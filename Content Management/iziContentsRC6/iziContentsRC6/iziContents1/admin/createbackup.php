<?php

/***************************************************************************

 createbackup.php
 -----------------
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
$GLOBALS["specialedit"] = True;

$BackupFileTypes = array('sql', 'gz');

includeLanguageFiles('admin','backup');


if (!isset($_POST["subdir"])) {
	$_GET["subdir"] = $_POST["subdir"];
}
if (isset($_GET["subdir"])) {
	$_GET["subdir"] = str_replace($GLOBALS["rootdp"], '', $_GET["subdir"]);
} else {
	$_GET["subdir"] = '';
}

if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	if (bCheckForm()) {
		$rval = CreateBackup();
		Header("Location: ".BuildLink('m_backup.php')."&subdir=".$_POST["subdir"].'&returncode='.$rval);
	}
} else {
	frmBackupForm();
}

function frmBackupForm()
{
	global $_GET;

	adminformheader();
	adminformopen('filename');
	adminformtitle(2,$GLOBALS["tFormTitle4"]);
	echo $GLOBALS["strErrors"];
	?>
	<tr class="tablecontent">
		<?php FieldHeading("Filename","filename"); ?>
		<td valign="top" class="content">
			<input type="text" name="filename" size="50" value="" maxlength="50">
		</td>
	</tr>
	<?php
	if (function_exists('gzopen')) {
		?>
		<tr class="tablecontent">
			<?php FieldHeading("UseGzip","use_compression"); ?>
			<td valign="top" class="content">
				<select name="use_compression" size="1">
					<option value="1" ><?php echo $GLOBALS["tGzipCompression"]; ?>
					<option value="0" selected><?php echo $GLOBALS["tGzipNoCompression"]; ?>
				</select>
			</td>
		</tr>
	<?php
	}

	adminformsavebar(2,'m_backup.php');
	adminhelpmsg(2);
	?><input type="hidden" name="subdir" value="<?php echo $_GET["subdir"]; ?>"><?php
	adminformclose();
} // function frmBackupForm()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	$strMessage = "<tr bgcolor=#900000><td colspan=\"2\"><b>";
	if ($_POST["filename"] == "") {
		$strMessage .= $GLOBALS["eFilenameEmpty"].'<br />';
		$bFormOK = false;
	}
	$strMessage .= "</b></td></tr>";
	if (!$bFormOK) { $GLOBALS["strErrors"] = $strMessage; }
	return $bFormOK;
} // function bCheckForm()





function get_tabledef($tablename)
{
	$tabledef = "";
	$tabledef .= "DROP TABLE IF EXISTS ".$tablename.";".chr(10);
	$tabledef .= "#".chr(10);
	$tabledef .= dbTableDef($tablename);

	return (stripslashes($tabledef));
} // function get_tabledef()


function get_content($tablename)
{
	$content = "";
	$sqlQuery = "SELECT * FROM ".$tablename;
	$result = dbExecute($sqlQuery,true);
	while ($row = dbFetch($result)) {
		$insert = "INSERT INTO ".$tablename." VALUES(";
		$fields = $GLOBALS["dbConn"]->MetaColumnNames($tablename);
		for ($j=0; $j<$result->FieldCount();$j++) {
			$fldname = $fields[$j];
			if ($row[$fldname] != "") { $row[$fldname] = addslashes($row[$fldname]); }
			if ($row[$fldname] != "") { $insert .= "'".$row[$fldname]."',";
			} else { $insert .= "'',"; }
		}
		$insert = ereg_replace(",$","",$insert);
		$insert .= ");".chr(10);
		$content .= $insert;
	}
	dbFreeResult($result);
	return $content;
} // function get_content()


function CreateBackup()
{
	global $_POST;

	set_time_limit(300);

	$rval = -2;

	$path = $GLOBALS["backup_home"].$_POST["subdir"];
	$filename = $_POST["filename"];
	$compression = $_POST["use_compression"];
	if ($compression == 1) { $filetype = "sql.gz";
	} else { $filetype = "sql"; }
//	flush();

	$comment = "#";

	$displaydate = sprintf("%s, %s %02d %04d, at %02d:%02d:%02d %s", strftime("%A"), strftime("%B"), strftime("%d"), strftime("%Y"), strftime("%I"), strftime("%M"), strftime("%S"), strftime("%p"));
	$newfile = "# Dump created with ezContents Backup ".$GLOBALS["Version"]." on ".$displaydate.chr(10).chr(10);

	$newfile .= $comment." --------------------------------------------------------".chr(10);
	$newfile .= $comment." ezContents ".$GLOBALS["ezContentsDB"]." backup".chr(10);
	$newfile .= $comment." ".$GLOBALS["Version"].chr(10);
	$newfile .= $comment.chr(10);
	$newfile .= $comment." Site            : ".$GLOBALS["gsSitetitle"].chr(10);
	$newfile .= $comment." Generation Time : ".$displaydate.chr(10);
	$newfile .= $comment." MySQL version   : ".mysql_get_server_info().chr(10);
	$newfile .= $comment." PHP Version     : ".phpversion().chr(10);
	$newfile .= $comment." Database        : '".$GLOBALS["ezContentsDBName"]."'".chr(10);
	$newfile .= $comment.chr(10).chr(10);

	$tables = $GLOBALS["dbConn"]->MetaTables();
	$num_tables = count($tables);
	$i = 0;
	while ($i < $num_tables) {
		$tablename = $tables[$i];

		$backitup = False;
		if ($GLOBALS["eztbPrefix"] != '') {
			if (substr($tablename,0,strlen($GLOBALS["eztbPrefix"])) == $GLOBALS["eztbPrefix"]) { $backitup = True; }
		} else { $backitup = True; }
		if ($backitup) {
			$newfile .= $comment." ----------------------------------------------------------".chr(10);
			$newfile .= $comment.chr(10);
			$newfile .= $comment." Table structure for table '".$tablename."'".chr(10);
			$newfile .= $comment.chr(10);
			$newfile .= get_tabledef($tablename);
			$newfile .= chr(10).chr(10);
			//  We don't dump data from the sessions table for security reasons
			if ($tablename == $GLOBALS["eztbSessions"]) {
				$newfile .= $comment.chr(10);
			} else {
				$newfile .= $comment.chr(10);
				$newfile .= $comment." Dumping data for table '".$tablename."'".chr(10);
				$newfile .= $comment.chr(10);
				$newfile .= get_content($tablename);
				$newfile .= chr(10).chr(10);
			}
		}
		$i++;
	}

	if ($compression == 1) {
		$fp = gzopen($GLOBALS["rootdp"].$path.$filename.".".$filetype,"wb9");
		if ($fp) {
			if (gzwrite($fp,$newfile) != -1) { $rval = 2; }
			gzclose($fp);
		}
	} else {
		$fp = fopen ($GLOBALS["rootdp"].$path.$filename.".".$filetype,"w");
		if ($fp) {
			if (fwrite($fp,$newfile) != -1) { $rval = 2; }
			fclose($fp);
		}
	}
	return $rval;
} // function CreateBackup()

include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
