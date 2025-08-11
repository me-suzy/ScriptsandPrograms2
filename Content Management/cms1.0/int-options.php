<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
switch($choice)
{
case '' : include("options.html");break;
case 'insert' : include("top-header.php");  include("insert.php");break;
case 'header' :include("top-header.php"); include("header.php");break;
case 'view' :include("top-header.php"); include("view.php");break;
case 'stylesheet' :include("top-header.php"); include("styleoptions.php");break;
case 'insfooter' :include("top-header.php"); include("insfooter.php");break;
}
include("footer.php");
?>