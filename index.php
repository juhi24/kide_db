<?php
require_once 'apu.php';

login_check();

//Jos kirjautuminen oli ok, ohjaa csv-laatijaan
ohjaa('csv-generator.php');
?>
