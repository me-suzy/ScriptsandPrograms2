<?php

  	/*=====================================================================
	// $Id: pre_include_standard.inc.php,v 1.3 2005/01/10 15:58:04 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Datum aus Vergangenheit
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                    // HTTP/1.0

	$timer_start = microtime();

	include ("config/config.inc.php");
	include ("connect_database.php");
	include ("inc/functions.inc.php");
	include ("inc/acl.inc.php");
	
	//session_start();

    loadLanguageFile ();

	$user_id		= $_SESSION ["user_id"];
	//$language		= var_include_int ("language", "SESSION");
	$login			= $_SESSION ["login"];
	$passwort		= $_SESSION ["passwort"];
	//$group			= var_include_int ("group",    "SESSION");

    if (!isset($dont_do_security_check))
     	$dont_do_security_check = false;
    else
    	$dont_do_security_check = true;

    if (!$dont_do_security_check) {
		security_check_core();
    }
?>