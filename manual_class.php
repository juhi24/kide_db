<?php

require_once 'apu.php';
$yhteys = connect();

if (isset($_POST['classify'])) {
    $quality = empty($_POST['low_quality']);

    if (!isset($_POST['class_primary'])) {
        die('Please choose particle habit!');
    }

    if ($quality) {
        $qstr = 'true';
    } else {
        $qstr = 'false';
    }

    $c2_label = '';
    $c2_value = '';

    if (!empty($_POST['class_alt'])) {
        $c2_label = 'class2,';
        $c2_value = "'{$_POST['class_alt']}',";
    }

    $insert = "INSERT INTO man_classification (kide,class1,$c2_label classified_by,quality) VALUES ('{$_POST['fname']}','{$_POST['class_primary']}',$c2_value'{$_SESSION['valid_user']}',$qstr)";

    try {
        $insert_kysely = $yhteys->prepare($insert);
    } catch (PDOException $e) {
        pdo_error($e);
    }

    $insert_kysely->execute();

    $site_selection = $_POST['site'];
    $autoclass = $_POST['autoclass'];
    $method = $_POST['method'];

    $_SESSION['sizemin'] = $_POST['size_min'];
    $_SESSION['sizemax'] = $_POST['size_max'];
    $_SESSION['armin'] = $_POST['ar_min'];
    $_SESSION['armax'] = $_POST['ar_max'];
    $_SESSION['aspratmin'] = $_POST['asprat_min'];
    $_SESSION['aspratmax'] = $_POST['asprat_max'];
    $_SESSION['datestart'] = $_POST['date_start'];
    $_SESSION['dateend'] = $_POST['date_end'];

    //clear old values
    clear_selection(array_column($classarr,0), 'any');
    clear_selection($sitearr, 'other');

    //set new values
    print_r($site_selection);
    $_SESSION["selected_$autoclass"] = 'selected';
    foreach ($site_selection as $site) {
        $_SESSION["selected_$site"] = 'selected';
    }

    $sitesql = saittifiltteri($site_selection);
    $methodsql = '';

    if ($autoclass !== 'any') {
        $methodsql = "AND $method = '$autoclass'";
    }

    $select = "SELECT fname, class1
    FROM kide LEFT JOIN man_classification
    ON kide.fname=man_classification.kide
    WHERE class1 IS NULL
    AND dmax BETWEEN :sizemin AND :sizemax
    AND time BETWEEN :datestart AND :dateend
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND site $sitesql
    $methodsql
    AND kide.fname NOT LIKE '{$_POST['fname']}'";

    try {
        $kysely = $yhteys->prepare($select, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $kysely->setFetchMode(PDO::FETCH_ASSOC);
        $kysely->execute(array(':sizemin' => $_POST['size_min'], ':sizemax' => $_POST['size_max'],
            ':datestart' => $_POST['date_start'], ':dateend' => $_POST['date_end'], 'armin' => $_POST['ar_min'],
            ':armax' => $_POST['ar_max'], ':aspratmin' => $_POST['asprat_min'], ':aspratmax' => $_POST['asprat_max']));
    } catch (PDOException $e) {
        pdo_error($e);
    }

    $fname_next = choose_kide($kysely);

    ohjaa("manual_classification.php?success&fname=$fname_next");
} else if (isset($_POST['defaults'])) {
    reset_defaults();
    ohjaa('manual_classification.php');
} else {
    die('Invalid action!');
}
?>
