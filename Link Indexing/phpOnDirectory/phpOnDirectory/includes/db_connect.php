<?php
if (is_dir('install')) {
    header("Location: http://".$_SERVER['HTTP_HOST']
                      .dirname($_SERVER['PHP_SELF'])
                      ."/install/index.php");

}
?>
