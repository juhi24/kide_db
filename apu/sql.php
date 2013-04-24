<?php
//generate SQL to filter sites
function saittifiltteri($sites) {
    $sitesql = '';
    if (empty($sites) || $sites[0] === 'other') {
        $sitesql .= 'IS NULL'; //if none or only "other" selected
    } else {
        $sitesql.="='$sites[0]'";
        $N = count($sites);
        for ($i = 1; $i < $N; $i++) {
            $sitesql.=" OR site='$sites[$i]'";
            if ($sites[$i] === 'other') {
                $sitesql.=' OR site IS NULL'; //NULL counts as "other"
            }
        }
    }
    return $sitesql;
}

//SQL to count matched particles by class
function SQLmatch_count($method, $class) {
    return "COUNT(NULLIF(($method=class1 OR $method=class2) AND $method='$class',FALSE))";
}

//SQL to count particles by class
function SQLparticle_count($ref, $class) {
    return "COUNT(NULLIF($ref='$class',FALSE))";
}


?>
