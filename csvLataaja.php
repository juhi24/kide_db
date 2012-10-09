<?php
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="output.txt"');
    
    readfile('output.csv')
?>
