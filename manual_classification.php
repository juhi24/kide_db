<?php require_once 'manual_class.php'; ?>
<!DOCTYPE html>
<html><head>

        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <title>Manual classification</title>


    </head><body>
        Manual classification<br>

        <br>

        <form method="post" action="manual_class.php" name="manual_classification">
            <div style="text-align: center;"><img id="particle_img" alt="" src="img/<?php echo $id ?>.jpg" height="16" hspace="5" vspace="5" width="16"><br>
                <br>
            </div>
            <br>
            Particle habit:<br>
            <input name="class_primary" value="b" type="radio">bullet&nbsp;&nbsp;
            <input name="class_primary" value="p" type="radio">plate&nbsp;&nbsp; 
            <input name="class_primary" value="c" type="radio">column&nbsp;&nbsp; 
            <input name="class_primary" value="r" type="radio">rosette&nbsp;&nbsp; 
            <input name="class_primary" value="pa" type="radio">plate agg.&nbsp; 
            <input name="class_primary" value="ca" type="radio">column agg.&nbsp; 
            <input name="class_primary" value="ra" type="radio">rosette agg.<br>
            <br>
            Alternative habit (optional):<br>
            <input name="class_alt" value="b" type="radio">bullet&nbsp;&nbsp; 
            <input name="class_alt" value="p" type="radio">plate&nbsp;&nbsp; 
            <input name="class_alt" value="c" type="radio">column&nbsp;&nbsp; 
            <input name="class_alt" value="r" type="radio">rosette&nbsp;&nbsp; 
            <input name="class_alt" value="pa" type="radio">plate agg.&nbsp; 
            <input name="class_alt" value="ca" type="radio">column agg.&nbsp; 
            <input name="class_alt" value="ra" type="radio">rosette agg.&nbsp;&nbsp;&nbsp; 
            <input checked="checked" name="class_alt" value="empty" type="radio">(empty)<br>
            <br>
            <br>
            <input name="submit" type="submit"><input name="reset" type="reset"><br>
        </form>

    </body></html>