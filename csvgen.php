<?php

//Yhteyden muodostus
try {
    $yhteys = new PDO("pgsql:host=localhost;dbname=jussitii", "jussitii", "f2da6f1d197719bb");
} catch (PDOException $e) {
    die("VIRHE: " . $e->getMessage());
}
$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement="SELECT id,c5nn FROM kide WHERE id='0125-043149_564_001'";

//kyselyn suoritus
$kysely = $yhteys->prepare($statement);
$kysely->execute();

$file = fopen("output.csv", "w");

while ($rivi = $kysely->fetch()) {
    var_dump($rivi);
    fputcsv($file, $rivi);
}
fclose($file);



?>