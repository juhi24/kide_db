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

//SQL to count matched particles by class
function SQLmatch_count($method, $class) {
    return "COUNT(NULLIF(($method=class1 OR $method=class2) AND $method='$class',FALSE))";
}

//SQL to count particles by class
function SQLparticle_count($ref, $class) {
    return "COUNT(NULLIF($ref='$class',FALSE))";
}

//HTML measurement site selector boxes
function HTMLsite($remember_selection) {
    global $sitearr;
    foreach ($sitearr as $site) {
        $siteboxes .= "$site<input name='site[]' value='$site' type='checkbox' ";
        if ($remember_selection) {
            $siteboxes .= $_SESSION["selected_$site"];
        }
        $siteboxes .= '>&nbsp;&nbsp;&nbsp;';
    }
    $siteboxes .= 'Other<input name="site[]" value="other" type="checkbox"><br>';
    return $siteboxes;
}

//HTML date fields
function HTMLdate($start, $end) {
    return "Time frame: From <input type='datetime-local' name='date_start' value='$start'> to <input type='datetime-local' name='date_end' value='$end'> <br>";
}

//HTML field for dmax
function HTMLdmax($min, $max) {
    return "Maximum diameter between <input maxlength='4' size='5' name='size_min' value='$min' min='0'>um and <input maxlength='4' size='5' name='size_max' value='$max'>um<br>";
}

//HTML field for ar
function HTMLar($min, $max) {
    return "Area ratio between <input maxlength='4' size='5' name='ar_min' value='$min'> and <input maxlength='4' size='5' name='ar_max' value='$max'><br>";
}

//HTML field for asprat
function HTMLasprat($min, $max) {
    return "Aspect ratio between <input maxlength='4' size='5' name='asprat_min' value='$min'> and <input maxlength='4' size='5' name='asprat_max' value='$max'><br>";
}

function printSimpleTable($array2d) {
    echo '<table>';
    foreach ($array2d as $rows => $row) {
                echo '<tr>';
                foreach ($row as $col => $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
    echo '</table>';
}

//initialize or reset session values in forms
function reset_defaults() {
    global $default, $classarr;
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
    //$_SESSION["man_method"] = $default["method"];

    clear_selection($classarr, 'any'); //clear class selection
    $_SESSION["selected_any"] = "selected"; //select default value
}

//clear "selected" elements
function clear_selection($optionsarr, $extra_option) {
    $_SESSION["selected_$extra_option"] = "";
    foreach ($optionsarr as $option) {
        $_SESSION["selected_$option"] = "";
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
