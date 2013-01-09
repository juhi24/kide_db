<?php
require_once 'apu.php';
require_once 'yhteys.php';
varmista_kirjautuminen();
$yhteys = yhdista();

$select = "SELECT COUNT(*) FROM manual_classification 
    WHERE classified_by = '{$_SESSION["valid_user"]}'
    ";

try {
    $kysely = $yhteys->prepare($select);
} catch (PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("ERROR: " . $e->getMessage());
}
$kysely->execute();
$rivi = $kysely->fetch();
$count_classified = $rivi["count"]
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

