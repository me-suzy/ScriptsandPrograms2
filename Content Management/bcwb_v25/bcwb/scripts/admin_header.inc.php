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

if($this->action=="additem" OR $this->action=="createsubitem") $this->last_argv = false;

$url_mode = $this->query_uri.'?';
if($GLOBALS["MODREWRITE"] == "disable") $url_mode = '?vpath='.$this->query_uri.'&#38;';

$admin_header = '

<base href="'.$GLOBALS["http_path"].'" />

<style>
BODY {	padding : 0px 0px 0px 0px; margin : 0px 0px 0px 0px; }

SELECT.cont_btn, INPUT.cont_btn { font-family: Tahoma, Arial, Helvetica, sans-serif; FONT-SIZE: 12px; }
H1.sys {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 18px; color: #585859;  }
TD.sys {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 11px; color: #585859; text-decoration : none; }
A.sys, A.sys:visited, A.sys:active, A.sys:link {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 11px; color: #707071; text-decoration : none; }
A.sys:hover { color: #FC7126; }

TD.adminarea, A.adminarea, A.adminarea:visited, A.adminarea:active, A.adminarea:link {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #585859;  text-decoration : none; font-weight:bold; }
A.adminarea:hover { text-decoration : none; color: #000000; }
INPUT.adminarea, SELECT.adminarea {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 11px; color: #585859; background-color: #F6F7F7 }
A.admin_com, A.admin_com:visited, A.admin_com:active, A.admin_com:link {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #707071;  text-decoration : none; font-weight: normal; font-style: italic}

DL { margin-left: 15px; }
.bold {  color: #529EE8; }
.alert {  color: #FC7126; }

</style>

<SCRIPT LANGUAGE="JavaScript">
//<![CDATA[

function helpdesk(pointer)
{
	window.open(\''.$GLOBALS["http_path"].'scripts/helpdesk.xml.php?pointer=\'+pointer, \'displayWindow\',\'width=250,height=200,status=no,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
	return false;
}

function checkbindfield(el)
{
	if(el.url.value.length==0)  { alert(\''.$lang["NoUrl"].'\'); return false; }
	el.submit();
	return false;
}

function delete_item()
{
	if(confirm(\''.$lang["You_are_sure"].'?\'))
		location.href=\''.$GLOBALS["http_path"].$url_mode.'action=delete\';

		return false;
}

//]]>
</SCRIPT>
';
if($this->action=="editpage" OR $this->action=="additem" OR $this->action=="createsubitem") {
	$admin_header .= '
<form action="'.$GLOBALS["http_path"].str_replace("&#38;", "", $url_mode).'" method="post" name="bcwb_form" style="	padding-top : 0px; padding-bottom : 0px; margin-top : 0px; margin-bottom : 0px;">
<input type="hidden" name="OK" value="1" />	

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td onclick="return helpdesk(\'Info\')" style="CURSOR: hand"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_logo.gif" WIDTH="69" HEIGHT="55" ALT="" /></td>
		<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_verline.gif" WIDTH="4" HEIGHT="55" ALT="" /></td>
		<td width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
				<tr>
					<td><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
					'.btn(false, $lang["Save"], "return submit_data()").'
					'.btn($GLOBALS["http_path"].$this->query_uri, $lang["Cancel"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>
	'.btn($GLOBALS["http_path"].$url_mode.'action=tree', $lang["Structure"]).'	
	'.btn($GLOBALS["http_path"].$url_mode.'action=stats', $lang["Statistic"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>	
	'.btn($GLOBALS["http_path"].'logout/admin/', $lang["Logout"]).'
					<td width="100%"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
				</tr>
			</table><table width="100%" border="0" cellspacing="0" cellpadding="0" background="'.$GLOBALS["http_path"].'system/v2_bgdw">
				<tr>
					<td><a href="#"  onclick="return helpdesk(\'Url\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: URI" /></a></td>
					<td nowrap="nowrap" class="adminarea">URI: '.(($this->action=="editpage" AND !$this->last_argv) ? '<i><b>mainpage</b></i><input type="hidden" name="url"  value="index" />' : '<input class="adminarea" type="text" name="url" value="'.$this->last_argv.'"/>' ).'&#xA0;&#xA0;</td>
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>
	
					<td><a href="#"  onclick="return helpdesk(\'Title\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: META-TITLE" /></a></td>
					<td nowrap="nowrap" class="adminarea">META-TITLE: <input class="adminarea" type="text" name="title" />&#xA0;&#xA0;</td>
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>

					<td><a href="#"  onclick="return helpdesk(\'Template\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: '.$lang["Template_select"].'" /></a></td>
					<td nowrap="nowrap" class="adminarea">'.$lang["Template_select"].': <select class="adminarea"  name="xslt" >'.$this->xslt_list_html.'
					</select>&#xA0;</td>
					
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>

					<td width="100%">&#xA0;</td>
				</tr>
			</table>	
	
		</td>
	</tr>
	<tr>	
		<td colspan="3" bgcolor="#B4B4B4"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="1" ALT="" /></td>
	</tr>
	
</table>	
	
	
	<input type="hidden" name="arg_ImgUrl" />
	<input type="hidden" name="arg_AltText" />
    <input type="hidden" name="arg_ImgBorder" />
    <input type="hidden" name="arg_HorSpace" />
    <input type="hidden" name="arg_VerSpace" />
    <input type="hidden" name="arg_ImgAlign" />
    <input type="hidden" name="arg_ImgHeight" />
    <input type="hidden" name="arg_ImgWidth" />
	
';
}
else
{
	$admin_header .= '
<form action="'.$GLOBALS["http_path"].$this->query_uri.'" method="post" name="bcwb_form" style="	padding-top : 0px; padding-bottom : 0px; margin-top : 0px; margin-bottom : 0px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td onclick="return helpdesk(\'Info\')" style="CURSOR: hand"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_logo.gif" WIDTH="69" HEIGHT="55" ALT="" /></td>
		<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_verline.gif" WIDTH="4" HEIGHT="55" ALT="" /></td>
		<td width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
				<tr>
					<td><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
					'.btn($GLOBALS["http_path"].$url_mode.'action=editpage', $lang["Edit_page"]).'
					'.btn($GLOBALS["http_path"].$url_mode.'action=additem', $lang["Add_item"]).'
					'.btn($GLOBALS["http_path"].$url_mode.'action=createsubitem', $lang["Create_subitem"]).'
	'.btn(false, $lang["Delete"], "return delete_item()").'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>
	'.btn($GLOBALS["http_path"].$url_mode.'action=tree', $lang["Structure"]).'	
	'.btn($GLOBALS["http_path"].$url_mode.'action=stats', $lang["Statistic"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>	
	'.btn($GLOBALS["http_path"].'logout/admin/', $lang["Logout"]).'
					<td width="100%"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
				</tr>
			</table><table width="100%" border="0" cellspacing="0" cellpadding="0" background="'.$GLOBALS["http_path"].'system/v2_bgdw">
				<tr>
					<td><a href="#"  onclick="return helpdesk(\'Url\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: URI" /></a></td>
					<td nowrap="nowrap" class="adminarea">URI: <a class="admin_com">'.($this->last_argv ? '<i>'.$this->last_argv.'</i>' : '<i><b>mainpage</b></i>').'</a>&#xA0;&#xA0;</td>
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>
	
					<td><a href="#"  onclick="return helpdesk(\'Title\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: META-TITLE" /></a></td>
					<td nowrap="nowrap" class="adminarea">META-TITLE: <a class="admin_com"><xsl:value-of select="//root/doc/title"/></a>&#xA0;&#xA0;</td>
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>

					<td><a href="#"  onclick="return helpdesk(\'Template\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: '.$lang["Template_select"].'" /></a></td>
					<td nowrap="nowrap" class="adminarea">'.$lang["Template_select"].': <a class="admin_com">'.$this->xslt_filename.'</a>&#xA0;</td>
					<td><a href="'.$url_mode.'action=edittemplate"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_edit.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Edit template" /></a>&#xA0;&#xA0;</td>
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>

					<td width="100%">&#xA0;</td>
				</tr>
			</table>	
	
		</td>
	</tr>
	<tr>	
		<td colspan="3" bgcolor="#B4B4B4"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="1" ALT="" /></td>
	</tr>
	
</table>	
';
}
?>