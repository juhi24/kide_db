<?php

function yhdista() {
    try {
        $yhteys = new PDO("pgsql:host=localhost;dbname=jussitii", "jussitii", "f2da6f1d197719bb");
    } catch (PDOException $e) {
        file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
        die("ERROR: " . $e->getMessage());
    }
    $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $yhteys;
}

?>
