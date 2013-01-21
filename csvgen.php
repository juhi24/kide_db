<?php

require_once 'apu.php';

//connection
require_once 'yhteys.php';
$yhteys = yhdista();

$pca_method = 'c5nn';

//values from the form
$reso = $_POST["resolution"];
$tunit = $_POST["timeunit"];
$sizemin = $_POST["size_min"];
$sizemax = $_POST["size_max"];
$armin = $_POST["ar_min"];
$armax = $_POST["ar_max"];
$aspratmin = $_POST["asprat_min"];
$aspratmax = $_POST["asprat_max"];
$datestart = $_POST["date_start"];
$dateend = $_POST["date_end"];
$site_selection = $_POST["site"];

$sitesql = saittifiltteri($site_selection);
$qualitysql = "";

//if qualityfilter is checked
if (!empty($_POST["quality"])) {
    $qualitysql = "AND id NOT IN (SELECT kide_id FROM man_class WHERE quality IS NOT NULL OR quality=FALSE)";
}

//SQL to count particles by habit
foreach ($classarr as $class) {
    $count .= SQLparticle_count($pca_method, $class[0]) . " AS $class[0], ";
}

$statement = "SELECT
        round_$tunit(time, :reso ) AS interval,
        COUNT(*) AS tot,
        $count
        AVG(dmax)::real AS dmax_mean,
        (sum(ar*area(ar,dmax))/sum(area(ar,dmax)))::real AS ar_weighted_mean
FROM kide
WHERE dmax BETWEEN :sizemin AND :sizemax
AND time BETWEEN :datestart AND :dateend
AND ar BETWEEN :armin AND :armax
AND asprat BETWEEN :aspratmin AND :aspratmax
AND site $sitesql
$qualitysql
GROUP BY interval
ORDER BY interval";

//prepare and execute query
try {
    $kysely = $yhteys->prepare($statement, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $kysely->setFetchMode(PDO::FETCH_ASSOC);
    $kysely->execute(array(':reso' => $reso, ':sizemin' => $sizemin, ':sizemax' => $sizemax,
        ':datestart' => $datestart, ':dateend' => $dateend, 'armin' => $armin,
        ':armax' => $armax, ':aspratmin' => $aspratmin, ':aspratmax' => $aspratmax));
} catch (PDOException $e) {
    pdo_error($e);
}

//save query results to a csv-file
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

// Start csv download
header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="output.csv"');
readfile('output.csv')
?>