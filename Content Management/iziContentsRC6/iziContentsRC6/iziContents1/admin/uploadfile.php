<?php

/***************************************************************************

 uploadfile.php
 ---------------
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


// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$GLOBALS["chmoduploads"] = True;




function validatefiletypes($script)
{
	$GLOBALS["maxfilesize"] = 4096000;		// 4MB max filesize
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbFiletypes"]." WHERE filecat='".$script."'";
	$result = dbRetrieve($strQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		$GLOBALS["FileTypes"][]	= $rs["filetype"];
		$GLOBALS["MimeTypes"][]	= $rs["mimetype"];
		$GLOBALS["FileIcon"][]	= $rs["fileicon"];
	}
	dbFreeResult($result);
} // function validatefiletypes()


function validatedirectory()
{
	global $_POST, $_GET;

	if (isset($_POST["subdir"])) $_GET["subdir"] = $_POST["subdir"];
	if (isset($_GET["subdir"])) {
		$_GET["subdir"] = str_replace('../', '', $_GET["subdir"]);
	} else {
		$_GET["subdir"] = '';
	}
	if (isset($_GET["subdir"])) $_POST["subdir"] = $_GET["subdir"];
} // function validatedirectory()


function lCountFiles($basedir,$dirtype)
{
	global $_GET;

	$old_locale = setlocale(LC_ALL, 0);
	setlocale (LC_TIME,$GLOBALS["locale"]);

	$nFileCount = 0;
	$savedir = getcwd();
	chdir($GLOBALS["rootdp"].$basedir.$_GET["subdir"]);
	if ($handle = @opendir('.')) {
		while ($file = readdir($handle)) {
			$filename = $file;
			if (is_file($filename)) {
				$fileparts = pathinfo($filename);
				$file_ext = strtolower($fileparts["extension"]);
				if (in_array($file_ext,$GLOBALS["FileTypes"])) {
					$GLOBALS["files"][$nFileCount]["filename"] = $filename;
					$GLOBALS["files"][$nFileCount]["filetype"] = $dirtype;
					$GLOBALS["files"][$nFileCount]["filesize"] = display_size(filesize($filename));
					$ival = array_search($file_ext,$GLOBALS["FileTypes"]);
					$GLOBALS["files"][$nFileCount]["fileicon"] = $GLOBALS["FileIcon"][$ival];
					$GLOBALS["files"][$nFileCount]["filetime"] = strftime('%c',filemtime($filename));
					if ($dirtype == 'image') {
						$size = GetImageSize($filename);
						$GLOBALS["files"][$nFileCount]["filedims"] = $size["0"].' x '.$size["1"];
					}
					$nFileCount++;
				}
			} elseif (is_dir($filename)) {
				if ((!(($filename == '..') && ($_GET["subdir"] == ''))) && ($filename != '.')) {
					$GLOBALS["files"][$nFileCount]["filename"] = $filename;
					$GLOBALS["files"][$nFileCount]["filetype"] = 'dir';
					$nFileCount++;
				}
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	setlocale(LC_ALL, $old_locale);

	return $nFileCount;
} // function lCountDownloads()


function diskUsedSpace($dir) {
	$dh = opendir($dir);
	$size = 0;
	while (($file = readdir($dh)) !== false) {
		if ($file != "." and $file != "..") {
			$path = $dir."/".$file;
			if (is_dir($path)) { $size += diskUsedSpace($path); }
			elseif (is_file($path)) { $size += filesize($path); }
		}
	}
	closedir($dh);
	$usedspace = $size;
	return $usedspace;
}  // function diskUsedSpace()


function frmFreeSpace($colspan,$dir='.')
{
	?>
	<tr class="teaserheadercontent">
		<td colspan="<?php echo $colspan; ?>">
			<table width="100%">
				<tr class="teaserheadercontent">
					<td align="<?php echo $GLOBALS["left"]; ?>">
						<?php echo $GLOBALS["tFreeSpace"].' : '.display_size(diskfreespace('.')); ?>
					</td>
					<td align="<?php echo $GLOBALS["right"]; ?>">
						<?php
						if ($dir != '') {
							//	DO NOT UNCOMMENT THIS NEXT LINE
							// if (substr(phpversion(),0,5) >= '4.1.0') { echo display_size(diskUsedSpace($dir)).'&nbsp;&nbsp;'; }
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function frmFreeSpace()


function frmDisplayDir($colspan,$dir)
{
	global $_GET;

	?>
	<tr class="teaserheadercontent">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<?php echo $dir.$_GET["subdir"]; ?>
		</td>
	</tr>
	<?php
} // function frmDisplayDir()


function validate_upload()
{
	global $_FILES;

	//  Temporary upload file doesn't exist
	if ($_FILES['filename']['tmp_name'] == '') {
		$GLOBALS["errormessage"] = $GLOBALS["eNoFileUpload"];
		return False;
	}
	//  Checks to ensure the file size is > 0 and < 4 megs
	if ($_FILES['filename']['size'] == 0) {
		$GLOBALS["errormessage"] = $GLOBALS["eZeroByteFile"];
		return False;
	}
	if ($_FILES['filename']['size'] > $GLOBALS["maxfilesize"]) {
		$GLOBALS["errormessage"] = $GLOBALS["eInvalidFileSize"];
		return False;
	}

	//  Test file extension
	$fileparts = pathinfo($_FILES['filename']['name']);
	$file_ext = strtolower($fileparts["extension"]);

	//  Test that the file extension of this file is valid for upload
	//			against the list of valid file extensions. If so, we determine
	//			which entry to use for the list of valid MIME types for this file
	$typeref = -1;
	for ($i=0; $i<count($GLOBALS["FileTypes"]); $i=$i+1) {
		if ($file_ext == strtolower($GLOBALS["FileTypes"][$i])) { $typeref = $i; }
	}

	if ($typeref == -1) {
		$GLOBALS["errormessage"] = $GLOBALS["eInvalidFileType"];
		return False;
	}

	//  Test file MIME type of this file against the list of valid MIME types
	$MimeTypes = explode(';',$GLOBALS["MimeTypes"][$typeref]);
	if (!in_array($_FILES['filename']['type'],$MimeTypes)) {
		$GLOBALS["errormessage"] = $_FILES['filename']['type'].' '.$GLOBALS["eInvalidMimeType"];
		return False;
	}
	return True;
} // function validate_upload()


function UploadNewFile($destinationdir)
{
	global $_FILES;

	$savedir = getcwd();
	$path = $GLOBALS["rootdp"].$destinationdir;
	chdir($path);

	$validupload = validate_upload();
	if ($validupload) {
		// Make sure this isn't an attempt to fiddle an upload
		if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
			// check if a file with the same name exists. if so, then delete the old one before upload
			$ffile = $_FILES['filename']['name'];
			if (file_exists("$ffile")) { unlink ("$ffile"); }
			// Copy the file from the temporary upload area
			@copy($_FILES['filename']['tmp_name'], $_FILES['filename']['name']) or die("Could not upload file");
			// Ensure that the file can be managed by both ezContents and ftp/telnet access to the webserver machine
			if ($GLOBALS["chmoduploads"]) {
				chmod($_FILES['filename']['name'],0666); 
			}
		}
		// Delete the file from the temporary upload area
		unlink($_FILES['filename']['tmp_name']);
	}

	chdir($savedir);
	return $validupload;
}

?>
