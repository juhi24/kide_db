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

$statement = "INSERT INTO manual_classification (kide_id,class1,$c2_label classified_by,quality) VALUES ('$id','$c1',$c2_value'$author',$qstr)";

try {
    $kysely = $yhteys->prepare($statement);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}
//$kysely->execute(array(':id'=>$id,
//                       ':c1'=>$c1,
//                       ':c2'=>$c2,
//                       ':author'=>$author,
//                       ':quality'=>$qstr));
$kysely->execute();
ohjaa("manual_classification.php");

?>
