<?php
require_once 'lib/phplot.php';
require_once 'apu.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$method = 'c5nn';

if (isset($_GET['results'])) {
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
    $from_dataset = "FROM (SELECT id, time, $method, dmax, ar, asprat FROM kide) AS pca
    RIGHT JOIN (SELECT kide_id, class1, class2 FROM man_class WHERE classified_by='{$_SESSION["valid_user"]}') AS usr
    ON pca.id=usr.kide_id
    WHERE dmax BETWEEN :sizemin AND :sizemax
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND time BETWEEN :datestart AND :dateend";

    //prepare and execute query
    try {
        $kysely = $yhteys->prepare("SELECT * FROM ($select_pcaclass $from_dataset 
        UNION $select_matchedclass $from_dataset 
            UNION $select_userclass1 $from_dataset 
                UNION $select_userclass2 $from_dataset 
                    UNION $select_matchedrelat $from_dataset) AS A ORDER BY row_order");
        $kysely->execute(array(':sizemin' => $_POST['size_min'], ':sizemax' => $_POST['size_max'],
            ':datestart' => $default['datestart'], ':dateend' => $default['dateend'], 'armin' => $_POST['ar_min'],
            ':armax' => $_POST['ar_max'], ':aspratmin' => $_POST['asprat_min'], ':aspratmax' => $_POST['asprat_max']));
    } catch (PDOException $e) {
        pdo_error($e);
    }
    $stat_array = $kysely->fetchAll(PDO::FETCH_ASSOC);
    
    //save new values to session
    $_SESSION["sizemin"] = $_POST["size_min"];
    $_SESSION["sizemax"] = $_POST["size_max"];
    $_SESSION["armin"] = $_POST["ar_min"];
    $_SESSION["armax"] = $_POST["ar_max"];
    $_SESSION["aspratmin"] = $_POST["asprat_min"];
    $_SESSION["aspratmax"] = $_POST["asprat_max"];
}
?>

<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" type="text/css" href="css/global.css">

        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Statistics</title>
    </head>
    <body>
        <?php require_once 'apu/header.html'; ?>
        <h2>Statistics</h2>
        <h3>Stat filters</h3>
        <form name="getstats" method="post" action="statistics.php?results">
            <fieldset><legend>Particle properties</legend>
                <?php
                echo HTMLdmax($_SESSION['sizemin'], $_SESSION['sizemax']);
                echo HTMLar($_SESSION['armin'], $_SESSION['armax']);
                echo HTMLasprat($_SESSION['aspratmin'], $_SESSION['aspratmax']);
                ?>
            </fieldset>
            <input type="submit" name="submit" value="Generate statistics">
        </form>
        <?php
        if (isset($_GET['results'])) {
            echo '<h3>Manual classification results</h3>';
            echo '<table class="hide1">';

            echo '<tr><td></td><td></td>';
            foreach ($classarr as $class) {
                echo "<td>$class[1]</td>";
            }
            echo '<td>Total</td></tr>';
            foreach ($stat_array as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
            echo '</table>';
            
            //print_r($stat_array);
            
            $plotdata = array();
            foreach ($classarr as $i => $class) {
                $plotdata[] = array($class[0], $stat_array[4][strtolower($class[0])]);
            }
            $plotdata[] = array('TOTAL', $stat_array[4]['total']);
            $_SESSION['statplot'] = $plotdata;
            
            //echo '<img src="graphs/statplot.php">';
        }
        ?>
        
    </body>
</html>
