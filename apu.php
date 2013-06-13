<?php

session_start();

require_once 'apu/queries.php';
require_once 'apu/pdo.php';
require_once 'apu/html.php';
require_once 'apu/sql.php';
require_once 'apu/connection.php';
require_once 'apu/session.php';

//Default values in forms
$default = array(
    'sizemin' => 0,
    'sizemax' => 9999,
    'armin' => 0,
    'armax' => 1,
    'aspratmin' => 1,
    'aspratmax' => 9999,
    'datestart' => '2000-01-01T00:00:00.000',
    'dateend' => '2015-01-01T00:00:00.000',
    'method' => 'c5nn'
);

//location wrapper
function ohjaa($osoite) {
    header("Location: $osoite");
    exit;
}

//if user not logged in, redirect to login form
function login_check() {
    if (!logged_in()) {
        ohjaa('login.html');
    }
}

//pick the first query result particle that has an image
function choose_kide(PDOStatement $kysely) {
    $fname = '';
    while ($rivi = $kysely->fetch()) {
        $fname = $rivi['fname'];
        if (file_exists("img/kide/$fname.jpg")) {
            break;
        }
    }
    return $fname;
}

function any_kide() {
    $yhteys = connect();

    $statement = "SELECT fname FROM kide WHERE fname NOT IN 
        (SELECT kide FROM man_classification WHERE classified_by = '{$_SESSION['valid_user']}')";

    //prepare and execute
    try {
        $kysely = $yhteys->prepare($statement);
    } catch (PDOException $e) {
        pdo_error($e);
    }
    $kysely->setFetchMode(PDO::FETCH_ASSOC);
    $kysely->execute();

    return choose_kide($kysely); //pick the first particle that has an image
}

function array_column($array, $column) {
    foreach ($array as $row)
        $ret[] = $row[$column];
    return $ret;
}

function imagecol($arr,$colname,$prefix,$postfix) {
    $isFirst = true;
    foreach ($arr as $rows => $row) {
        if ($isFirst) {
            $isFirst = false;
            $arr[$rows][] = $colname;
            continue;
        }
        $arr[$rows][] = HTMLparticleimg($prefix . $row['fname'] . $postfix);
    }
    return $arr;
}

function print_readable($var) {
    echo '<pre>', print_r($var, true), '</pre>';
}

?>
