<?php

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/
 
// get uri of file and redirect to index
if (basename($PHP_SELF) == 'index.php.complete') {
	Header("Location: ../index.php");
}

error_reporting( E_ERROR | E_PARSE );	//	Disable error reporting for anything but critical errors
set_time_limit(300);					//	Allow up to five minutes for the time-consuming upgrade to run....
										//		this should be more than enough for almost any site.

$GLOBALS["DebugMode"] = False;
$GLOBALS["rootdp"] = '../';		//	Database access routines
include ('../include/debuglib.php');
include ('../include/db.php');
include ('./help.php');
include ('./languages.php');

// ############	Execute the appropriate page script ###########################
if ($_POST["mode"] <> "") { $_GET["mode"] = $_POST["mode"]; }
if($_POST["DBLogUpdates"]){$GLOBALS["Log"] = $_POST["DBLogUpdates"];}

if ($_GET["mode"] == 'language2') {
	$languagecount = test_languages_1();
	if ($languagecount == 0) {
		$languageerror = 'You must install at least one language';
		$_GET["mode"] = 'languages';
	} else {
		$languagecount = test_languages_2($_POST["defaultlanguage"]);
		if ($languagecount == 0) {
			$languageerror = 'The default language must be one of those you have selected for installation';
			$_GET["mode"] = 'languages';
		}
	}
}

// if using manual installation, rename all installation files and jump to writeconfig
if($_POST["manualinstall"]){
	$_GET["mode"] = 'writeconfig';
	rename_file('database.php', 'database.php.complete');
	rename_file('install.php', 'install.php.complete');
	rename_file('languages.php', 'languages.php.complete');
	rename_file('modules.php', 'modules.php.complete');
}
// #############    start installation     ####################################
switch ($_GET["mode"]) {
	case 'install_update'	 : install_header("Installation",360,120);
				   include("install.php");
				   if ($_POST["InstallType"] == 'newinstall'){
				   install_iziContents();
				   help_screen(360,72,$GLOBALS["Help"]["NewInstall"].$GLOBALS["Help"]["SecurityComments"]);
				   } elseif($_POST["InstallType"] == 'update') {
				   update_iziContents();
				   help_screen(360,72,$GLOBALS["Help"]["UpgradeInstall"].$GLOBALS["Help"]["SecurityComments"]); }
				   break;
	case 'database'	 : install_header("Installation",460,370);
					include_once("database.php");	
					database_screen();
					help_screen(360,72,$GLOBALS["Help"]["Database"]);
					   break;
	case 'database2' : install_header("Installation",460,370);
					include_once("database.php");
					database2_screen();
					help_screen(360,72,$GLOBALS["Help"]["Database"]);
					   break;
	case 'modules'	 : install_header("Installation",420,120);
						include_once("modules.php");
					   module_screen();
					   help_screen(400,62,$GLOBALS["Help"]["Modules"]);
					   break; 
	case 'modules2'	 : install_header("Installation",420,120);
						include_once("modules.php");
					   $Status = create_modules($_POST["DBPrefix"],&$Status);
					   break;
	case 'languages' : install_header("Installation",420,120);
					   include_once("languages.php");
					   language_screen();
					   help_screen(400,62,$GLOBALS["Help"]["Languages"]);
					   break;
	case 'languages2' : install_header("Installation",420,120);
					   include_once("languages.php");
					   $Status = create_languages($_POST["DBPrefix"],&$Status);
					   break;				   
	case 'test'		 : install_header("Installation",780,500);
					include_once("test.php");
					   test_screen();
					   help_screen(780,92,$GLOBALS["Help"]["Test"]);
					   break;
	case 'agreement' : install_header("License",600,330);
					   agreement_screen();
					   break;
	case 'writeconfig': install_header("Config", 420, 120);
						include_once("writeinstallation.php");
						config_screen();
						help_screen(400,62,$GLOBALS["Help"]["Config"]);
						break;
	default			 : install_header("Installation",420,200);
					   opening_screen();
}
install_footer($title);

######################## Layout functions   ############################## 

function rename_file($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
     if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
     }
     return FALSE;
   }
   return TRUE;
}

function install_header($title,$width,$height)
{
	?>
	<html>
	<head>
		<title><?php echo $GLOBALS["Titles"][$title]; ?></title>
		<style>
			.ip_text
			{
				border:solid 1px;
				font-family:verdana;
				font-size:8pt;
				letter-spacing:-0.5pt
			}
			.help_text
			{
				border:none;
				border-color:white;
				font-family:verdana;
				font-size:8pt;
				letter-spacing:-0.5pt;
				background-color:darkslateblue;
				color:yellow;
				overflow:hidden;
				text-decoration:None
			}
		</style>
	</head>
	<body text="black" bgcolor="slategray" link="white" vlink="white" alink="white">
	<br />

	<center>
	<table border="1" bgcolor="darkslateblue" cellspacing="0" cellpadding="0" height="<?php echo $height; ?>" width="<?php echo $width; ?>">
		<tr><td align="center" valign="top">
	<?php
} // function install_header(

function install_footer()
{
	?>
			</td>
		</tr>
	</table>
	</center>

	</body>
	</html>
	<?php
} // function install_footer()

function blocktitle($title)
{
	?>
	<p><font face="Times New Roman" color="paleturquoise" size="+2"><b><?php echo $GLOBALS["Titles"][$title] ?></b></font></p>
	<?php
} // function blocktitle(


function blocktext($text,$colour='white')
{
	?>
	<p><font face="Times New Roman" color="<?php echo $colour; ?>" size="-1"><?php echo $text; ?></font></p>
	<?php
} // function blocktext()

function basetext($text,$colour='white')
{
	?>
	<font face="Times New Roman" color="<?php echo $colour; ?>" size="-1"><?php echo $text; ?></font>
	<?php
} // function blocktext()

function mouseover($text,$helptext,$returntext)
{
	$ref = '<a href="#" style="text-decoration:none" onMouseOver="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"][$helptext])).'\';" onMouseOut="window.document.helpform.helptext.value=\''.str_replace('\'','\\\'',str_replace(chr(10),'\n\r',$GLOBALS["Help"][$returntext])).'\';">';
	return $ref.$text.'</a>';
} // function mouseover()


function help_screen($width,$colwidth,$text)
{
	?>
			</td>
		</tr>
		<tr><td valign="center" align="center">
	<form name="helpform" enctype="multipart/form-data">
	<table border="0" bgcolor="darkslateblue" cellspacing="0" cellpadding="0" height="50" width="<?php echo $width; ?>">
		<tr><td align="center" valign="top">
				<textarea cols="<?php echo $colwidth; ?>" rows="7" id="helptext" name="helptext" wrap='soft' class="help_text" readonly>
<?php echo $text; ?>
				</textarea>
			</td>
		</tr>
	</table>
	</form>
	<?php
} // function help_screen()

function install_message($colour,$message)
{
	$image = $GLOBALS["Checks"][$colour];
	?>
			</td>
		</tr>
		<tr>
			<td align="center" valign="middle">
				<?php
				blocktext($image.$message);
} // function install_message()

function test_languages_1()
{
	global $_POST;

	$languagecount = 0;
	$savedir = getcwd();
	chdir('../languages');
	if ($handle = @opendir('.')) {
		while ($filename = readdir($handle)) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.') && !($filename == 'CVS'))) {
				if ($_POST[$filename] == 'Y') { $languagecount++; }
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	return $languagecount;
} // function test_languages_1()


function test_languages_2($language)
{
	global $_POST;

	$languagecount = 0;
	$savedir = getcwd();
	chdir('../languages');
	if ($handle = @opendir('.')) {
		while ($filename = readdir($handle)) {
			if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.') && !($filename == 'CVS'))) {
				if ($_POST[$filename] == 'Y') {
					if ($filename == $language) { $languagecount++; }
				}
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	return $languagecount;
} // function test_languages_2()


#####################  Installation  Screens ########################

function opening_screen()
{
	?>
	<table border="0" height="100%" width="100%" cellpadding="4" cellspacing="2">
		<tr><td colspan="2" align="center" valign="middle">
				<?php blocktitle("Main"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="bottom">
				<img src="../images/izilogo.jpg">
			</td>
			<td valign="top">
				<?php blocktext('Thank you for choosing to install the iziContents Content Management System.'); ?>
				<?php blocktext('This script will only take a few moment to run, and will configure a new installation.'); ?>
			</td>
		</tr>
		<tr><td align="center" valign="top">
				<img src="../images/logo_small.gif">
			</td>
			<td align="center" valign="middle">
				<input type="button" class="ip_text" value="Install iziContents" onClick="location.href='index.php?mode=agreement'">
			</td>
		</tr>
	</table>
	<?php
} // function opening_screen()	

function agreement_screen()
{
	?>
	<table border="0" height="100%" width="100%" cellpadding="4" cellspacing="2">
		<tr><td align="center" valign="middle">
				<?php blocktitle("License"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<?php blocktext('Please read the agreement below, and if you agree to it select the button at the very bottom.<br />By selecting the button, you agree to the terms below.'); ?>
				<p>
				<textarea cols="70" rows="15" name="agreement" wrap='soft' style="font-family: Courier; font-size: 10pt" readonly>
<?php include("./iziContents_License.txt"); ?>
				</textarea>
				</p>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<input type="button" class="ip_text" value="I Agree To These Terms" onClick="location.href='index.php?mode=test'">
			</td>
		</tr>
	</table>
	<?php
} // function agreement_screen()

#################### additional functions ####################################

function readPerms($in_Perms)
{
	$sP = '';

	if ($in_Perms & 0x1000) { $sP = 'p'; }	// FIFO pipe
	elseif ($in_Perms & 0x2000) { $sP = 'c'; }	// Character special
	elseif ($in_Perms & 0x4000) { $sP = 'd'; }	// Directory
	elseif ($in_Perms & 0x6000) { $sP = 'b'; }	// Block special
	elseif ($in_Perms & 0x8000) { $sP = '-'; }	// Regular
	elseif ($in_Perms & 0xA000) { $sP = 'l'; }	// Symbolic Link
	elseif ($in_Perms & 0xC000) { $sP = 's'; }	// Socket
	else { $sP = 'u'; }				// UNKNOWN

	// owner
	$sP .= (($in_Perms & 0x0100) ? 'r' : '-').(($in_Perms & 0x0080) ? 'w' : '-').(($in_Perms & 0x0040) ? (($in_Perms & 0x0800) ? 's' : 'x' ) : (($in_Perms & 0x0800) ? 'S' : '-'));
	// group
	$sP .= (($in_Perms & 0x0020) ? 'r' : '-').(($in_Perms & 0x0010) ? 'w' : '-').(($in_Perms & 0x0008) ? (($in_Perms & 0x0400) ? 's' : 'x' ) : (($in_Perms & 0x0400) ? 'S' : '-'));
	// world
	$sP .= (($in_Perms & 0x0004) ? 'r' : '-').(($in_Perms & 0x0002) ? 'w' : '-').(($in_Perms & 0x0001) ? (($in_Perms & 0x0200) ? 't' : 'x' ) : (($in_Perms & 0x0200) ? 'T' : '-'));
	return $sP;
} // function readPerms()


function fileReadWrite($myuserid,$mygroupid,$fileref)
{
	$rval = false;

	$userid = fileowner($fileref);
	$groupid = filegroup($fileref);
	$perms = readPerms(fileperms($fileref));

	if ($GLOBALS["DebugMode"]) {
		debug_msg('<font color="LIGHTGREEN" SIZE="-1">File Name = ['.$fileref.']<br />',$GLOBALS["Titles"]["InstallLog"]);
		debug_msg('File Owner ID = ['.$userid.'] ',$GLOBALS["Titles"]["InstallLog"]);
		debug_msg('File Group ID = ['.$groupid.']<br />',$GLOBALS["Titles"]["InstallLog"]);
		debug_msg('File Permissions = ['.$perms.']<br />',$GLOBALS["Titles"]["InstallLog"]);
	}

	if ($myuserid == $userid) {
		if (substr($perms,2,1) == 'w') {
			$rval = true;
			if ($GLOBALS["DebugMode"]) { debug_msg('*** Owner match ***<br />',$GLOBALS["Titles"]["InstallLog"]); }
		}
		elseif ($mygroupid == $groupid) {
			if (substr($perms,5,1) == 'w') {
				$rval = true;
				if ($GLOBALS["DebugMode"]) { debug_msg('*** Group match ***<br />',$GLOBALS["Titles"]["InstallLog"]); }
			} elseif (substr($perms,8,1) == 'w') {
				$rval = true;
				if ($GLOBALS["DebugMode"]) { debug_msg('*** World match ***<br />',$GLOBALS["Titles"]["InstallLog"]); }
			}
		}
	} elseif ($mygroupid == $groupid) {
		if (substr($perms,5,1) == 'w') {
			$rval = true;
			if ($GLOBALS["DebugMode"]) { debug_msg('*** Group match ***<br />',$GLOBALS["Titles"]["InstallLog"]); }
		}
	} elseif (substr($perms,8,1) == 'w') {
		$rval = true;
		if ($GLOBALS["DebugMode"]) { debug_msg('*** World match ***<br />',$GLOBALS["Titles"]["InstallLog"]); }
	}

	if ($GLOBALS["DebugMode"]) { 
		if (!($rval)) {
			debug_msg('*** NO MATCH ***<br />',$GLOBALS["Titles"]["InstallLog"]);
		}
		debug_msg('<br /></font>',$GLOBALS["Titles"]["InstallLog"]);
	}

	return $rval;
} // function fileReadWrite()
?>	