<?php
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="output.csv"');
    
    readfile('output.csv')
?>
