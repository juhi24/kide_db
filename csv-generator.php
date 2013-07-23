<?php
require_once 'apu.php';
login_check();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>CSV Generator</title>
    </head>
    <body>

        <?php require_once 'header.html'; ?>

        <h2>CSV-summary generator</h2>
        <p>Generate particle distribution summaries in an easy-to-use format.</p>
        <form method="post" action="csvgen.php" name="csv-generator"><br>
            <fieldset><legend>Select dataset</legend>
                <p>Site(s):&nbsp;&nbsp;&nbsp; 
                <?php
                echo HTMLsite(FALSE) . '</p>';
                echo '<p>' . HTMLdate($default["datestart"],$default["dateend"]) . '</p>';
                ?>
            </fieldset>
            <fieldset><legend>Particle properties</legend>
                <?php
                echo HTMLdmax($default['sizemin'], $default['sizemax']);
                echo HTMLar($default['armin'], $default['armax']);
                echo HTMLasprat($default['aspratmin'], $default['aspratmax']);
                ?>
            </fieldset>
            <p>Time resolution: 
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
            </select></p>
            <p><input checked="checked" name="quality" type="checkbox"> Exclude particles manually marked as low quality</p>
            <p><input type="checkbox" name="filters" checked="checked"> Exclude automatically filtered particles</p>
            <p><input type="submit" name="single-bin" value="Generate output"> <input type="reset" name="reset"></p>
            <h3>Size bins</h3>
            <textarea name="sizebins" placeholder="Paste size bins here"></textarea>
            <p><input type="submit" name="multibin" value="Generate output archive"></p>
        </form>

    </body>
</html>