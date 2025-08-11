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

$block='
 	<LINK REL="stylesheet" TYPE="text/css" HREF="'.$GLOBALS["http_path"].'system/default.css.php" TITLE="Style" />

    <input type="hidden" name="tags_list" value="header," />
	<br /><br /><br /><br /><br /><br />
	<table width="100%" cellpadding="0" cellspacing="0">
		  <tr>
		    <td valign="middle" align="center">
		    
				<table cellpadding="3" cellspacing="5">
				  <tr>
				    <td align="left" valign="middle" width="400">
				    <h1><xsl:value-of select="//root/doc/header"/></h1>
				    </td>
				  </tr>
				  <tr>
				    <td width="400"  class="install">
				    <xsl:copy-of select="/root/doc/description/*"/>
				    <hr color="#C0C0C0" noshade="noshade" />
					</td>
				  </tr>
				</table>
		    
		    </td>
		  </tr>
		</table>
		';
?>