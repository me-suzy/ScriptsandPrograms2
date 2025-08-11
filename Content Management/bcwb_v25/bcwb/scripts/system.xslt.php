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

if( !defined("SABLOTRON") )
{

	include_once("../config.inc.php");
	$root_path = preg_replace("/scripts\/system\.xslt\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);
	if($language=="ru" or $language=="en") $default_language=$language;
	if(!$default_language) $default_language="ru";
	include_once('../lang/'.$default_language.'.inc.php');
	
	
	include($root_path.'include/startup/debug.inc.php');
	include($root_path.'include/startup/auth.inc.php');
	include($root_path.'include/startup/common_functions.inc.php');
	include($root_path.'include/lib/admin.lib.php');
	$bcwb_admin->action = $GLOBALS["action"];
	
	$bcwb_admin->url_parse();
	$bcwb_admin->get_xslt_list();

} else {
	$bcwb_admin = new bcwb_admin;
}


$xslData = '
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output
 method="html"
 media-type="text/html"
 doctype-public="-//W3C//DTD HTML 4.0 Transitional//EN"
 indent="yes"
 encoding="UTF-8"
/>

<xsl:template match="/"> 

<html>
 <head>
  <title>BCWB :: <xsl:value-of select="//root/doc/title"/></title>
 </head>
 
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0">

	<bcwb form="start" />

		<table height="100%" width="100%" cellpadding="0" cellspacing="0">
		  <tr>
		    <td valign="middle" align="center">
		    
				<table cellpadding="3" cellspacing="5">
				  <tr>
				    <td valign="top" align="left"><img id="pagerrorImg" SRC="'.$GLOBALS["http_path"].'system/pagerror.gif" width="25" height="33" /></td>
				    <td align="left" valign="middle" width="360">
				    <h1 style="COLOR: black; FONT: 13pt/15pt verdana"><span id="errorText"><xsl:value-of select="//root/doc/header"/></span></h1>
				    </td>
				  </tr>
				  <tr>
				    <td width="400" colspan="2">
				    <font style="COLOR: black; FONT: 8pt/11pt verdana"><xsl:copy-of select="/root/doc/description/*"/></font>
				    <hr color="#C0C0C0" noshade="noshade" />
					</td>
				  </tr>
				</table>
		    
		    </td>
		  </tr>
		</table>

	<bcwb form="finish" />

 	</body>
 </html>

	</xsl:template>
</xsl:stylesheet> 
';


if( !$GLOBALS["authorized"] )
	$xslData = ( $bcwb_admin->admin_parse_content( $xslData ) ) ;
else 
	$xslData = ( $bcwb_admin->admin_header_parse( $bcwb_admin->admin_parse_content( $xslData ) ) );


if( !defined("SABLOTRON") ) print $xslData;
?>

