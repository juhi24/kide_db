<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$sql="SELECT round(dmax::numeric,1), COUNT(round(dmax::numeric,1))
FROM kide
GROUP BY round(dmax::numeric,1)
HAVING (COUNT(round(dmax::numeric,1))>1)
ORDER BY ROUND(dmax::numeric,1)";

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Duplicate finder</title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
