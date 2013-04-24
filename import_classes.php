<?php

$insert_kide = 'INSERT INTO kide VALUES ';
$insert_class = 'INSERT INTO PCA_classification VALUES ';

$col_index = array(
    'fname' => 1,
    'nn'    => 2,
    '3nn'   => 3,
    '5nn'   => 4,
    'nw3nn' => 6,
    'nw5nn' => 7,
    'bayes' => 8,
    'dmax'  => 9,
    'ar'    => 10,
    'ar_filled' => 11,
    'asprat' => 12,
    'n_corners' => 13
);

$allowedExt = 'out';
$ext = end(explode('.', $_FILES['classesfile']['name']));
if (($_FILES['classesfile']['type'] == 'application/octet-stream') && ($ext == $allowedExt)) {
    if ($_FILES['classesfile']['error'] > 0) {
        echo '<p>Error: ' . $_FILES['classesfile']['error'] . '</p>';
    } else {
        if (($handle = fopen($_FILES['classesfile']['tmp_name'], 'r')) !== FALSE) {
            while (($classesrow = fgetcsv($handle, 500, ' ')) !== FALSE) {
                $insert_kide .= '(' . $classesrow[$col_index['fname']] . ','
                        . $classesrow[$col_index['time']] . ',' 
                        . $classesrow[$col_index['dmax']] . ',' 
                        . $classesrow[$col_index['ar']] . ',' 
                        . $classesrow[$col_index['ar_filled']] . ','
                        . $classesrow[$col_index['asprat']] . ','
                        . $classesrow[$col_index['n_corners']] . ','
                        . $classesrow[$col_index['site']] . ','
                      //  . $classesrow[$col_index['filtered']] . ','
                        . '), ';
            }
        }
        
    }
} else {
    echo 'Invalid file';
}
?>
