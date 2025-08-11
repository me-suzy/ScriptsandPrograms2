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
if( !defined("SABLOTRON") ) $output .= "<?xml-stylesheet type='text/xsl' href='".$GLOBALS["http_path"]."scripts/system.xslt.php?action=".$GLOBALS["action"]."'?>\r\n";
$output .= "\r\n<root>\r\n";
$output .= "<doc>\r\n";
$output .= "	<title>".($GLOBALS["action"]=="additem" ? $lang["Adding_item"] : $lang["Creating_subitem"])."</title>\r\n";
$output .= "	<header>".($GLOBALS["action"]=="additem" ? $lang["Adding_item"] : $lang["Creating_subitem"])."</header>\r\n";
$output .= "	<description>\r\n";
$output .= "<P>".$lang["Is_it_necessary_fill_fileld"]."</P>\r\n";
$output .= "	</description>\r\n";
$output .= "</doc>\r\n";
$output .= "\r\n</root>\r\n";
?>