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
function SQLmatch_count($class) {
    return "COUNT(NULLIF((pca_class=class1 OR pca_class=class2) AND pca_class='$class',FALSE))";
}

//SQL to count particles by class
function SQLparticle_count($column,$class) {
    return "COUNT(NULLIF($column='$class',FALSE))";
}


?>
