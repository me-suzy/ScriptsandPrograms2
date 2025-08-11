<?php
session_start();
if($_POST["passcode"])
{
	if($_POST["passcode"] == "123")
	{
	$passcode = $_POST["passcode"];
	$_SESSION["passcode"] = $passcode;
	echo "<META HTTP-EQUIV = 'Refresh' Content = '0; URL=options.php'>";
//	include_once("top-header.php");
//	include("int-options.php");
	}

	else
	{
	include_once("top-header.php");
	include("relogin.html");
	}
}

else
{
include("admin.html");
}
//include_once("footer.php");
?>