<html><head><title>Punktgewinn</title></head><body>
<?php

require('./prepend.inc.php');

if($userid && $sid)
        $points=autogamble_click();

if($points){
        ?>
Your account was credited <b><?php echo $points; ?></b> points.
        <?php
}else{
        ?>
Your account could not be credited.
        <?php
}

?>
</body></html>