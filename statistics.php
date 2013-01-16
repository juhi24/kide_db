<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$count_all = "SELECT COUNT(*) FROM man_class
    WHERE classified_by = '{$_SESSION["valid_user"]}'
    ";

//prepare and execute query
try {
    $kysely = $yhteys->prepare($count_all);
} catch (PDOException $e) {
    pdo_error($e);
}
$kysely->execute();
$rivi = $kysely->fetch();
$count_classified = $rivi["count"] //result
?>

<!DOCTYPE html>
<html>
    <head>

        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Statistics</title>
    </head>
    <body>
        <?php require_once 'apu/header.html'; ?>
        <h2>Statistics</h2>
        <p>Particles classified: <?php echo $count_classified; ?></p>
    </body>
</html>

