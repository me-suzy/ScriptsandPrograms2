<?php

// mail_down.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: mail_down.php,v 1.5 2005/06/20 14:49:08 paolo Exp $

// intialise the array so noone can introduce poisoned variables
$arr = array();

// include lib to fetch the sessiond data and to perform check
$path_pre="../";
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;

// check role
if (check_role("mail") < 1) { die("You are not allowed to do this!"); }

// @session_cache_limiter('public');   // suppress error messages for PHP version < 4.0.2


$arr = explode("|",$file_ID[$rnd]);

// check permission
$result = db_query("select von from ".DB_PREFIX."mail_client, ".DB_PREFIX."mail_attach
                     where ".DB_PREFIX."mail_attach.ID = '$arr[2]' and
                           ".DB_PREFIX."mail_attach.parent = ".DB_PREFIX."mail_client.ID") or db_die();
$row = db_fetch_row($result);
if ($row[0] <> $user_ID) { die("You are not allowed to do this"); }

// Prevent escaping from the attach dir
if ((ereg('/', $arr[1])) or (ereg('^\.+$', $arr[1]))) { die("You are not allowed to do this!");  }

// assign the filename
$name = $arr[0];

// have a look whether this file exists
if (!file_exists($path_pre.PHPR_ATT_PATH."/".$arr[1])) { die("panic! specified file not found ..."); }

// include content type definition
$include_path = $path_pre."lib/get_contenttype.inc.php";
include_once $include_path;

// stream the file
readfile($path_pre.PHPR_ATT_PATH."/".$arr[1]);
?>
