<?php
require_once 'apu.php';
$yhteys = connect();

$statement = "SELECT fname, class1
    FROM (SELECT fname FROM kide) AS fnames LEFT JOIN man_classification
    ON fnames.fname=man_classification.kide
    WHERE class1 IS NULL";

//prepare and execute
try {
    $kysely = $yhteys->prepare($statement);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("ERROR: " . $e->getMessage());
}
$kysely->setFetchMode(PDO::FETCH_ASSOC);
$kysely->execute();

$fname = choose_kide($kysely); //pick a particle that has an image
?>
