<?php

require_once 'apu.php';

$downloadsdir = 'downloads';
$bins_dir = $downloadsdir . DIRECTORY_SEPARATOR . 'sizebins';

if (isset($_POST['single-bin'])) {
    $kysely = flux_query($_POST['size_min'],$_POST['size_max']);
    
    //save query results to a csv-file
    $csvfilename = 'output.csv';
    $csvfilepath = $downloadsdir . DIRECTORY_SEPARATOR . $csvfilename;
    results2csv($kysely, $csvfilepath);
    
    // Start csv download
    header('Content-type: text/plain');
    header("Content-Disposition: attachment; filename='$csvfilepath'");
    readfile($csvfilepath);
} elseif (isset($_POST['multibin'])) {
    $sizebins = preg_split('/\r\n|\ŗ|\n/', $_POST['sizebins']);
    foreach ($sizebins as $irow => $binrow) {
        $sizebins[$irow] = preg_split('/[\s,]+/', trim($binrow));
    }
    $col_count = max(array_map('count', $sizebins));
    if ($col_count != 3) {
        die('ERROR: Unsupported size bins format. There seems to be ' . $col_count. ' columns.');
    }
    foreach ($sizebins as $binrow) {
        $q = flux_query($binrow[0], $binrow[2]);
        $csvfilename = str_replace('.', '_', $binrow[1]) . '.csv';
        $csvfilepath = $bins_dir . DIRECTORY_SEPARATOR . $csvfilename;
        results2csv($q, $csvfilepath);
    }

    // ZIP magic
    $zipname = 'output_bins.zip';
    $zip_path = $downloadsdir . DIRECTORY_SEPARATOR . $zipname;
    $bins_zip = new ZipArchive();
    if ($bins_zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
        $csvfiles = glob($bins_dir . DIRECTORY_SEPARATOR . '*');
        foreach ($csvfiles as $csvfile) {
            $bins_zip->addFile($csvfile, basename($csvfile));
        }
        $bins_zip->close();
        foreach ($csvfiles as $csvfile) {
            if (is_file($csvfile)) {
                unlink($csvfile);
            }
        }
    } else {
        die('ERROR: Could not create zip-archive.');
    }
    
    header('Content-type: application/octet-stream');
    header('Content-disposition: attachment; filename=' . $zipname);
    readfile($zip_path);
}

function results2csv($q, $csvfilepath) {
    $hfile = fopen($csvfilepath, 'w');
    $otsikot = array();
    while ($rivi = $q->fetch()) {
        if (empty($otsikot)) {
            $otsikot = array_keys($rivi);
            fputcsv($hfile, $otsikot);
        }
        fputcsv($hfile, $rivi);
    }
    fclose($hfile);
}

function flux_query($sizemin,$sizemax) {
    //connection
    $yhteys = connect();

    $pca_method = 'c5nn';
    $classarr = getHabits();

    $sitesql = saittifiltteri($_POST['site']);
    $qualitysql = '';
    $filtersql = '';

    //if qualityfilter is checked
    if (!empty($_POST['quality'])) {
        $qualitysql = 'AND fname NOT IN (SELECT kide FROM man_classification WHERE quality IS NOT NULL OR quality=FALSE)';
    }
    if (!empty($_POST['filters'])) {
        $filtersql = 'AND fname NOT IN (SELECT kide FROM flags WHERE flag IS NOT NULL)';
    }

    //SQL to count particles by habit
    $count = '';
    foreach ($classarr as $class) {
        $count .= SQLparticle_count('pca_class', $class[0]) . " AS $class[0], ";
    }

    $statement = "SELECT
        round_{$_POST['timeunit']}(time, :reso ) AS interval,
        COUNT(fname) AS tot,
        $count
        AVG(dmax)::real AS dmax_mean,
        (sum(ar*area(ar,dmax))/sum(area(ar,dmax)))::real AS ar_weighted_mean
    FROM kide LEFT JOIN PCA_classification ON (kide.fname = PCA_classification.kide)
    WHERE PCA_classification.pca_method='$pca_method'
    AND dmax BETWEEN :sizemin AND :sizemax
    AND time BETWEEN :datestart AND :dateend
    AND ar BETWEEN :armin AND :armax
    AND asprat BETWEEN :aspratmin AND :aspratmax
    AND site $sitesql
    $qualitysql
    $filtersql
    GROUP BY interval
    ORDER BY interval";

    //prepare and execute query
    try {
        $kysely = $yhteys->prepare($statement, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $kysely->setFetchMode(PDO::FETCH_ASSOC);
        $kysely->execute(array(':reso' => $_POST['resolution'], ':sizemin' => $sizemin, ':sizemax' => $sizemax,
            ':datestart' => $_POST['date_start'], ':dateend' => $_POST['date_end'], 'armin' => $_POST['ar_min'],
            ':armax' => $_POST['ar_max'], ':aspratmin' => $_POST['asprat_min'], ':aspratmax' => $_POST['asprat_max']));
    } catch (PDOException $e) {
        pdo_error($e);
    }
    return $kysely;
}

?>