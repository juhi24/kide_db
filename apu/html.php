<?php

//HTML measurement site selector boxes
function HTMLsite($remember_selection) {
    $sitearr = getSites();
    $siteboxes = '';
    foreach ($sitearr as $site) {
        if ($site[0] === 'oth') {
            continue;
        }
        $siteboxes .= "$site[0]<input name='site[]' value='$site[0]' type='checkbox' ";
        if ($remember_selection) {
            $siteboxes .= $_SESSION["selected_$site[0]"];
        }
        $siteboxes .= '>&nbsp;&nbsp;&nbsp;';
    }
    $siteboxes .= "Other<input name='site[]' value='oth' type='checkbox' ";
    if ($remember_selection) {
        $siteboxes .= $_SESSION["selected_oth"];
    }
    $siteboxes .= '>';
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

//HTML for particle image
function HTMLparticleimg($id) {
    return "<img alt='$id' src='img/kide/$id.jpg'>";
}

//HTML table from 2d array
function print_simple_table($array2d) {
    echo '<table>';
    foreach ($array2d as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}

function HTMLmessage($msg) {
    return '<p style="background-color: greenyellow; text-align: center;">' . $msg . '</p>';
}

?>
