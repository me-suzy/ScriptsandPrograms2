<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
echo("<p><a href='options.php'>Back</a></p>");
phpinfo();
?>
	   
