<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Administration</title>
<?php
$choice = $_REQUEST["choice"];
switch($choice)
{
case '' : include("top-header.php"); include("options.html");break;
case 'insert' :include("top-header.php"); include("insert.php");break;
case 'header' :include("top-header.php"); include("header.php");break;
case 'view' :include("top-header.php"); include("view.php");break;
case 'stylesheet' :include("top-header.php"); include("styleoptions.php");break;
case 'insfooter' :include("top-header.php"); include("insfooter.php");break;
}
include("footer.php");
?>
