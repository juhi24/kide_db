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
                echo 'Other<input name="site[]" value="other" type="checkbox"><br>';
                echo "Time frame: From <input type='datetime-local' name='date_start' value='{$default["datestart"]}'>";
                echo "to <input type='datetime-local' name='date_end' value='{$default["dateend"]}'> <br>";
                ?>
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
                <?php
                echo HTMLdmax($default['sizemin'], $default['sizemax']);
                echo HTMLar($default['armin'], $default['armax']);
                echo HTMLasprat($default['aspratmin'], $default['aspratmax']);
                ?>
            </fieldset>

            <br>
            <input checked="checked" name="quality" type="checkbox"> Filter out data flagged as low quality<br>
            <br>
            <input name="Submit" type="submit"> <input name="Reset" type="reset"><br>
        </form>

    </body>
</html>