<?php

//Yhteyden muodostus
try {
    $yhteys = new PDO("pgsql:host=localhost;dbname=jussitii", "jussitii", "SALASANA");
} catch (PDOException $e) {
    die("VIRHE: " . $e->getMessage());
}
$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement="SELECT * FROM kide";

//kyselyn suoritus
$kysely = $yhteys->prepare($statement);
$kysely->execute();



?>
