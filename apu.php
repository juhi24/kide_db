<?php
require_once 'apu/kyselyt.php';
require_once 'apu/sessio.php';

function ohjaa($osoite) {
  header("Location: $osoite");
  exit;
}

function on_kirjautunut() {
  global $sessio;
  return isset($sessio->kayttaja_id);
}

function varmista_kirjautuminen() {
  if (!on_kirjautunut()) {
    ohjaa('login.html');
  }
}

?>
