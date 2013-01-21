<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$method = 'c5nn';

//SQL for counting particles
foreach ($classarr as $class) {
    $count_match .= SQLmatch_count($method, $class[0]) . " AS $class[0], ";
    $count_matchrelat .= 'ROUND(' . SQLmatch_count($method, $class[0]) . '/NULLIF(' . SQLparticle_count($method, $class[0]) . ",0)::numeric*100,2) AS $class[0], ";
    $count_pca .= SQLparticle_count($method, $class[0]) . " AS $class[0], ";
    $count_user1 .= SQLparticle_count('class1', $class[0]) . " AS $class[0], ";
    $count_user2 .= SQLparticle_count('class2', $class[0]) . " AS $class[0], ";
}

//SELECT
$select_userclass1 = "SELECT 2 AS row_order, 'user primary classification', $count_user1 COUNT(class1) AS total";
$select_userclass2 = "SELECT 3 AS row_order, 'user secondary classification', $count_user2 COUNT(class2) AS total";
$select_matchedclass = "SELECT 4 AS row_order, 'IC-PCA matches with user primary or secondary', $count_match COUNT(NULLIF($method=class1 OR $method=class2,FALSE)) AS total";
$select_matchedrelat = "SELECT 5 AS row_order, 'matched %', $count_matchrelat ROUND(COUNT(NULLIF($method=class1 OR $method=class2,FALSE))/COUNT($method)::numeric*100,2) AS total";
$select_pcaclass = "SELECT 1 AS row_order, 'IC-PCA classification', $count_pca COUNT($method) AS total";

//FROM
$from_dataset = "FROM (SELECT id, $method FROM kide) AS pca
RIGHT JOIN (SELECT kide_id, class1, class2 FROM man_class WHERE classified_by='{$_SESSION["valid_user"]}') AS usr
ON pca.id=usr.kide_id";

//prepare and execute query
try {
    $kysely = $yhteys->prepare("SELECT * FROM ($select_pcaclass $from_dataset 
        UNION $select_matchedclass $from_dataset 
            UNION $select_userclass1 $from_dataset 
                UNION $select_userclass2 $from_dataset 
                    UNION $select_matchedrelat $from_dataset) AS A ORDER BY row_order");
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
