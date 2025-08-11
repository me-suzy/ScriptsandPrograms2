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
include_once("../include/startup/common_functions.inc.php");

$root_path = preg_replace("/scripts\/helpdesk\.xslt\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);

if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
include_once('../lang/'.$default_language.'.inc.php');

$xslData = '
<xsl:stylesheet version = \'1.0\' 
     xmlns:xsl=\'http://www.w3.org/1999/XSL/Transform\'>

<xsl:output method="html" encoding="'.$GLOBALS["default_charset"].'"/> 
<xsl:template match="/"> 
<LINK REL="stylesheet" TYPE="text/css" HREF="'.$GLOBALS["http_path"].'system/default.css.php" TITLE="Style" />
<title><xsl:value-of select="//root/doc/title"/></title>


<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td ><IMG SRC="'.$GLOBALS["http_path"].'system/install_logo2.gif" WIDTH="117" HEIGHT="51" ALT="BCWB v2.0." /></td>
		<td width="100%" valign="middle" background="'.$GLOBALS["http_path"].'system/install_bg.gif"> 
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="panel_title">&#xA0;HELPDESK</td>
				
				</tr>
			</table>
		</td>
	</tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="10">

	<tr>
		<td><br />
    <h1><xsl:value-of select="//root/doc/header"/></h1>
    <xsl:copy-of select="/root/doc/description/*"/> 
		</td>
	</tr>
	
</table>

	</xsl:template>
</xsl:stylesheet> 
';

if( !defined("SABLOTRON") )  print $xslData;
?>