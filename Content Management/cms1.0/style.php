<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
$choice2 = $_REQUEST["choice2"];
switch($choice2)
{

case 'insertstyle' : include("insertstyle.php");break;
case 'settings' : include("stylesettings.php");break;

}
?>
