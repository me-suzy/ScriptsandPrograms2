<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/


$phpver = phpversion();
if ($phpver >= '4.0.4pl1' && strstr($HTTP_USER_AGENT,'compatible')) {
    if (extension_loaded('zlib')) {
	ob_end_clean();
	ob_start('ob_gzhandler');
    }
} else if ($phpver > '4.0') {
    if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	if (extension_loaded('zlib')) {
	    $do_gzip_compress = TRUE;
	    ob_start();
	    ob_implicit_flush(0);
	    //header('Content-Encoding: gzip');
	}
    }
}

$phpver = explode(".", $phpver);
$phpver = "$phpver[0]$phpver[1]";
if ($phpver >= 41) {
    $PHP_SELF = $_SERVER['PHP_SELF'];
}
 
	if (!ini_get("register_globals")) die("Cold't declare register_globals");
	
?>