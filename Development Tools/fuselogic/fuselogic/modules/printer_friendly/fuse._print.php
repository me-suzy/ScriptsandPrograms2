<?php 
///if(@$_SESSION['printer_friendly'] != 0){
if(@(int)$_COOKIE['printer_friendly'] == 1){
    require_once('class.printer_friendly.php');
    $printer_friendly = &new printer_friendly();
    echo $printer_friendly->run(getLayout());
}else echo getLayout();

?>