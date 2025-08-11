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
$root_path = preg_replace("/scripts\/tree\.xslt\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
include_once('../lang/'.$default_language.'.inc.php');
print '<?xml version="1.0" encoding="'.$GLOBALS["default_charset"].'"?>';
?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" encoding="<?=$GLOBALS["default_charset"]?>"/> 

<xsl:template match="/"> 

<bcwb form="start" />
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=$GLOBALS["http_path"]?>system/default.css.php" TITLE="Style" />
<title><?=$lang["Structure"]?></title>


<table width="100%" border="0" cellspacing="15" cellpadding="0">
	<tr>
		<td class="sys" nowrap="nowrap">
		<h1><?=$lang["Structure"]?></h1>

	    		    	<table border="0" cellspacing="0" cellpadding="0">
	    		    		<tr>
				    			<td style="cursor:hand" onclick="location.href='<?=$GLOBALS["http_path"]?>?action=additem'"><IMG SRC="system/btn101_l.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    			<td align="center" class="install" style="cursor:hand" background="system/btn101_c.gif"><a class="adminarea" href="<?=$GLOBALS["http_path"]?>?action=additem"><?=$lang["Add_item"]?></a></td>
				    			<td style="cursor:hand" onclick="location.href='<?=$GLOBALS["http_path"]?>?action=additem'"><IMG SRC="system/btn101_r.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    		</tr>
				    	</table>
<br />		
			<TABLE border="0" cellpadding="0" cellspacing="1">
			<xsl:for-each select="//root/tree/treeitem" >
				<tr>
				  <xsl:if test="not(@state='selected')">
				  	<td width="100%">	
					  	<a class="sys" >
							<xsl:attribute name="href">
								<xsl:value-of select="variable"/>
							</xsl:attribute>
							<B><xsl:value-of select="label"/></B>
						</a>
					</td>
						<xsl:call-template name="actions"/>
				  </xsl:if>
				  <xsl:if test="@state='selected'">
				  	<td width="100%">	
					  	<B><xsl:value-of select="label"/></B>
					</td>
						<xsl:call-template name="actions"/>
				  </xsl:if>
				</tr>
				<tr>
				<td bgcolor="#731410" colspan="2"><IMG SRC="/system/x.gif" WIDTH="1" HEIGHT="1" BORDER="0" ALT="" /></td>
				</tr>
				  
				  <xsl:call-template name="childtree"/>
				
			</xsl:for-each>	
			</TABLE>

		</td>
	</tr>
</table>

<bcwb form="finish" />

	</xsl:template>

	

	<xsl:template name="childtree"> 
		
		    <xsl:for-each select="child::*"> 
			<xsl:if test="name()='treeitem'">		    
				<tr>
				  	<xsl:if test="not(./@state='selected')">
				  		<td width="100%">	
						<a class="sys"  >
						<xsl:attribute name="href">
							<xsl:value-of select="./variable"/>
						</xsl:attribute>
						<IMG SRC="/system/x.gif" WIDTH="{@level*5}" HEIGHT="1" BORDER="0" ALT="" />
						<B><xsl:value-of select="./label"/></B></a> 
						</td>
						<xsl:call-template name="actions"/>
			  		</xsl:if>
			  		<xsl:if test="./@state='selected'">
			  			<td width="100%">
				  		<IMG SRC="/system/x.gif" WIDTH="{@level*5}" HEIGHT="1" BORDER="0" ALT="" />
				  		<B><xsl:value-of select="./label"/></B>
				  		</td>
				  		<xsl:call-template name="actions"/>
			  		</xsl:if>
				</tr>
				<tr>
					<td bgcolor="#731410" colspan="2"><IMG SRC="/system/x.gif" WIDTH="1" HEIGHT="1" BORDER="0" ALT="" /></td>
				</tr>
				<xsl:call-template name="childtree"/>
				
		 </xsl:if>				
	     </xsl:for-each> 
		
	</xsl:template>

	
	
	<xsl:template name="actions"> 
	<td nowrap="nowrap">
			<a class="sys" >
				<xsl:attribute name="href">
					<xsl:value-of select="variable"/><xsl:text>?action=editpage</xsl:text>
				</xsl:attribute>
				<?=$lang["Edit_page"]?>
			</a> |
			<a class="sys" >
				<xsl:attribute name="href">
					<xsl:value-of select="variable"/><xsl:text>?action=createsubitem</xsl:text>
				</xsl:attribute>
				<?=$lang["Create_subitem"]?>
			</a> |
			<a class="sys" >
				<xsl:attribute name="href">
					<xsl:value-of select="variable"/><xsl:text>?action=delete</xsl:text>
				</xsl:attribute>
				<?=$lang["Delete"]?>
			</a>				
	</td>
	</xsl:template>

	

</xsl:stylesheet> 


