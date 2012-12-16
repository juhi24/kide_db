<?php
require_once 'apu.php';
varmista_kirjautuminen();

$id = $_GET["id"]; //Luokiteltavan kiteen id.

//Jos kidettä ei saada GETillä, valitaan se kidevalitsimella. Tyhjä id implikoi ettei haun mukaista kidettä löydy.
if (!isset($id)) {
    require_once 'kidevalitsin.php';
} elseif ($id=="") {
    die("No such particle was found to be classified. Please modify your particle filter options.");
}
?>
<!DOCTYPE html>
<html><head>

        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Manual classification</title>

        <script src="js/jquery.js"></script>
        <script src="js/hidden_fieldset.js"></script>

    </head><body>

        <?php require_once 'apu/header.html'; ?>

        <h2>Manual classification</h2>

        <br>

        <form method="post" action="manual_class.php" name="manual_classification">
            <div style="text-align: center;"><img id="particle_img" alt="" src="img/kide/<?php echo $id ?>.jpg" hspace="5" vspace="5"><br>
                <br>
            </div>
            <br>

            <fieldset><legend>Select dataset</legend>
                <div style="display: none">
                    Site(s):&nbsp;&nbsp;&nbsp; 
                    <?php
                    foreach ($sitearr as $site) {
                        echo "$site<input name=\"site[]\" value=\"$site\" type=\"checkbox\" {$_SESSION["selected_{$site}"]}>&nbsp;&nbsp;&nbsp;";
                    }
                    ?> 
                    Other<input name="site[]" value="other" type="checkbox"><br>
                    Time frame: From <input type="datetime-local" name="date_start" value="2000-01-01T00:00:00.000"> 
                    to <input type="datetime-local" name="date_end" value="2013-01-01T00:00:00.000"> <br>
                </div>
            </fieldset>
            <fieldset><legend>Particle properties</legend>
                <div style="display: none">
                    Maximum diameter between <input maxlength="4" size="5" name="size_min" value="0">um and <input maxlength="4" size="5" name="size_max" value="9999">um<br>
                    Area ratio between <input maxlength="4" size="5" name="ar_min" value="0"> and <input maxlength="4" size="5" name="ar_max" value="1"><br>
                    Aspect ratio between <input maxlength="4" size="5" name="asprat_min" value="1"> and <input maxlength="4" size="5" name="asprat_max" value="9999"><br>
                </div>
            </fieldset>
            <fieldset><legend>IC-PCA classification</legend>
                <div style="display: none">
                    Classified as <select name="autoclass">
                        <option value="any" <?php $_SESSION["selected_any"] ?>>any class</option>
                        <?php
                        foreach ($classarr as $class) {
                            echo "<option value='$class[0]' {$_SESSION["selected_{$class[0]}"]}>{$class[1]}</option>";
                        }
                        ?>
                    </select> by method <select name="method">
                        <option value="c5nn" selected>5NN (default)</option>
                    </select>
                </div>
            </fieldset>
            <fieldset><legend>Your classification</legend>
                Particle habit:<br>
                <?php
                foreach ($classarr as $class) {
                    echo "<label for='$class[0]'><input name='class_primary' id='$class[0]' value='$class[0]' type='radio'>$class[1]</label>&nbsp;&nbsp;";
                }
                ?>
                <br>
                <br>
                Alternative habit (optional):<br>
                <?php 
                foreach ($classarr as $class) {
                    $lowclass = strtolower($class[0]);
                    echo "<label for='$lowclass'><input name='class_alt' id='$lowclass' value='$class[0]' type='radio'>$class[1]</label>&nbsp;&nbsp; ";
                }
                ?>
                <label for="empty"><input checked="checked" id="empty" name="class_alt" value="" type="radio">(empty)</label>
            </fieldset>
            <br>
            <label for="quality"><input id="quality" type="checkbox" name="low_quality"> Mark image as low quality </label>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <br>
            <br>
            <input name="submit" type="submit" value="Classify"><input name="reset" type="reset"><br>
        </form>

    </body></html>