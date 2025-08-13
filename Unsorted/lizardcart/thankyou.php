<?
session_start();
include ("header.php");
include ("config.inc.php");

session_destroy();
echo "".session_id()."";
?>
<br><br>
<h2 align="center">Thank you for your order!</h2>

<? include ("footer.php");?>