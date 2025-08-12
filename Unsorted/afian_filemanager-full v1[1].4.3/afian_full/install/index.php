<?
/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/


require_once("../config.php");
require_once("../functions/functions.php");
require_once("../functions/compatibility.php");

	$filemanRootDir = safePath(realpath(getcwd()."/.."));

if (!eregi("win", strtolower(php_uname()))) {
	@superChmod($filemanRootDir."/config.php", "777");
	@superChmod($filemanRootDir."/clipboard", "777");
	@superChmod($filemanRootDir."/trash", "777");
	@superChmod($filemanRootDir."/trashundo.txt", "777");
	@superChmod($filemanRootDir."/class/pclzip", "777");
}

	if (isCfgOn("safe_mode")) {
	$tests["test"][] = "PHP Safe Mode";
	$tests["result"][] = "ON";
	$tests["color"][] = "red";
	$tests["note"][] = "A filemanager can't work properly with PHP in safe mode. Please contact your hosting company.";
	$fatal = true;
	}




	$sysfiles = getDirList("..", array());
	$tests["test"][] = "File permissions";
	for ($i = 0 ; $i < sizeof($sysfiles) ; $i++) {
		if (!is_readable($sysfiles[$i]))	{
			$failed = true;
			$failedfile = $sysfiles[$i];
			break;
		}
		//echo $i . " " . $sysfiles[$i] . "<br>";
	}
	if ($failed) {
		$tests["result"][] = "FAILED";
		$tests["color"][] = "red";
		$tests["note"][] = $failedfile . " it is not readable. Chmod it 777 and then try again.";
		$fatal = true;
	} else if (!is_writeable("../config.php")) {
		$tests["result"][] = "FAILED";
		$tests["color"][] = "red";
		$tests["note"][] = "File \"/config.php\" is not writeable. Chmod it 777 and then try again.";
		$fatal = true;
	} else if (!is_dir("../clipboard") || !is_writeable("../clipboard")) {
		$tests["result"][] = "FAILED";
		$tests["color"][] = "red";
		$tests["note"][] = "Folder \"/clipboard\" is not writeable. Chmod it 777. The installation may continue, but you won't be able to cut/copy files or folders.";
	} else if (!is_dir("../trash") || !is_writeable("../trash")) {
		$tests["result"][] = "FAILED";
		$tests["color"][] = "red";
		$tests["note"][] = "Folder \"/trash\" is not writeable. Chmod it 777. The installation can continue but you will cannot delete files or folders, or archive them.";
	} else if (!is_writeable("../trashundo.txt")) {
		$tests["result"][] = "FAILED";
		$tests["color"][] = "red";
		$tests["note"][] = "File \"/trashundo.txt\" is not writeable. Chmod it 777. The installation can continue but the the trash is not available.";
	} else {
		$tests["result"][] = "OK";
		$tests["color"][] = "green";
		$tests["note"][] = "&nbsp;";
	}


// Test that file uploads are allowed
	$tests["test"][] = "File uploads";
if (isCfgOn("file_uploads")) {
	$tests["result"][] = "OK";
	$tests["color"][] = "green";
	$tests["note"][] = "&nbsp;";
} else {
	$tests["result"][] = "FAILED";
	$tests["color"][] = "red";
	$tests["note"][] = "File uploads disabled. Check your PHP configuration.";
}



// Test that GD is loaded
	$tests["test"][] = "PHP GD Library";
if (extension_loaded("gd")) {
	$tests["result"][] = "OK";
	$tests["color"][] = "green";
	$tests["note"][] = "Thumbnail view available for JPEG and PNG files.";
} else {
	$tests["result"][] = "FAILED";
	$tests["color"][] = "red";
	$tests["note"][] = "Thumbnail view unavailable. GD extension is not loaded. Check your PHP configuration.";
}



	$tests["test"][] = "Your browser";
	$tests["result"][] = getenv("HTTP_USER_AGENT");
	$tests["color"][] = "green";
	if (ereg("msie", strtolower(getenv("HTTP_USER_AGENT")))) {
		$tests["note"][] = "Afian should work properly with your browser. If it doesn't, then please get an updated version of your browser.";
	} else if (ereg("opera", strtolower(getenv("HTTP_USER_AGENT")))) {
		$tests["note"][] = "Afian should work properly with your browser. If it doesn't, then please get an updated version of your browser.";
	} else if (ereg("safari", strtolower(getenv("HTTP_USER_AGENT")))) {
		$tests["note"][] = "Afian should work properly with your browser. If it doesn't, then please get an updated version of your browser.";
	} else if (ereg("konqueror", strtolower(getenv("HTTP_USER_AGENT")))) {
		$tests["note"][] = "Afian should work properly with your browser. If it doesn't, then please get an updated version of your browser.";
	} else if (ereg("netscape", strtolower(getenv("HTTP_USER_AGENT")))) {
		$tests["note"][] = "The good news: Afian works on Netscape/Mozilla. The bad news: Due to poor support of CSS technology in Netscape/Mozilla, Afian may look weird.";
	} else {
		$tests["note"][] = "Afian was not tested on this browser. Please let us know how it works with this browser.";
	}




	$tests["test"][] = "Operating system";
if (eregi("win", php_uname())) {
	$tests["result"][] = php_uname();
	$tests["color"][] = "green";
	$tests["note"][] = "UNIX-style permissions unavailable on Windows.";
} else {
	$tests["result"][] = php_uname();
	$tests["color"][] = "green";
	$tests["note"][] = "&nbsp;";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Afian file manager - Installation</title>
	<link rel="stylesheet" type="text/css" rev="stylesheet" href="../css/style.css">
</head>

<body style="background-image:url(../images/interface/bg-page.gif)">

<div align="center">
<br>
<div style="width:520px;text-align:left;">
<img src="../images/interface/logo-install.gif" border="0" alt="">
</div>
<br><br>
<table class="popup" style="width:530px;" cellpadding="3" cellspacing="0" border="0">
<tr>
	<td><strong>System test</strong></td>
	<td><strong>Result</strong></td>
	<td><strong>Note</strong></td>
</tr>
<tr>
	<td colspan="3" style="padding:1px;padding-top:0px;"><hr style="height:1px;color:gray;" size="1"></td>
</tr>
<?
for ($i = 0 ; $i < sizeof($tests[test]) ; $i++) {
?>
<tr>
	<td valign="top" nowrap><?echo $tests[test][$i]?></td>
	<td valign="top" style="color:<?echo $tests[color][$i]?>"><?echo $tests[result][$i]?></td>
	<td valign="top"><?echo $tests[note][$i]?></td>
</tr>
<?}?>
<tr>
	<td colspan="3" style="padding:1px;"><hr style="height:1px;color:gray;" size="1"></td>
</tr>
</table>
<div style="width:520px;text-align:left;margin-top:10px;">
<?if (!$fatal) {?>
<form method="post" action="install.php">
<strong>Welcome to the Afian File Manager!</strong>
<br>
<br>
To install it, type the path to the folder that you want to manage and then click the button.<br><br>
<strong>NOTE</strong>: You will see this page only once. If later you want to change the working folder, you can find this page by accessing  <a>http://<?echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])?></a>.
<br><br>
<strong>Working folder :</strong> 
<input type="text" name="workingDir" value="<?if(!$config[base_dir]){echo safePath(realpath(getcwd()."/.."));}else{echo $config[base_dir];}?>" size="50">
<br>
<br>
<strong>Display folder's size in tooltips :</strong>
<input type="checkbox" name="showDirSize" value="1"  <?if($config[showDirSize]){echo "checked";}?>> 
<span class="comment">(Uncheck to improve performance)</span>
<br><br>
<div align="center"><input type="submit" value="  Install  " name="submit" class="button"></div>
</form>
<?} else {?>
Sorry, I have encountered a problem. Installation halted.
<?}?>
</div>
</div>
</body>
</html>
