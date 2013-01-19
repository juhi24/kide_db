<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$ref='c5nn';
$count_user="";
$count_pca="";
foreach ($classarr as $class) {
    $count_user .= "COUNT(NULLIF((c5nn=class1 OR c5nn=class2) AND $ref='$class[0]',FALSE)) AS $class[0], ";
    $count_pca .= "COUNT(NULLIF($ref='$class[0]',FALSE)) AS $class[0], ";
}

$select_userdata = "SELECT 2 AS row_order, 'matched with IC-PCA classification', $count_user COUNT(NULLIF(c5nn=class1 OR c5nn=class2,FALSE)) AS total";
$select_pcadata = "SELECT 1 AS row_order, 'particles classified', $count_pca COUNT(*) AS total";
$from_dataset = "FROM (SELECT id, c5nn FROM kide) AS pca
RIGHT JOIN (SELECT kide_id, class1, class2 FROM man_class WHERE classified_by='{$_SESSION["valid_user"]}') AS usr
ON pca.id=usr.kide_id";

//prepare and execute query
try {
    $kysely = $yhteys->prepare("SELECT * FROM ($select_pcadata $from_dataset UNION $select_userdata $from_dataset) AS A ORDER BY row_order");
    $kysely->execute();
} catch (PDOException $e) {
    pdo_error($e);
}
$stat_array = $kysely->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        
        <style>
            .hide1 tr *:nth-child(1) {
                display: none;
            }
        </style>

        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Statistics</title>
    </head>
    <body>
        <?php require_once 'apu/header.html'; ?>
        <h2>Statistics</h2>
        <h3>Manual classification results</h3>
        <table class="hide1" border="1">
            <?php
            echo '<tr><td></td><td style="visibility:collapse;"></td>';
            foreach ($classarr as $class) {
                echo "<td>$class[1]</td>";
            }
            echo '<td>Total</td></tr>';
            foreach ($stat_array as $rows => $row) {
                echo '<tr>';
                foreach ($row as $col => $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
            ?>
        </table>
    </body>
</html>

