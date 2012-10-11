<?php

//Yhteyden muodostus
try {
    $yhteys = new PDO("pgsql:host=localhost;dbname=jussitii", "jussitii", "f2da6f1d197719bb");
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}
$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$reso=$_POST["resolution"];
$tunit=$_POST["timeunit"];
$sizemin=$_POST["size_min"];
$sizemax=$_POST["size_max"];

$statement="SELECT
        round_$tunit(time,$reso) AS interval,
        COUNT(*) AS tot,
        COUNT(NULLIF(c5nn='P',FALSE)) AS P, 
        COUNT(NULLIF(c5nn='B',FALSE)) AS B,
        COUNT(NULLIF(c5nn='C',FALSE)) AS C,
        COUNT(NULLIF(c5nn='I',FALSE)) AS I,
        COUNT(NULLIF(c5nn='R',FALSE)) AS R, 
        COUNT(NULLIF(c5nn='RA',FALSE)) AS RA,
        COUNT(NULLIF(c5nn='PA',FALSE)) AS PA,
        COUNT(NULLIF(c5nn='CA',FALSE)) AS CA,
        AVG(dmax)::real AS dmax_mean,
        (sum(ar*area(ar,dmax))/sum(area(ar,dmax)))::real AS ar_weighted_mean
FROM kide
WHERE dmax BETWEEN $sizemin AND $sizemax
AND time BETWEEN '01-01-2000 00:00:00' AND '01-01-2012 00:00:00'
GROUP BY interval
ORDER BY interval
;";

//kyselyn suoritus

try {
$kysely = $yhteys->prepare($statement);
$kysely->setFetchMode(PDO::FETCH_ASSOC);
$kysely->execute();
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("VIRHE: " . $e->getMessage());
}

$file = fopen("output.csv", "w");

while ($rivi = $kysely->fetch()) {
    //var_dump($rivi);
    fputcsv($file, $rivi);
}
fclose($file);

require_once 'csvLataaja.php';

?>