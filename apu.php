<?php

require_once 'apu/kyselyt.php';
require_once 'apu/sessio.php';

function ohjaa($osoite) {
    header("Location: $osoite");
    exit;
}

function on_kirjautunut() {
    return isset($_SESSION["valid_user"]);
}

function varmista_kirjautuminen() {
    if (!on_kirjautunut()) {
        ohjaa('login.html');
    }
}

function saittifiltteri($sites) {
    $sitesql="";
    //Määritellään saittifiltteri.
    if (empty($sites)) {
        $sitesql = "IS NULL"; //mukana vain NULL jos yhtään saittia ei valittuna
    } else {
        $sitesql.="='$sites[0]'";
        $N = count($sites);
        for ($i = 1; $i < $N; $i++) {
            $sitesql.=" OR site='$sites[$i]'";
            if ($sites[$i] === "other") {
                $sitesql.=" OR site IS NULL"; //NULL site lasketaan other siteksi
            }
        }
    }
    return $sitesql;
}

?>
