<?php

/***************************************************************************

 filefunctions.php
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


// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$GLOBALS["chmoduploads"]	= True;

if (strpos(php_uname(), 'Win') !== FALSE) { $GLOBALS["OS"] = "Windows"; }




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


function lGetFileOwner($filename)
{
	if ($GLOBALS["OS"] != "Windows") {
		$owner = fileowner($filename);
		if (function_exists('posix_getpwuid')) {
			$userinfo = posix_getpwuid($owner);
			$owner = $userinfo["name"];
		}
	} else { $owner = ''; }
	return $owner;
} // function lGetFileOwner()


function lGetFileGroup($filename)
{
	if ($GLOBALS["OS"] != "Windows") {
		$group = filegroup($filename);
		if (function_exists('posix_getgrgid')) {
			$userinfo = posix_getgrgid($group);
			$group = $userinfo["name"];
		}
	} else { $group = ''; }
	return $group;
} // function lGetFileGroup()


function lGetFilePerms($filename)
{
	$sP;

	$perms = fileperms($filename);
	if ($perms & 0x1000) { $sP = 'p'; }	// FIFO pipe
	elseif ($perms & 0x2000) { $sP = 'c'; }	// Character special
	elseif ($perms & 0x4000) { $sP = 'd'; }	// Directory
	elseif ($perms & 0x6000) { $sP = 'b'; }	// Block special
	elseif ($perms & 0x8000) { $sP = '-'; }	// Regular
	elseif ($perms & 0xA000) { $sP = 'l'; }	// Symbolic Link
	elseif ($perms & 0xC000) { $sP = 's'; }	// Socket
	else { $sP = 'u'; }				// UNKNOWN

	// owner
	$sP .= (($perms & 0x0100) ? 'r' : '-').(($perms & 0x0080) ? 'w' : '-').(($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
	// group
	$sP .= (($perms & 0x0020) ? 'r' : '-').(($perms & 0x0010) ? 'w' : '-').(($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
	// world
	$sP .= (($perms & 0x0004) ? 'r' : '-').(($perms & 0x0002) ? 'w' : '-').(($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
	return $sP;
} // function lGetFilePerms()


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
					if ($GLOBALS["ShowFilePermissions"] == 'Y') {
						$GLOBALS["files"][$nFileCount]["fileowner"] = lGetFileOwner($filename);
						$GLOBALS["files"][$nFileCount]["filegroup"] = lGetFileGroup($filename);
						$GLOBALS["files"][$nFileCount]["fileperms"] = lGetFilePerms($filename);
					}
					$nFileCount++;
				}
			} elseif (is_dir($filename)) {
				if ((!(($filename == '..') && ($_GET["subdir"] == ''))) && ($filename != '.')) {
					$GLOBALS["files"][$nFileCount]["filename"] = $filename;
					$GLOBALS["files"][$nFileCount]["filetype"] = 'dir';
					if ($GLOBALS["ShowFilePermissions"] == 'Y') {
						$GLOBALS["files"][$nFileCount]["fileowner"] = lGetFileOwner($filename);
						$GLOBALS["files"][$nFileCount]["filegroup"] = lGetFileGroup($filename);
						$GLOBALS["files"][$nFileCount]["fileperms"] = lGetFilePerms($filename);
					}
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
	$MimeTypes = explode(';',trim($GLOBALS["MimeTypes"][$typeref]));
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
			//	Default upload method is http:
			if (($GLOBALS["uploadmethod"] == 'http:') || ($GLOBALS["uploadmethod"] == '')) {
				// check if a file with the same name exists. if so, then delete the old one before upload
				$ffile = $_FILES['filename']['name'];
				if (file_exists("$ffile")) { unlink ("$ffile"); }
				// Copy the file from the temporary upload area
				@copy($_FILES['filename']['tmp_name'], $_FILES['filename']['name']) or die("Could not upload file");
				// Try to ensure that the file can be managed by both ezContents and ftp/telnet access to the webserver machine
				if ($GLOBALS["chmoduploads"]) {
					if ($GLOBALS["OS"] == "Windows") { chmod($_FILES['filename']['name'],666); 
					} else { chmod($_FILES['filename']['name'],0666); }
				}
			} elseif ($GLOBALS["uploadmethod"] == 'ftp:') {
				if (function_exists('ftp_connect')) {
					// set up basic ftp connection
					$ftpConnection = @ftp_connect($GLOBALS["ftp"]["server"],$GLOBALS["ftp"]["port"]);
					if (!$ftpConnection) {
						$GLOBALS["errormessage"] = "FTP connection to ".$GLOBALS["ftp"]["server"]." has failed!";
						$validupload = False;
					} else {
						// login with username and password
						$login_result = @ftp_login($ftpConnection, $GLOBALS["ftp"]["username"], $GLOBALS["ftp"]["password"]);
						// check connection
						if (!$login_result) {
							$GLOBALS["errormessage"] = "FTP connection has failed for user ".$GLOBALS["ftp"]["username"];
							$validupload = False;
						} else {
							ftp_pasv($ftpConnection,TRUE);
							if (@ftp_chdir($ftpConnection, $GLOBALS["ftp"]["ezContents_root"])) {
								if (@ftp_chdir($ftpConnection, $destinationdir)) {
									// upload the file
									$upload = @ftp_put($ftpConnection, $_FILES['filename']['name'], $_FILES['filename']['tmp_name'], FTP_BINARY);
									// check upload status
									if (!$upload) {
										$GLOBALS["errormessage"] = "FTP upload has failed for file ".$_FILES['filename']['name'];
										$validupload = False;
									}
								} else {
									$GLOBALS["errormessage"] = "Failed changing directory to ".$destinationdir;
									$validupload = False;
								}
							} else {
								$GLOBALS["errormessage"] = "Failed changing directory to ezContents root";
								$validupload = False;
							}
						}
						// close the FTP stream
						ftp_close($ftpConnection);
					}
				} else {
					$GLOBALS["errormessage"] = 'FTP is unavailable';
					$validupload = False;
				}
			} else {
				$GLOBALS["errormessage"] = 'Invalid upload method: '.$GLOBALS["uploadmethod"];
				$validupload = False;
			}
		}
		// Delete the file from the temporary upload area
		unlink($_FILES['filename']['tmp_name']);
	}

	chdir($savedir);
	return $validupload;
} // function UploadNewFile()


function CreateNewDir($home,$dirname)
{
	$savedir = getcwd();
	$path = $GLOBALS["rootdp"].$home;
	chdir($path);

	$validcreate = True;
	// check if the subdirectory already exists
	$ffile = $dirname;
	if (file_exists("$ffile")) {
		$validcreate = False;
		$GLOBALS["errormessage"] = '/'.$dirname.' '.$GLOBALS["eDirAlreadyExists"];
	}

	if ($validcreate) {
		//	Default upload method is http:
		if (($GLOBALS["uploadmethod"] == 'http:') || ($GLOBALS["uploadmethod"] == '')) {
                        $oldumask = umask(0);
						$validcreate = @mkdir($dirname, 0755);
                        umask($oldumask);
		} elseif ($GLOBALS["uploadmethod"] == 'ftp:') {
			if (function_exists('ftp_connect')) {
				// set up basic ftp connection
				$ftpConnection = @ftp_connect($GLOBALS["ftp"]["server"],$GLOBALS["ftp"]["port"]);
				if (!$ftpConnection) {
					$GLOBALS["errormessage"] = "FTP connection to ".$GLOBALS["ftp"]["server"]." has failed!";
					$validcreate = False;
				} else {
					// login with username and password
					$login_result = @ftp_login($ftpConnection, $GLOBALS["ftp"]["username"], $GLOBALS["ftp"]["password"]);
					// check connection
					if (!$login_result) {
						$GLOBALS["errormessage"] = "FTP connection has failed for user ".$GLOBALS["ftp"]["username"];
						$validcreate = False;
					} else {
						ftp_pasv($ftpConnection,TRUE);
						if (@ftp_chdir($ftpConnection, $GLOBALS["ftp"]["ezContents_root"])) {
							if (@ftp_chdir($ftpConnection, $home)) {
								$createdir = @ftp_mkdir($ftpConnection, $dirname);
								// check upload status
								if (!$createdir) {
									$GLOBALS["errormessage"] = "FTP upload has failed for directory ".$_FILES['filename']['name'];
									$validcreate = False;
								}
							} else {
								$GLOBALS["errormessage"] = "Failed changing directory to ".$home;
								$validcreate = False;
							}
						} else {
							$GLOBALS["errormessage"] = "Failed changing directory to ezContents root";
							$validcreate = False;
						}
					}
					// close the FTP stream
					ftp_close($ftpConnection);
				}
			} else {
				$GLOBALS["errormessage"] = 'FTP is unavailable';
				$validcreate = False;
			}
		} else {
			$GLOBALS["errormessage"] = 'Invalid method: '.$GLOBALS["uploadmethod"];
			$validcreate = False;
		}
	}

	chdir($savedir);
	return $validcreate;
} // function CreateNewDir()


function DeleteFile($path,$ffile)
{
	$savedir = getcwd();
	chdir($path);

	$validdelete = True;
	if (@file_exists($ffile)) {
		//	Default upload method is http:
		if (($GLOBALS["uploadmethod"] == 'http:') || ($GLOBALS["uploadmethod"] == '')) {
			$validdelete = @unlink($ffile);
			clearstatcache();
			//	If we failed to delete at the first attempt
			if (@file_exists($ffile)) {
				if ($GLOBALS["OS"] == "Windows") { $validdelete = @chmod($ffile, 666);; 
				} else { $validdelete = @chmod($ffile, 0666); }
				$validdelete = @unlink($ffile);
				clearstatcache();
			}
		} elseif ($GLOBALS["uploadmethod"] == 'ftp:') {
			if (function_exists('ftp_connect')) {
				$path = str_replace('../','',$path);
				// set up basic ftp connection
				$ftpConnection = @ftp_connect($GLOBALS["ftp"]["server"],$GLOBALS["ftp"]["port"]);
				if (!$ftpConnection) {
					$GLOBALS["errormessage"] = "FTP connection to ".$GLOBALS["ftp"]["server"]." has failed!";
					$validdelete = False;
				} else {
					// login with username and password
					$login_result = @ftp_login($ftpConnection, $GLOBALS["ftp"]["username"], $GLOBALS["ftp"]["password"]);
					// check connection
					if (!$login_result) {
						$GLOBALS["errormessage"] = "FTP connection has failed for user ".$GLOBALS["ftp"]["username"];
						$validdelete = False;
					} else {
						ftp_pasv($ftpConnection,TRUE);
						if (@ftp_chdir($ftpConnection, $GLOBALS["ftp"]["ezContents_root"])) {
							if (@ftp_chdir($ftpConnection, $path)) {
								$rmfile = @ftp_delete($ftpConnection, $ffile);
								// check upload status
								if (!$rmfile) {
									$GLOBALS["errormessage"] = "FTP upload has failed for directory ".$_FILES['filename']['name'];
									$validdelete = False;
								}
							} else {
								$GLOBALS["errormessage"] = "Failed changing directory to ".$path;
								$validdelete = False;
							}
						} else {
							$GLOBALS["errormessage"] = "Failed changing directory to ezContents root";
							$validdelete = False;
						}
					}
					// close the FTP stream
					ftp_close($ftpConnection);
				}
			} else {
				$GLOBALS["errormessage"] = 'FTP is unavailable';
				$validdelete = False;
			}
		} else {
			$GLOBALS["errormessage"] = 'Invalid method: '.$GLOBALS["uploadmethod"];
			$validdelete = False;
		}
	}

	if (isset($GLOBALS["errormessage"])) { echo $GLOBALS["errormessage"]; exit; }
	chdir($savedir);
	return $validdelete;
} // function DeleteFile();


function DeleteDir($path,$ffile)
{
	$savedir = getcwd();
	chdir($path);

	$validdelete = True;
	if (file_exists($ffile)) {
		//	Default upload method is http:
		if (($GLOBALS["uploadmethod"] == 'http:') || ($GLOBALS["uploadmethod"] == '')) {
			$validdelete = @rmdir($ffile);
			clearstatcache();
			//	If we failed to delete at the first attempt
			if (@file_exists($ffile)) {
				if ($GLOBALS["OS"] == "Windows") { $validdelete = @chmod($ffile, 666);; 
				} else { $validdelete = @chmod($ffile, 0666); }
				$validdelete = @rmdir($ffile);
				clearstatcache();
			}
		} elseif ($GLOBALS["uploadmethod"] == 'ftp:') {
			if (function_exists('ftp_connect')) {
				$path = str_replace('../','',$path);
				// set up basic ftp connection
				$ftpConnection = @ftp_connect($GLOBALS["ftp"]["server"],$GLOBALS["ftp"]["port"]);
				if (!$ftpConnection) {
					$GLOBALS["errormessage"] = "FTP connection to ".$GLOBALS["ftp"]["server"]." has failed!";
					$validdelete = False;
				} else {
					// login with username and password
					$login_result = @ftp_login($ftpConnection, $GLOBALS["ftp"]["username"], $GLOBALS["ftp"]["password"]);
					// check connection
					if (!$login_result) {
						$GLOBALS["errormessage"] = "FTP connection has failed for user ".$GLOBALS["ftp"]["username"];
						$validdelete = False;
					} else {
						ftp_pasv($ftpConnection,TRUE);
						if (@ftp_chdir($ftpConnection, $GLOBALS["ftp"]["ezContents_root"])) {
							if (@ftp_chdir($ftpConnection, $path)) {
								$rmdir = @ftp_rmdir($ftpConnection, $ffile);
								// check upload status
								if (!$rmdir) {
									$GLOBALS["errormessage"] = "FTP upload has failed for directory ".$_FILES['filename']['name'];
									$validdelete = False;
								}
							} else {
								$GLOBALS["errormessage"] = "Failed changing directory to ".$path;
								$validdelete = False;
							}
						} else {
							$GLOBALS["errormessage"] = "Failed changing directory to ezContents root";
							$validdelete = False;
						}
					}
					// close the FTP stream
					ftp_close($ftpConnection);
				}
			} else {
				$GLOBALS["errormessage"] = 'FTP is unavailable';
				$validdelete = False;
			}
		} else {
			$GLOBALS["errormessage"] = 'Invalid method: '.$GLOBALS["uploadmethod"];
			$validdelete = False;
		}
	};

	if (isset($GLOBALS["errormessage"])) { echo $GLOBALS["errormessage"]; exit; }
	chdir($savedir);
	return $validdelete;
} // function DeleteDir();

?>
