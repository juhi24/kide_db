<?php
require_once 'apu.php';

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
    'asprat'    => 12,
    'n_corners' => 13,
    'time'      => 14
);

$delimiter = ',';
$allowedExt = 'csv';
$ext = end(explode('.', $_FILES['classesfile']['name']));
$allowedTypes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
if (in_array($_FILES['classesfile']['type'],$allowedTypes) && ($ext == $allowedExt)) {
    if ($_FILES['classesfile']['error'] > 0) {
        die('<p>Error: ' . $_FILES['classesfile']['error'] . '</p>');
    } else {
        if (($handle = fopen($_FILES['classesfile']['tmp_name'], 'r')) !== FALSE) {
            $rows=0;
            while (($classesrow = fgetcsv($handle, 500, $delimiter)) !== FALSE) {
                foreach ($col_index as $ival) {
                    if (empty($classesrow[$col_index[$ival]])) {
                        $classesrow[$col_index[$ival]] = 'NULL';
                    }
                }
                $insert_kide .= '(\'' . $classesrow[$col_index['fname']] . '\',\''
                        . $classesrow[$col_index['time']] . '\',' 
                        . $classesrow[$col_index['dmax']] . ',' 
                        . $classesrow[$col_index['ar']] . ',' 
                        . $classesrow[$col_index['ar_filled']] . ','
                        . $classesrow[$col_index['asprat']] . ','
                        . $classesrow[$col_index['n_corners']] . ','
                        . $classesrow[$col_index['site']] . ','
                        . 'NULL' //filtered
                        . '),';
                $rows++;
            }
            $insert_kide = rtrim($insert_kide,',');
            pdo_query($insert_kide);
            ohjaa('uploader.php?particles=' . $rows);
        }
        
    }
} else {
    die('Invalid file');
}
?>
