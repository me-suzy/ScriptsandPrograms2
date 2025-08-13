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
// | $RCSfile: init_vars.php,v $ - $Revision: 1.4 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Recursively strips slashes from $array
function stripslashesarray($array) {
	if (is_array($array)) {
		foreach($array as $key => $val) {
			if (is_array($val)) {
				$array["$key"] = stripslashesarray($val);
			} elseif (is_string($val)) {
				if (get_cfg_var('magic_quotes_sybase')) {
					$array["$key"] = str_replace("''", "'", $val);
				} else {
					$array["$key"] = stripslashes($val);
				}
			}
		}
	}

	return $array;
}

// ############################################################################
// God I hate magic_quotes...
if (get_magic_quotes_gpc() and !defined('VB_PLUGIN')) {
	$_GET = stripslashesarray($_GET);
	$_POST = stripslashesarray($_POST);
	$_COOKIE = stripslashesarray($_COOKIE);
	$_REQUEST = stripslashesarray($_REQUEST);
}
set_magic_quotes_runtime(0);

// ############################################################################
// Register globals
// HiveMail v2 will have this turned off
if ((!isset($PHP_SELF) or get_magic_quotes_gpc()) and !defined('VB_PLUGIN')) {
	// Make sure $cmd doesn't get overwritten
	unset($_COOKIE['cmd']);
	$extract_type = (get_magic_quotes_gpc() ? EXTR_OVERWRITE : EXTR_SKIP);
	@extract($_GET, $extract_type);
	@extract($_SERVER, $extract_type);
	@extract($_COOKIE, $extract_type);
	@extract($_FILES, $extract_type);
	@extract($_POST, $extract_type);
	@extract($_ENV, $extract_type);
}

?>