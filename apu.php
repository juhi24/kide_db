<?php

session_start();

require_once 'apu/kyselyt.php';

$sitearr = array("AAF", "AMF", "NSA", "SGP", "TWP");
$classarr = array(
    array("B", "bullet"),
    array("P", "plate"),
    array("C", "column"),
    array("R", "rosette"),
    array("PA", "plate agg."),
    array("CA", "column agg."),
    array("RA", "rosette agg."),
    array("I", "irregular"));

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
    $sitesql = "";
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

function reset_defaults() {
    $_SESSION["man_sizemin"] = 0;
    $_SESSION["man_sizemax"] = 9999;
    $_SESSION["man_armin"] = 0;
    $_SESSION["man_armax"] = 1;
    $_SESSION["man_aspratmin"] = 1;
    $_SESSION["man_aspratmax"] = 9999;
    $_SESSION["man_datestart"] = "2000-01-01T00:00:00.000";
    $_SESSION["man_dateend"] = "2015-01-01T00:00:00.000";
    unset($_SESSION["man_sites"]);
    unset($_SESSION["man_autoclass"]);
    $_SESSION["man_method"] = "c5nn";
    
    clear_class_selection(); //clear class selection
    $_SESSION["selected_any"] = "selected"; //select default value
}

function clear_class_selection() {
    global $classarr;
    $_SESSION["selected_any"] = "";
    foreach ($classarr as $class) {
        $_SESSION["selected_$class[0]"] = "";
    }
}
?>
