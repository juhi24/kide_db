<?php

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

$statement = "INSERT INTO manual_classification (kide_id,class1,class2,classified_by,quality) VALUES ('$id','$c1','$c2','$author',$quality)";

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

?>
