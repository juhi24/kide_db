<?php

session_start();

require_once 'apu/kyselyt.php';

//Measurement sites
$sitearr = array("AAF", "AMF", "NSA", "SGP", "TWP");

//Habit classes
$classarr = array(
    array("B", "bullet"),
    array("P", "plate"),
    array("C", "column"),
    array("R", "rosette"),
    array("PA", "plate agg."),
    array("CA", "column agg."),
    array("RA", "rosette agg."),
    array("I", "irregular")
);

//IC-PCA methods
$methodarr = array(
    array('nw3nn', 'no weight 3 nearest neighbours'),
    array('nw5nn', 'no weight 5 nearest neighbours'),
    array('c1nn', 'nearest neighbour'),
    array('c3nn', '3 nearest neighbours'),
    array('c5nn', '5 nearest neighbours'),
    array('bayes', 'bayesian')
);

//Default values in forms
$default = array(
    "sizemin" => 0,
    "sizemax" => 9999,
    "armin" => 0,
    "armax" => 1,
    "aspratmin" => 1,
    "aspratmax" => 9999,
    "datestart" => "2000-01-01T00:00:00.000",
    "dateend" => "2015-01-01T00:00:00.000",
    "method" => "c5nn"
);

//location wrapper
function ohjaa($osoite) {
    header("Location: $osoite");
    exit;
}

//check if user is logged in
function on_kirjautunut() {
    return isset($_SESSION["valid_user"]);
}

//if user not logged in, redirect to login form
function varmista_kirjautuminen() {
    if (!on_kirjautunut()) {
        ohjaa('login.html');
    }
}

//generate SQL to filter sites
function saittifiltteri($sites) {
    $sitesql = "";
    if (empty($sites) || $sites[0] === "other") {
        $sitesql .= "IS NULL"; //if none or only "other" selected
    } else {
        $sitesql.="='$sites[0]'";
        $N = count($sites);
        for ($i = 1; $i < $N; $i++) {
            $sitesql.=" OR site='$sites[$i]'";
            if ($sites[$i] === "other") {
                $sitesql.=" OR site IS NULL"; //NULL counts as "other"
            }
        }
    }
    return $sitesql;
}

//initialize or reset session values in forms
function reset_defaults() {
    global $default;
    $_SESSION["man_sizemin"] = $default["sizemin"];
    $_SESSION["man_sizemax"] = $default["sizemax"];
    $_SESSION["man_armin"] = $default["armin"];
    $_SESSION["man_armax"] = $default["armax"];
    $_SESSION["man_aspratmin"] = $default["aspratmin"];
    $_SESSION["man_aspratmax"] = $default["aspratmax"];
    $_SESSION["man_datestart"] = $default["datestart"];
    $_SESSION["man_dateend"] = $default["dateend"];
    unset($_SESSION["man_sites"]);
    unset($_SESSION["man_autoclass"]);
    $_SESSION["man_method"] = $default["method"];

    clear_class_selection(); //clear class selection
    $_SESSION["selected_any"] = "selected"; //select default value
}

//clear "selected" elements
function clear_class_selection() {
    global $classarr;
    $_SESSION["selected_any"] = "";
    foreach ($classarr as $class) {
        $_SESSION["selected_$class[0]"] = "";
    }
}

//pick the first query result particle that has an image
function choose_kide(PDOStatement $kysely) {
    while ($rivi = $kysely->fetch()) {
        $id = $rivi["id"];
        if (file_exists("img/kide/$id.jpg")) {
            break;
        }
    }
    return $id;
}

//log pdo errors and show error messages
function pdo_error(PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die("ERROR: " . $e->getMessage());
}

?>
