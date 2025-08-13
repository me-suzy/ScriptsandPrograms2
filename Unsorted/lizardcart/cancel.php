<?
session_start();
include ("header.php");
include ("config.inc.php");

session_destroy();
echo "".session_id()."";
?>

<br><br>
<h2 align="center">Something went wrong with your order your order!</h2>
<p>Contact <? echo "$paypalemail";?> for some help.</p>

<? include ("footer.php");?>