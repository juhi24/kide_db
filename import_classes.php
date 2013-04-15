<?php

$allowedExt = 'out';
$ext = end(explode('.', $_FILES['classesfile']['name']));
if (($_FILES['classesfile']['type'] == 'application/octet-stream') && ($ext == $allowedExt)) {
    if ($_FILES['classesfile']['error'] > 0) {
        echo '<p>Error: ' . $_FILES['classesfile']['error'] . '</p>';
    } else {
        echo '<p>Type: ' . $_FILES['classesfile']['type'] . '</p>';
    }
} else {
    echo 'Invalid file';
}
?>
