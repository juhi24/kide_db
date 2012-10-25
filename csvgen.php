<?php

//Yhteyden muodostus
require_once 'yhteys.php';

$reso=$_POST["resolution"];
$tunit=$_POST["timeunit"];
$sizemin=$_POST["size_min"];
$sizemax=$_POST["size_max"];
$armin=$_POST["ar_min"];
$armax=$_POST["ar_max"];
$aspratmin=$_POST["asprat_min"];
$aspratmax=$_POST["asprat_max"];
$datestart=$_POST["date_start"];
$dateend=$_POST["date_end"];

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
AND time BETWEEN '$datestart' AND '$dateend'
AND ar BETWEEN $armin AND $armax
AND asprat BETWEEN $aspratmin AND $aspratmax
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
$otsikot = array();
while ($rivi = $kysely->fetch()) {
    if (empty($otsikot)) {
        $otsikot = array_keys($rivi);
        fputcsv($file, $otsikot);
    }
    //var_dump($rivi);
    fputcsv($file, $rivi);
}
fclose($file);

require_once 'csvLataaja.php';

?>