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

$output = "<?xml version=\"1.0\" encoding=\"".$GLOBALS["default_charset"]."\"?>\r\n";
if( !defined("SABLOTRON") ) $output .= "<?xml-stylesheet type='text/xsl' href='".$GLOBALS["http_path"]."scripts/system.xslt.php'?>\r\n";
$output .= "\r\n<root>\r\n";
$output .= "<doc>\r\n";
$output .= "	<title>404 ERROR</title>\r\n";
$output .= "	<header>".$lang["Page_not_found"]."</header>\r\n";
$output .= "	<description>\r\n";
$output .= "<P>".$lang["Page_not_found_desc"]."</P>\r\n";
$output .= "	</description>\r\n";
$output .= "</doc>\r\n";
$output .= "\r\n</root>\r\n";

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

if(!defined("SABLOTRON"))
	header("Content-Type: text/xml; charset=".$GLOBALS["default_charset"]); 


		if( defined("SABLOTRON") )
		{
			include_once($GLOBALS["root_path"]."scripts/system.xslt.php");
			include_once($GLOBALS["root_path"]."include/lib/sablotron.inc.php");
		}
		else 
			print $output;

exit;
?>