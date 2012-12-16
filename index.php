<?php
require_once 'apu.php';

varmista_kirjautuminen();

//Jos kirjautuminen oli ok, ohjaa csv-laatijaan
ohjaa('csv-generator.php');
?>
