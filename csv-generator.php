<?php
require_once 'apu.php';
varmista_kirjautuminen();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>CSV Generator</title>
    </head>
    <body>

        <?php require_once 'apu/header.html'; ?>

        <h2>CSV-generator</h2>
        <br>
        <form method="post" action="csvgen.php" name="csv-generator"><br>
            <fieldset><legend>Select dataset</legend>
                Site(s):&nbsp;&nbsp;&nbsp; 
                <?php
                foreach ($sitearr as $site) {
                    echo "$site<input name=\"site[]\" value=\"$site\" type=\"checkbox\">&nbsp;&nbsp;&nbsp;";
                }
                ?> 
                Other<input name="site[]" value="other" type="checkbox"><br>
                Time frame: From <input type="datetime-local" name="date_start" value="2000-01-01T00:00:00.000"> 
                to <input type="datetime-local" name="date_end" value="2013-01-01T00:00:00.000"> <br>
            </fieldset>
            <br>
            Time resolution: 
            <select name="resolution">
                <option selected="selected">1</option>
                <option>2</option>
                <option>3</option>
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>20</option>
                <option>30</option>
            </select>
            <select name="timeunit">
                <option>seconds</option>
                <option selected="selected">minutes</option>
            </select>
            <br>
            <br>
            <fieldset><legend>Particle properties</legend>
                Maximum diameter between <input maxlength="4" size="5" name="size_min" value="0">um and <input maxlength="4" size="5" name="size_max" value="9999">um<br>
                Area ratio between <input maxlength="4" size="5" name="ar_min" value="0"> and <input maxlength="4" size="5" name="ar_max" value="1"><br>
                Aspect ratio between <input maxlength="4" size="5" name="asprat_min" value="1"> and <input maxlength="4" size="5" name="asprat_max" value="9999"><br>
            </fieldset>

            <br>
            <input checked="checked" name="quality" type="checkbox"> Filter out data flagged as low quality<br>
            <br>
            <input name="Submit" type="submit"> <input name="Reset" type="reset"><br>
        </form>

    </body>
</html>