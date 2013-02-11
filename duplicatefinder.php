<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$dmax = "ROUND(dmax::numeric,0)";
$ar = "ROUND(ar::numeric,1)";
$asprat = "ROUND(asprat::numeric,1)";
$attribs = "$dmax, $ar, $asprat, n_corners";

$sql = "SELECT $dmax AS size, $ar AS arearatio, $asprat AS asprat, n_corners, COUNT(*)
FROM kide
GROUP BY $attribs
HAVING (COUNT($dmax)>1 AND COUNT(n_corners)>1 AND COUNT($ar)>1 AND COUNT($asprat)>1)
ORDER BY $dmax";

try {
    $query = $yhteys->prepare($sql);
    $query->execute();
} catch (PDOException $e) {
    pdo_error($e);
}

$duplicates_array = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <title>Duplicate finder</title>
    </head>
    <body>
        <?php
        printSimpleTable($duplicates_array);
        ?>
    </body>
</html>
