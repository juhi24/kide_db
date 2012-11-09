<?php require_once 'kidevalitsin.php'; ?>
<!DOCTYPE html>
<html><head>

        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <title>Manual classification</title>


    </head><body>
        Manual classification<br>

        <br>

        <form method="post" action="manual_class.php" name="manual_classification">
            <div style="text-align: center;"><img id="particle_img" alt="" src="img/kide/<?php echo $id ?>.jpg" hspace="5" vspace="5"><br>
                <br>
            </div>
            <br>
            <fieldset><legend>Particle classification</legend>
            Particle habit:<br>
            <input name="class_primary" value="B" type="radio">bullet&nbsp;&nbsp;
            <input name="class_primary" value="P" type="radio">plate&nbsp;&nbsp; 
            <input name="class_primary" value="C" type="radio">column&nbsp;&nbsp; 
            <input name="class_primary" value="R" type="radio">rosette&nbsp;&nbsp; 
            <input name="class_primary" value="PA" type="radio">plate agg.&nbsp; 
            <input name="class_primary" value="CA" type="radio">column agg.&nbsp; 
            <input name="class_primary" value="RA" type="radio">rosette agg.<br>
            <br>
            Alternative habit (optional):<br>
            <input name="class_alt" value="B" type="radio">bullet&nbsp;&nbsp; 
            <input name="class_alt" value="P" type="radio">plate&nbsp;&nbsp; 
            <input name="class_alt" value="C" type="radio">column&nbsp;&nbsp; 
            <input name="class_alt" value="R" type="radio">rosette&nbsp;&nbsp; 
            <input name="class_alt" value="PA" type="radio">plate agg.&nbsp; 
            <input name="class_alt" value="CA" type="radio">column agg.&nbsp; 
            <input name="class_alt" value="RA" type="radio">rosette agg.&nbsp;&nbsp;&nbsp; 
            <input checked="checked" name="class_alt" value="" type="radio">(empty)</fieldset><br>
            <br>
            <input type="checkbox" name="low_quality"> Mark image as low quality
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <br>
            <br>
            <input name="submit" type="submit" value="Classify"><input name="reset" type="reset"><br>
        </form>

    </body></html>