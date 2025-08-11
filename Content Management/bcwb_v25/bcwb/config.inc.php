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


$http_path="http://bcwb.rh8.rg/";

/*
	Available languages
--------------------------
	ru - Russia  	(Windows-1251)
	en - English   	(ISO-8859-1)
	ua - Ukraine 	(UTF-8)
	by - Belarus	(UTF-8)
	pl - Poland 	(UTF-8)
	en - English	(UTF-8)
	de - Germany 	(UTF-8)
	fr - France 	(UTF-8)
	et - Estonian 	(UTF-8)
	he - Hebrew 	(UTF-8)
	vi - Vietnamese (UTF-8)
	it - Italy	(UTF-8)

	-- Translation powered by http://www.systransoft.com/ --
	cn - China		(UTF-8)	
	jp - Japan		(UTF-8)
	kr - Korea		(UTF-8)
	es - Spain		(UTF-8)


*/

$ACCEPT_LANGUAGES = array("en");

$default_charset="ISO-8859-1";
$default_language = "en";
$root_path = preg_replace("/index\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"])."/";
$root_path = preg_replace("/\/\//is","/", $root_path);

$MODREWRITE = "enable";

$FEEDBACK_EMAIL = "sheiko@cmsdevelopment.com";

Error_Reporting(1+2+4);

$admin_login = "pass";
$admin_password = "pass";
$admin_subdomain = "login";

/* Define BCWB tags pattern */
$BCWB_TAG["form_start"] = '<bcwb form="start" />';
$BCWB_TAG["form_finish"] = '<bcwb form="finish" />';
$BCWB_TAG["content"] = '<bcwb content="(.*?)" />';

$THIS_VERSION = "1.0";

$DEFAULT_CONTENT_TYPE = "text/html";
$PAGE_CONTENT_TYPE_PREF = array(
						"textplain__" => "text/plain",
						"textxml__" => "text/xml",
						"svg__" => "image/svg+xml",
						"pdf__" => "application/pdf",
						"apprtf__" => "application/rtf",
						"apppostscript__" => "application/postscript",
						"appoctetstream__" => "application/octet-stream",
						"appzip__" => "application/zip",
						"appmsword__" => "application/msword",
						"remoteprinting__" => "application/remote-printing",
						"audiobasic__" => "audio/basic",
						"videompeg__" => "video/mpeg",
						"videoquicktime__" => "video/quicktime",
						);


/*****
 * Turn local language version of the your site

if( in_array($GLOBALS["HTTP_ACCEPT_LANGUAGE"], $ACCEPT_LANGUAGES) AND !str_replace("/","", $GLOBALS["REQUEST_URI"]) )
	$GLOBALS["REQUEST_URI"] = "/".$GLOBALS["HTTP_ACCEPT_LANGUAGE"]."/";

	if(eregi("twosite.com", $GLOBALS["HTTP_HOST"]) ) {
		$GLOBALS["REQUEST_URI"] = "/twosite/";
	}

*****/

?>
