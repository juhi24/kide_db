<?php

require_once 'yhteys.php';
$yhteys = yhdista();

$statement = "SELECT id FROM kide ORDER BY random() LIMIT 1000;";

try {
    $kysely = $yhteys->prepare($statement);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}

$id = $kysely->fetch();

header("Location: manual_classification.php?id=$id")

?>
