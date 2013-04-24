<?php

session_start();

require_once 'apu/kyselyt.php';
require_once 'apu/pdo.php';
require_once 'apu/html.php';
require_once 'apu/sql.php';
require_once 'apu/connection.php';

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
    if (!on_kirjautunut()) {
        ohjaa('login.html');
    }
}

//pick the first query result particle that has an image
function choose_kide(PDOStatement $kysely) {
    while ($rivi = $kysely->fetch()) {
        $id = $rivi['id'];
        if (file_exists("img/kide/$id.jpg")) {
            break;
        }
    }
    return $id;
}

function array_column($array, $column) {
    foreach ($array as $row) $ret[] = $row[$column];
    return $ret;
}

?>
