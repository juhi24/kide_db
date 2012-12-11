<?php
require_once 'apu.php';
varmista_kirjautuminen();

$id = $_GET["id"];
if (!isset($id))
    require_once 'kidevalitsin.php';
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
                        echo "$site<input name=\"site[]\" value=\"$site\" type=\"checkbox\">&nbsp;&nbsp;&nbsp;";
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
            <fieldset><legend>Particle classification</legend>
                Particle habit:<br>
                <label for="B"><input name="class_primary" id="B" value="B" type="radio">bullet</label>&nbsp;&nbsp;
                <label for="P"><input name="class_primary" id="P" value="P" type="radio">plate</label>&nbsp;&nbsp; 
                <label for="C"><input name="class_primary" id="C" value="C" type="radio">column</label>&nbsp;&nbsp; 
                <label for="R"><input name="class_primary" id="R" value="R" type="radio">rosette</label>&nbsp;&nbsp; 
                <label for="PA"><input name="class_primary" id="PA" value="PA" type="radio">plate agg.</label>&nbsp; 
                <label for="CA"><input name="class_primary" id="CA" value="CA" type="radio">column agg.</label>&nbsp; 
                <label for="RA"><input name="class_primary" id="RA" value="RA" type="radio">rosette agg.</label>&nbsp; 
                <label for="I"><input name="class_primary" id="I" value="I" type="radio">irregular</label><br>
                <br>
                Alternative habit (optional):<br>
                <label for="b"><input name="class_alt" id="b" value="B" type="radio">bullet</label>&nbsp;&nbsp; 
                <label for="p"><input name="class_alt" id="p" value="P" type="radio">plate</label>&nbsp;&nbsp; 
                <label for="c"><input name="class_alt" id="c" value="C" type="radio">column</label>&nbsp;&nbsp; 
                <label for="r"><input name="class_alt" id="r" value="R" type="radio">rosette</label>&nbsp;&nbsp; 
                <label for="pa"><input name="class_alt" id="pa" value="PA" type="radio">plate agg.</label>&nbsp; 
                <label for="ca"><input name="class_alt" id="ca" value="CA" type="radio">column agg.</label>&nbsp; 
                <label for="ra"><input name="class_alt" id="ra" value="RA" type="radio">rosette agg.</label>&nbsp; 
                <label for="i"><input name="class_alt" id="i" value="I" type="radio">irregular</label>&nbsp;&nbsp; 
                <label for="empty"><input checked="checked" id="empty" name="class_alt" value="" type="radio">(empty)</label></fieldset><br>
            <label for="quality"><input id="quality" type="checkbox" name="low_quality"> Mark image as low quality </label>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <br>
            <br>
            <input name="submit" type="submit" value="Classify"><input name="reset" type="reset"><br>
        </form>

    </body></html>