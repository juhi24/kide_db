<?php

require_once 'apu.php';
require_once 'yhteys.php';
$yhteys = yhdista();

if (isset($_POST['classify'])) {
    $id = $_POST["id"];
    $c1 = $_POST["class_primary"];
    $c2 = $_POST["class_alt"];
    $author = $_SESSION["valid_user"];
    $quality = empty($_POST["low_quality"]);

    if (!isset($c1)) {
        die('Please choose particle habit!');
    }

    if ($quality) {
        $qstr = "true";
    } else {
        $qstr = "false";
    }

    $c2_label = "";
    $c2_value = "";

    if (!empty($c2)) {
        $c2_label = "class2,";
        $c2_value = "'$c2',";
    }

    $insert = "INSERT INTO manual_classification (kide_id,class1,$c2_label classified_by,quality) VALUES ('$id','$c1',$c2_value'$author',$qstr)";

    try {
        $insert_kysely = $yhteys->prepare($insert);
    } catch (PDOException $e) {
        file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
        die("ERROR: " . $e->getMessage());
    }
//$kysely->execute(array(':id'=>$id,
//                       ':c1'=>$c1,
//                       ':c2'=>$c2,
//                       ':author'=>$author,
//                       ':quality'=>$qstr));
    $insert_kysely->execute();

    $sizemin = $_POST["size_min"];
    $sizemax = $_POST["size_max"];
    $armin = $_POST["ar_min"];
    $armax = $_POST["ar_max"];
    $aspratmin = $_POST["asprat_min"];
    $aspratmax = $_POST["asprat_max"];
    $datestart = $_POST["date_start"];
    $dateend = $_POST["date_end"];
    $site_selection = $_POST["site"];
    $autoclass = $_POST["autoclass"];
    $method = $_POST["method"];

    $_SESSION["man_sizemin"] = $sizemin;
    $_SESSION["man_sizemax"] = $sizemax;
    $_SESSION["man_armin"] = $armin;
    $_SESSION["man_armax"] = $armax;
    $_SESSION["man_aspratmin"] = $aspratmin;
    $_SESSION["man_aspratmax"] = $aspratmax;
    $_SESSION["man_datestart"] = $datestart;
    $_SESSION["man_dateend"] = $dateend;
    $_SESSION["man_sites"] = $site_selection;
    $_SESSION["man_autoclass"] = $autoclass;
    $_SESSION["man_method"] = $method;

//clear old value
    clear_class_selection();

//set new value
    $_SESSION["selected_$autoclass"] = "selected";

    $sitesql = saittifiltteri($site_selection);
    $methodsql = "";

    if ($autoclass !== "any") {
        $methodsql = "AND $method = '$autoclass'";
    }

    $select = "SELECT id, class1
    FROM (SELECT * FROM kide) AS ids LEFT JOIN manual_classification
    ON ids.id=manual_classification.kide_id
    WHERE class1 IS NULL
    AND dmax BETWEEN :sizemin AND :sizemax
    AND time BETWEEN :datestart AND :dateend
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND site $sitesql
    $methodsql
    AND ids.id NOT LIKE '$id'";

    try {
        $kysely = $yhteys->prepare($select, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $kysely->setFetchMode(PDO::FETCH_ASSOC);
        $kysely->execute(array(':sizemin' => $sizemin, ':sizemax' => $sizemax,
            ':datestart' => $datestart, ':dateend' => $dateend, 'armin' => $armin,
            ':armax' => $armax, ':aspratmin' => $aspratmin, ':aspratmax' => $aspratmax));
    } catch (PDOException $e) {
        file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
        die("ERROR: " . $e->getMessage());
    }

    $id_next = choose_kide($kysely);

    ohjaa("manual_classification.php?id=$id_next");
} else if (isset($_POST['defaults'])) {
    reset_defaults();
    ohjaa('manual_classification.php');
} else {
    die("Invalid action!");
}
?>
