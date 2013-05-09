<?php
require_once 'apu.php';
login_check();
//$yhteys = yhdista();

$showresults = true;
if (isset($_GET['results'])) {
    $dmax = "ROUND(dmax::numeric,{$_POST['dec_dmax']})";
    $ar = "ROUND(ar::numeric,{$_POST['dec_ar']})";
    $asprat = "ROUND(asprat::numeric,{$_POST['dec_asprat']})";
    $attribs = "$dmax, $ar, $asprat, n_corners";

    $sql = "SELECT $dmax AS size, $ar AS arearatio, $asprat AS aspratio, n_corners, COUNT(*) AS particle_count
    FROM kide
    GROUP BY $attribs
    HAVING (COUNT($dmax)>1 AND COUNT(n_corners)>1 AND COUNT($ar)>1 AND COUNT($asprat)>1)
    ORDER BY $dmax";

    $query = pdo_query($sql);

    $duplicates = fetchAll_with_headers($query);

    if (count($duplicates) > 50) {
        $showresults = false;
        $message = 'Found too many results. Please change the search conditions.';
    } elseif (empty($duplicates)) {
        $showresults = false;
        $message = 'No results.';
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <title>Duplicate finder</title>
    </head>
    <body>
        <?php require_once 'header.html'; ?>
        <h2>Find duplicates</h2>
        <h3>Settings</h3>
        <form method="post" action="duplicatefinder.php?results" name="findersettings">
            <h4>Number of decimals</h4>
            <ul>
                <li>maximum diameter:
                    <select name="dec_dmax">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select> decimals</li>
                <li>area ratio:
                    <select name="dec_ar">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                    </select> decimals</li>
                <li>aspect ratio:
                    <select name="dec_asprat">
                        <option value="0">0</option>
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                    </select> decimals</li>
            </ul>
            <input type="submit" value="Search duplicates">
        </form>

        <?php
        if (isset($_GET['results']) && $showresults) {
            echo '<h3>Results overview</h3>';

            print_simple_table($duplicates);
            echo '<h3>Possible duplicates</h3>';
            foreach (array_slice($duplicates, 1) as $dupgroups => $dupgroup) {
                $selectid = "SELECT time, fname FROM kide 
                WHERE $dmax = {$dupgroup['size']} AND $ar = {$dupgroup['arearatio']} AND $asprat = {$dupgroup['aspratio']}
                ORDER BY time";

                $duplicateids = fetchAll_with_headers(pdo_query($selectid));

                $isFirst = true;
                foreach ($duplicateids as $rows => $row) {
                    if ($isFirst) {
                        $isFirst = false;
                        $duplicateids[$rows][] = "image";
                        continue;
                    }
                    $duplicateids[$rows][] = HTMLparticleimg($row['id']);
                }
                print_simple_table($duplicateids);
            }
        } elseif (!$showresults) {
            echo "<h3>Results overview</h3><p>$message</p>";
        }
        ?>
    </body>
</html>
