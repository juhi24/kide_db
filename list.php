<?php
require_once 'apu.php';
login_check();

$method = 'c5nn';

$classarr = getHabits();

if (isset($_GET['results'])) {
    //Dataset
    $from = 'FROM kide 
        LEFT JOIN man_classification ON kide.fname=man_classification.kide
        LEFT JOIN PCA_classification ON kide.fname=PCA_classification.kide
        LEFT JOIN flags ON kide.fname=flags.kide ';
    $where = "WHERE classified_by='{$_POST['user']}'
        AND pca_method='$method' ";
    
    //Table data
    $select = "SELECT fname, class1 AS man_class, pca_class, flag ";
    if (!empty($_POST['habit'])) {
        $where .= "AND class1='{$_POST['habit']}' ";
    }

    $result_arr = fetchAll_with_headers(pdo_query($select . $from . $where . 'ORDER BY class1, pca_class, fname'));
    $result_arr = imagecol($result_arr,'image','','');
    $result_arr = imagecol($result_arr,'plot','perimeter/','_out');
    
    //Total number
    $countq = pdo_query('SELECT COUNT(fname) ' . $from . $where);
    $count = $countq->fetch(PDO::FETCH_NUM);
    
    //Plot data
    $plot_select = 'SELECT ';
    foreach ($classarr as $habit) {
        $plot_select .= SQLparticle_count('pca_class', $habit[0]) . "AS $habit[0] ,";
    }
    $plot_select = rtrim($plot_select, ',');
    
    $plotq = pdo_query($plot_select . $from . $where);
    $plot_data = $plotq->fetchAll(PDO::FETCH_ASSOC);
    
    $_SESSION['listpie'] = array();
    foreach ($classarr as $habit) {
        $_SESSION['listpie'][] = array($habit[1],$plot_data[0][strtolower($habit[0])]);
    }
    
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <title>Particle browser</title>
    </head>
    <body>
        <?php require_once 'header.html'; ?>
        <h2>Particle browser</h2>

        <form action="list.php?results" method="post" enctype="multipart/form-data">
            Manually classified by <select name="user">
                <?php
                $userlistq = pdo_query('SELECT username FROM users ORDER BY username');
                $userlist = $userlistq->fetchAll();
                foreach ($userlist as $user) {
                    echo "<option value='$user[0]'>$user[0]</option>";
                }
                ?>
            </select>
            as <select name="habit">
                <option value="">ANY HABIT</option>
                <?php
                foreach ($classarr as $habit) {
                    echo "<option value='$habit[0]'>$habit[1]</option>";
                }
                ?>
            </select>
            <p><input type="submit" name="submit"></p>
        </form>
        <?php
        if (isset($_GET['results'])) {
            echo '<h3>Query results</h3>';
            if (empty($result_arr)) {
                echo '<p>No results.</p>';
            } else {
                echo "<p>$count[0] total particles found.</p>";
                echo HTMLplot('listpie');
                print_simple_table($result_arr);
            }
        }
        ?>

    </body>
</html>