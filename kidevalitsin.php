<?php
require_once 'apu.php';
require_once 'yhteys.php';
$yhteys = yhdista();

$statement = "SELECT id, class1
    FROM (SELECT id FROM kide) AS ids LEFT JOIN manual_classification
    ON ids.id=manual_classification.kide_id
    WHERE class1 IS NULL";

try {
    $kysely = $yhteys->prepare($statement);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("ERROR: " . $e->getMessage());
}
$kysely->setFetchMode(PDO::FETCH_ASSOC);
$kysely->execute();

$id = choose_kide($kysely);
?>
