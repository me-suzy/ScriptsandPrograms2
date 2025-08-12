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
	$workingDir = stripslashes(safePath($workingDir));


	if (!is_dir($workingDir)) {
		$error = "The path you chose doesn't point to an existing folder.<br> Please try a different path (or create the desired folder).";
		$displayForm = true;
	} else if (!is_readable($workingDir)) {
		$error = "The directory you chose it is not readable. Please type another path.";
		$displayForm = true;
	} else if (!is_writeable($workingDir)) {
		$error = "The directory you chose it is not writable.  Please type another path.";
		$displayForm = true;
	} else {

if ($showDirSize == "1") {
	$showDirSize = "true";
} else {
	$showDirSize = "false";
}

$newConfigCont = "<?
\$config[root_dir] = \"".$filemanRootDir."\";
\$config[base_dir] = \"".$workingDir."\";
\$config[showDirSize] = ".$showDirSize.";
?>";


		$fp = @fopen("$filemanRootDir/config.php","w");
		$lenght = strlen($newConfigCont);
		if ($fp) {
			if (!@fputs($fp, $newConfigCont, $lenght)) {
				$error = "Failed to write the configuration. <br> Please check file permissions for file \"./config.php\".";
				$displayForm = true;
			}
		@fclose($fp);
		} else {
			$error = "Failed to open the configuration file. <br> Please check file permissions for file \"./config.php\".";
			$displayForm = true;
		}

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
<br>
<br>
<br>
<?if ($error) {?>
<span><strong><?echo $error?></strong></span>
<br><br>
<form method="post" action="install.php">
<br><br>
<strong>Working folder :</strong> 
<input type="text" name="workingDir" value="<?echo $workingDir?>" size="50">
<br><br><br>
<div align="center"><input type="submit" value="  Install  " name="submit" class="button"></div>
</form>
<?} else {?>
<strong>The AFIAN file manager was successfuly set up.</strong>
<br>
<br>
Click <a href="../index.php">here</a> to load it.
<?}?>
</div>

</body>
</html>