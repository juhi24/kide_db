<?php
//initialize or reset session values in forms
function reset_defaults() {
    global $default, $classarr;
    $_SESSION['sizemin'] = $default['sizemin'];
    $_SESSION['sizemax'] = $default['sizemax'];
    $_SESSION['armin'] = $default['armin'];
    $_SESSION['armax'] = $default['armax'];
    $_SESSION['aspratmin'] = $default['aspratmin'];
    $_SESSION['aspratmax'] = $default['aspratmax'];
    $_SESSION['datestart'] = $default['datestart'];
    $_SESSION['dateend'] = $default['dateend'];
    unset($_SESSION['sites']);
    unset($_SESSION['autoclass']);
    //$_SESSION['method'] = $default['method'];

    clear_selection($classarr, 'any'); //clear class selection
    $_SESSION['selected_any'] = 'selected'; //select default value
}

//clear "selected" elements
function clear_selection($optionsarr, $extra_option) {
    $_SESSION["selected_$extra_option"] = '';
    foreach ($optionsarr as $option) {
        $_SESSION["selected_$option"] = '';
    }
}

//check if user is logged in
function on_kirjautunut() {
    return isset($_SESSION['valid_user']);
}


?>
