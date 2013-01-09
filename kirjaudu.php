<?php
require_once 'apu.php';

if (isset($_GET['sisaan'])) {
    $user=$_POST['user'];
    $pass=$_POST['pass'];
    
    if (!$user || !$pass) {
        die("You need to provide a username and a password.");
    }
    $kayttaja = $kyselija->tunnista($user, $pass);
    if ($kayttaja) {
        $_SESSION["valid_user"]=$kayttaja->username;
        reset_defaults();
    } else {
        die("Login incorrect!");
        //ohjaa('login.html');
    }
} elseif (isset($_GET['ulos'])) {
    session_unset();
    session_destroy();
} else {
    die('Illegal action!');
}

ohjaa('index.php');
?>
