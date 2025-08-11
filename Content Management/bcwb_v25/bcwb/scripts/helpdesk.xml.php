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

include_once("../config.inc.php");

if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
include_once('../lang/'.$default_language.'.inc.php');


	$lang["Info"]["title"] = "About BCWB";
	$lang["Info"]["description"] = "Business Card Web Builder v.".$THIS_VERSION.". <br /> Author: Dmitry Sheiko (info@cmsdevelopment.com)<br /><br /> <a href=\"http://bcwb.cmsdevelopment.com\" target=\"_blank\">cmsdevelopment.com/bcwb/</a>";

if(!$pointer) $pointer = "Url";

header("Content-Type: text/xml");

$output = "<?xml version=\"1.0\" encoding=\"".$GLOBALS["default_charset"]."\"?>\r\n";
if(!defined("BCWB_NOXSLT") AND !defined("SABLOTRON") )
	$output .= "<?xml-stylesheet type='text/xsl' href='".$http_path."scripts/helpdesk.xslt.php'?>\r\n";
$output .= "\r\n<root>\r\n";
$output .= "<doc>\r\n";
$output .= "	<title>".$lang["Helpdesk_window"]."</title>\r\n";
$output .= "	<header>".$lang[$pointer]["title"]."</header>\r\n";
$output .= "	<description>\r\n";
$output .= "<P>".$lang[$pointer]["description"]."</P>\r\n";
$output .= "	</description>\r\n";
$output .= "</doc>\r\n";
$output .= "\r\n</root>\r\n";

if( defined("SABLOTRON") )	{
	include_once($GLOBALS["root_path"]."scripts/xslt.php");
	include_once($GLOBALS["root_path"]."scripts/helpdesk.xslt.php");
} else  print $output;


?>