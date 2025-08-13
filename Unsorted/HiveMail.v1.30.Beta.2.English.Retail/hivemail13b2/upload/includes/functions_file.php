<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_file.php,v $ - $Revision: 1.5 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Returns an array of files and directories from the $directory. If $directory
// is not a directory, then boolean FALSE is returned. By default, the sorting
// order is alphabetical in ascending order. If the optional $sorting_order is
// used (set to 1), then sort order is alphabetical in descending order. (PHP5)
if (!function_exists('scandir')) {
	function scandir($directory, $sorting_order = 0) {
		if (!($dh = opendir($directory))) {
			return false;
		}

		$files = array();
		while (false !== ($filename = readdir($dh))) {
			if ($filename != '.' and $filename != '..') {
				$files[] = $filename;
			}
		}
		closedir($dh);

		if (!$sorting_order) {
			sort($files);
		} else {
			rsort($files);
		}

		return $files;
	}
}

// ############################################################################
// Returns total size of a folder
function foldersize($folder) {
	$size = 0;
	if ($dh = @opendir($folder)) {
		while (($filename = readdir($dh)) !== false) {
			if ($filename != '.' and $filename != '..') {
				if (is_file("$folder/$filename")) {
					$func = 'filesize';
				} else {
					$func = 'foldersize';
				}
				$size += $func("$folder/$filename");
			}
		}
		closedir($dh);
	}
	return $size;
}

// ############################################################################
// Reads data from $filename
function readfromfile($filename, $binary = true, $getdata = true) {
	if (function_exists('file_get_contents')) {
		return file_get_contents($filename);
	} else {
		$mode = 'r'.iif($binary, 'b');
		$data = '';
		if (is_readable($filename) and
			is_resource($fp = @fopen($filename, $mode)) and
			(!$getdata or filesize($filename) == 0 or $data = @fread($fp, @filesize($filename))) and
			@fclose($fp)) {
			return $data;
		} else {
			return false;
		}
	}
}

// ############################################################################
// Creates a file called $filename and stores $data in it
function writetofile($filename, &$data, $append = false, $binary = true) {
	$folder = substr($filename, 0, strrpos($filename, '/'));
	$mode = iif($append, 'a', 'w').iif($binary, 'b');
	return (is_writable($folder) and
			is_resource($fp = @fopen($filename, $mode)) and
			@fputs($fp, $data) !== false and
			@fclose($fp));
}

// ############################################################################
// A useful function when handling file uploads
// I wouldn't use it where it's not already used since it's done quite done yet
function upload_file(&$filename, &$data, $fieldname = 'file', $maxsize = 0, $validtypes = array()) {
	if (is_array($maxsize)) {
		list($maxsize, $sizeerror) = each($maxsize);
	}

	$filename = strtolower($_FILES["$fieldname"]['name']);
	$extension = getextension($filename);

	if (!empty($validtypes) and !array_contains($extension, $validtypes)) {
		return false;
	}

	if (is_uploaded_file($_FILES["$fieldname"]['tmp_name'])) {
		if (getop('safeupload')) {
			$path = getop('tmppath', true).'/'.$filename;
			move_uploaded_file($_FILES["$fieldname"]['tmp_name'], $path);
			$_FILES["$fieldname"]['tmp_name'] = $path;
		}

		$filesize = filesize($_FILES["$fieldname"]['tmp_name']);
		if ($filesize == $_FILES["$fieldname"]['size'] and strstr($_FILES["$fieldname"]['tmp_name'], '..') == '') {
			if ($maxsize > 0 and $filesize > $maxsize) {
				unlink($_FILES["$fieldname"]['tmp_name']);
				return false;
			}
			$data = '';
			$fp = fopen($_FILES["$fieldname"]['tmp_name'], 'rb');
			$data = fread($fp, $filesize);
			fclose($fp);
			unlink($_FILES["$fieldname"]['tmp_name']);

			return true;
		}
	}

	return false;
}

// ############################################################################
// Gets the maximum size we can upload through POST forms
function get_max_upload() {
	if (!ini_get('file_uploads')) {
		return false;
	}
	$upload_max_filesize = get_real_size(ini_get('upload_max_filesize'));
	$post_max_size = get_real_size(ini_get('post_max_size'));
	$memory_limit = round(get_real_size(ini_get('memory_limit')) / 2);
	if ($post_max_size < $upload_max_filesize) {
		$max = $post_max_size;
	} else {
		$max = $upload_max_filesize;
	}
	if (!empty($memory_limit) and $memory_limit < $max) {
		$max = $memory_limit;
	}
	return $max;
}
function get_real_size($size) {
	if (empty($size)) {
		return 0;
	}
	$scan['MB'] = 1048576;
	$scan['M'] = 1048576;
	$scan['KB'] = 1024;
	$scan['K'] = 1024;
	foreach ($scan as $name => $value) {
		if (strlen($size) > strlen($name) and substr($size, strlen($size) - strlen($name)) == $name) {
			$size = substr($size, 0, strlen($size) - strlen($name)) * $value;
			break;
		}
	}
	return $size;
}

// ############################################################################
// Checks if the file we are currently viewing is $filename
function infile($filename) {
	return (substr(basename($_SERVER['PHP_SELF']), 0, strlen($filename)) == $filename);
}

// ############################################################################
// Gets the extension of $filename
function getextension($filename) {
	return substr(strrchr($filename, '.'), 1);
}

// ############################################################################
// Returns a unique folder name for data files
function get_dirname($overrideoption = false) {
	if (!$overrideoption and !getop('flat_use')) {
		return '';
	}

	if (getop('flat_curcount') < MAX_FLAT_FILES) {
		$dirname = getop('flat_curfolder');
	} else {
		do {
			$dirname = uniquestring();
		} while (@is_dir(getop('flat_path', true).'/'.$dirname));
	}

	return $dirname;
}

// ############################################################################
// Returns a unique string to be used in filenames of data files
function make_filename($folder = '', $overrideoption = false) {
	if (!$overrideoption and !getop('flat_use')) {
		return '';
	}

	if (!empty($folder)) {
		$folder .= '/';
	}
	do {
		$filename = uniquestring();
	} while (@file_exists(getop('flat_path', true).'/'.$folder.getop('flat_prefix').$filename.'.dat'));

	return $filename;
}

?>