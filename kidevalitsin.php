<?php

require_once 'yhteys.php';
$yhteys = yhdista();

$statement = "SELECT id FROM kide";

try {
    $kysely = $yhteys->prepare($statement);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}
$kysely->setFetchMode(PDO::FETCH_ASSOC);
$kysely->execute();
$rivi = $kysely->fetch();

$id = $rivi["id"];

?>