<?php

/***************************************************************************

 restorebackup.php
 ------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

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

include_once ("rootdatapath.php");

$GLOBALS["form"] = 'backup';
$GLOBALS["validaccess"] = VerifyAdminLogin();
if ($GLOBALS["canadd"] == False) {
	Header("Location: ".BuildLink('adminlogin.php'));
}


if (!isset($_GET["file"])) { $_GET["file"] = ''; }
$filesplit = explode(".", $_GET["file"]);
$extension = array_pop($filesplit);

if (($extension == 'gz') && (!(function_exists('gzopen')))) {
	include ($GLOBALS["rootdp"]."include/settings.php");
	include ("adminfunctions.php");
	includeLanguageFiles('backup');

	adminheader();
	admintitle(1,$GLOBALS["tFormTitle"]);
	echo '<TR><TD>';
	echo '<TABLE BORDER="1" BORDERCOLOR="BLACK" BGCOLOR="#900000" WIDTH="100%" CELLPADDING="2" CELLSPACING="2"><TR><TD>';
	echo '<TABLE BORDER="0" WIDTH="100%" CELLPADDING="3" CELLSPACING="3">';
	echo '<TR><TD align="'.$GLOBALS["right"].'" VALIGN="TOP"><FONT COLOR="WHITE"><B>'.$GLOBALS["eERROR"].':</B></FONT></TD><TD><FONT COLOR="WHITE"><B>'.$GLOBALS["eNoGzip"].'</B></FONT></TD></TR>';
	echo '</TABLE>';
	echo '</TD></TR></TABLE>';
	echo '</TD></TR></TABLE>';
} else {
	$rval = RestoreBackup($extension);
	Header("Location: ".BuildLink('m_backup.php')."&subdir=".$_POST["subdir"].'&returncode='.$rval);
}




function RestoreBackup($extension)
{
	global $_GET;

	$blocksize = 65536;		//	64k

	$rval = 0;
	if ($_GET["file"] !="") {
		set_time_limit(240);
		if (get_magic_quotes_runtime() == 1) { set_magic_quotes_runtime(0); }

		$filesize = filesize($_GET["file"]);
		if ($filesize > 0) {

			$buffersize = ceil($filesize / $blocksize) * $blocksize;

			if ($extension == 'gz') {
				$fp = gzopen($_GET["file"], "rb");
				$file = gzread($fp, $buffersize);
			} else {
				$fp = fopen($_GET["file"], "r");
				$file = fread($fp, $buffersize);
			}
	
			$file = str_replace("\r", "", $file);
			$query = explode(";\n",$file);
			for ($i=0;$i < count($query)-1;$i++) {
				$sqlQuery = trim($query[$i]);
				$workquery = explode("\n",$sqlQuery);
				for ($j=0; $j < count($workquery) - 1; $j++) {
					$test_workquery = trim($workquery[$j]);
					if (substr($test_workquery,0,1) == '#') { $workquery[$j] = ''; }
				}
				$sqlQuery = implode("",$workquery);
				if ((substr($sqlQuery,0,4) == 'DROP') || (substr($sqlQuery,0,6) == 'CREATE') || (substr($sqlQuery,0,6) == 'INSERT')) {
					$result = dbExecute($sqlQuery,true);
				}
			}

			if ($extension == 'gz') { $fp = gzclose($fp);
			} else { $fp = fclose($fp); }
			dbCommit();

			// A restore will have erased the current session data, so we recreate it here from the values currently held in memory.
			db_session_write();
			dbCommit();
			$rval = 1;
		}
	}
	return $rval;
} // function RestoreBackup();

?>
