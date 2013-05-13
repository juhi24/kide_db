<?php
require_once 'apu.php';
login_check();
$yhteys = connect();

//config
$method = 'c5nn';
$sizereso = 50;

$classarr = getHabits();

if (isset($_GET['results'])) {
    //SQL for counting particles
    foreach ($classarr as $class) {
        $count_match .= SQLmatch_count('pca_class', $class[0]) . " AS $class[0], ";
        $count_matchrelat .= 'ROUND(' . SQLmatch_count($method, $class[0]) . '/NULLIF(' . SQLparticle_count('pca_class', $class[0]) . ",0)::numeric*100,2) AS $class[0], ";
        $count_pca .= SQLparticle_count('pca_class', $class[0]) . " AS $class[0], ";
        $count_user1 .= SQLparticle_count('class1', $class[0]) . " AS $class[0], ";
        $count_user2 .= SQLparticle_count('class2', $class[0]) . " AS $class[0], ";
    }

    //SELECT table data
    $select_userclass1 = "SELECT 2 AS row_order, 'user primary classification', $count_user1 COUNT(class1) AS total";
    $select_userclass2 = "SELECT 3 AS row_order, 'user secondary classification', $count_user2 COUNT(class2) AS total";
    $select_matchedclass = "SELECT 4 AS row_order, 'IC-PCA matches with user primary or secondary', $count_match COUNT(NULLIF(pca_class=class1 OR pca_class=class2,FALSE)) AS total";
    $select_matchedrelat = "SELECT 5 AS row_order, 'matched %', $count_matchrelat ROUND(COUNT(NULLIF(pca_class=class1 OR pca_class=class2,FALSE))/NULLIF(COUNT(pca_class),0)::numeric*100,2) AS total";
    $select_pcaclass = "SELECT 1 AS row_order, 'IC-PCA classification', $count_pca COUNT(pca_class) AS total";
    
    //WHERE
    $where = 'WHERE dmax BETWEEN :sizemin AND :sizemax
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND time BETWEEN :datestart AND :dateend ';

    //FROM
    $from_pca_join_man = "FROM (SELECT fname, time, dmax, ar, asprat, pca_class, pca_method FROM kide LEFT JOIN pca_classification ON (kide.fname=pca_classification.kide) WHERE pca_method='$method') AS pca
    RIGHT JOIN (SELECT kide, class1, class2 FROM man_classification WHERE classified_by='{$_SESSION['valid_user']}') AS usr
    ON pca.fname=usr.kide $where";
    
    //GROUP BY sizedist
    $groupby_sizedist = "GROUP BY sizefloor ORDER BY sizefloor";

    //prep and exec table query
    try {
        $table_query = $yhteys->prepare("SELECT * FROM ($select_pcaclass $from_pca_join_man 
        UNION $select_matchedclass $from_pca_join_man 
            UNION $select_userclass1 $from_pca_join_man 
                UNION $select_userclass2 $from_pca_join_man 
                    UNION $select_matchedrelat $from_pca_join_man) AS A ORDER BY row_order");
        $table_query->execute(array(':sizemin' => $_POST['size_min'], ':sizemax' => $_POST['size_max'],
            ':datestart' => $default['datestart'], ':dateend' => $default['dateend'], 'armin' => $_POST['ar_min'],
            ':armax' => $_POST['ar_max'], ':aspratmin' => $_POST['asprat_min'], ':aspratmax' => $_POST['asprat_max']));
    } catch (PDOException $e) {
        pdo_error($e);
    }
    $stat_array = $table_query->fetchAll(PDO::FETCH_ASSOC);
    
    //Extra options
    $left_join_man = 'LEFT JOIN man_classification ON (kide.fname=man_classification.kide) ';
    $and_jun = "AND classified_by='Jun' AND class1='U' ";
    
    //SELECT sizedist
    $count_pca_trimmed = substr_replace($count_pca, '', -2);
    $select_sizedist = "SELECT FLOOR(dmax/$sizereso.0)*$sizereso AS sizefloor, $count_pca_trimmed ";
    
    //FROM
    $from_pca = 'FROM kide LEFT JOIN pca_classification ON (kide.fname=pca_classification.kide) ';
    $from_pca .= $left_join_man; //extra
    $from_pca .= $where . "AND pca_method='$method' ";
    $from_pca .= $and_jun; //extra
    
    //prep and exec sizedist query
    //echo $select_sizedist . $from_dataset . $groupby_sizedist;
    try {
        $sizedist_query = $yhteys->prepare($select_sizedist . $from_pca . $groupby_sizedist);
        $sizedist_query->execute(array(':sizemin' => $_POST['size_min'], ':sizemax' => $_POST['size_max'],
            ':datestart' => $default['datestart'], ':dateend' => $default['dateend'], 'armin' => $_POST['ar_min'],
            ':armax' => $_POST['ar_max'], ':aspratmin' => $_POST['asprat_min'], ':aspratmax' => $_POST['asprat_max']));
    } catch (PDOException $e) {
        pdo_error($e);
    }
    $sizedist_array = $sizedist_query->fetchAll(PDO::FETCH_ASSOC);
    
    //save new values to session
    $_SESSION['sizemin'] = $_POST['size_min'];
    $_SESSION['sizemax'] = $_POST['size_max'];
    $_SESSION['armin'] = $_POST['ar_min'];
    $_SESSION['armax'] = $_POST['ar_max'];
    $_SESSION['aspratmin'] = $_POST['asprat_min'];
    $_SESSION['aspratmax'] = $_POST['asprat_max'];
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
        <?php require_once 'header.html'; ?>
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
            
            //Prepare performance plot
            $plotdata = array();
            foreach ($classarr as $i => $class) {
                $plotdata[] = array($class[0], $stat_array[4][strtolower($class[0])]);
            }
            $plotdata[] = array('TOTAL', $stat_array[4]['total']);
            $_SESSION['statplot'] = $plotdata;
            
            //Prepare sizedist plot
            //TODO: Find better way than using _SESSION
            $_SESSION['sizedistplot'] = $sizedist_array;
            $_SESSION['classes'] = array();
            foreach ($classarr as $class) {
                $_SESSION['classes'][] = $class[1];
            }
            
            echo '<h3>Plots</h3>';
            echo '<img src="graphs/statplot.php">';
            echo '<img src="graphs/sizedist.php">';
        }
        ?>
    </body>
</html>
