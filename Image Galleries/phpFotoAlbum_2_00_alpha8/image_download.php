<?php
session_start();
$filename=$_SESSION["s_data"]["file"];
$file="./_images".$_SESSION["s_data"]["dir"].$_SESSION["s_data"]["file"];
$fp = fopen($file, "rb");
$buffer = fread($fp, filesize($file));
Header("Content-type: application/octet-stream\nContent-Disposition: attachment; filename=".$filename);
echo $buffer;
?>
