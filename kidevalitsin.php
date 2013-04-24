<?php
require_once 'apu.php';
$yhteys = connect();

$statement = "SELECT id, class1
    FROM (SELECT id FROM kide) AS ids LEFT JOIN man_class
    ON ids.id=man_class.kide_id
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

$id = choose_kide($kysely); //pick a particle that has an image
?>
