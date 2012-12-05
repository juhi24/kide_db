<?php
require_once 'apu.php';
require_once 'yhteys.php';
$yhteys = yhdista();

$id=$_POST["id"];
$c1=$_POST["class_primary"];
$c2=$_POST["class_alt"];
$author="anonymous"; //to-do
$quality = empty($_POST["low_quality"]);

if ($quality) {
    $qstr="true";
} else {
    $qstr="false";
}

$c2_label="";
$c2_value="";

if (!empty($c2)) {
    $c2_label="class2,";
    $c2_value="'$c2',";
} 

$insert = "INSERT INTO manual_classification (kide_id,class1,$c2_label classified_by,quality) VALUES ('$id','$c1',$c2_value'$author',$qstr)";

try {
    $insert_kysely = $yhteys->prepare($insert);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}
//$kysely->execute(array(':id'=>$id,
//                       ':c1'=>$c1,
//                       ':c2'=>$c2,
//                       ':author'=>$author,
//                       ':quality'=>$qstr));
$insert_kysely->execute();

$sizemin=$_POST["size_min"];
$sizemax=$_POST["size_max"];
$armin=$_POST["ar_min"];
$armax=$_POST["ar_max"];
$aspratmin=$_POST["asprat_min"];
$aspratmax=$_POST["asprat_max"];
$datestart=$_POST["date_start"];
$dateend=$_POST["date_end"];
$sites=$_POST["site"];

$sitesql =  saittifiltteri($sites);

$select = "SELECT id, class1
    FROM (SELECT * FROM kide) AS ids LEFT JOIN manual_classification
    ON ids.id=manual_classification.kide_id
    WHERE class1 IS NULL
    AND dmax BETWEEN :sizemin AND :sizemax
    AND time BETWEEN :datestart AND :dateend
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND site $sitesql
    AND ids.id NOT LIKE '$id'";

try {
$kysely = $yhteys->prepare($select, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$kysely->setFetchMode(PDO::FETCH_ASSOC);
$kysely->execute(array(':sizemin' => $sizemin, ':sizemax' => $sizemax,
        ':datestart' => $datestart, ':dateend' => $dateend, 'armin' => $armin,
        ':armax' => $armax, ':aspratmin' => $aspratmin, ':aspratmax' => $aspratmax));
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}

$rivi = $kysely->fetch();

$id_next = $rivi["id"];

ohjaa("manual_classification.php?id=$id_next");

?>
