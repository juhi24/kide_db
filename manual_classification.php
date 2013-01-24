<?php
require_once 'apu.php';
varmista_kirjautuminen();

$id = $_GET["id"]; //Luokiteltavan kiteen id.
//Jos kidettä ei saada GETillä, valitaan se kidevalitsimella. Tyhjä id implikoi ettei haun mukaista kidettä löydy.
if (!isset($id)) {
    require_once 'kidevalitsin.php';
} elseif ($id == "") {
    die("No such particle was found to be classified. Please modify your particle filter options.");
}
?>
<!DOCTYPE html>
<html>
    <head>

        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>Manual classification</title>

        <link rel="stylesheet" href="css/slimbox2.css" media="screen" />
        <script src="js/jquery.js"></script>
        <script src="js/hidden_fieldset.js"></script>
        <script src="js/slimbox2.js"></script>

    </head><body>

        <?php require_once 'apu/header.html'; ?>

        <h2>Manual classification</h2>
        <a href="img/classification_reference.png" rel="lightbox" title="1) plate, 2)bullet, 3) column, 4) irregular, 5) rosette aggregate, 6) rosette, 7) plate aggregates, 8) column aggregate">
            classification reference
        </a>

        <br>

        <form method="post" action="manual_class.php" name="manual_classification">
            <div style="text-align: center;"><img id="particle_img" alt="" src="img/kide/<?php echo $id ?>.jpg" hspace="5" vspace="5"><br>
                <br>
            </div>
            <br>

            <fieldset><legend>Select dataset (click to expand/hide)</legend>
                <div style="display: none">
                    Site(s):&nbsp;&nbsp;&nbsp; 
                    <?php
                    echo HTMLsite(TRUE);
                    echo "Time frame: From <input type='datetime-local' name='date_start' value='{$_SESSION["man_datestart"]}'>";
                    echo "to <input type='datetime-local' name='date_end' value='{$_SESSION["man_dateend"]}'> <br>";
                    ?>
                </div>
            </fieldset>
            <fieldset><legend>Particle properties (click to expand/hide)</legend>
                <div style="display: none">
                    <?php
                    echo HTMLdmax($_SESSION['man_sizemin'],$_SESSION['man_sizemax']);
                    echo HTMLar($_SESSION["man_armin"], $_SESSION["man_armax"]);
                    echo HTMLasprat($_SESSION["man_aspratmin"], $_SESSION["man_aspratmax"]);
                    ?>
                </div>
            </fieldset>
            <fieldset><legend>IC-PCA classification (click to expand/hide)</legend>
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
                //generate radio buttons for each habit
                foreach ($classarr as $class) {
                    echo "<label for='$class[0]'><input type='radio' name='class_primary' id='$class[0]' value='$class[0]' accesskey='$class[0]'>$class[1]</label>&nbsp;&nbsp;";
                }
                ?>
                <br>
                <br>
                Alternative habit (optional):<br>
                <?php
                //generate radio buttons for secondary habits
                foreach ($classarr as $class) {
                    $lowclass = strtolower($class[0]);
                    echo "<label for='$lowclass'><input type='radio' name='class_alt' id='$lowclass' value='$class[0]'>$class[1]</label>&nbsp;&nbsp; ";
                }
                ?>
                <label for="empty"><input checked="checked" id="empty" name="class_alt" value="" type="radio">(empty)</label>
            </fieldset>
            <br>
            <label for="quality"><input id="quality" type="checkbox" name="low_quality"> Mark image as low quality </label>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <br>
            <br>
            <input type="submit" name="classify" value="Classify">
            <input name="reset" type="reset" value="Reset to latest values">
            <input type="submit" name="defaults" value="Reset to defaults"><br>
        </form><br>

        <p style="background-color: greenyellow; text-align: center;">
            <?php
            if (isset($_GET["success"])) {
                echo 'Particle successfully classified!';
            }
            ?>
        </p>

    </body>
</html>