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

?>
<html>
 <head>
  <title><?=$lang["Template_select"]?>: <?=$this->xslt_filename?></title>
  
	<LINK REL="stylesheet" TYPE="text/css" HREF="<?=$GLOBALS["http_path"]?>system/default.css.php" TITLE="Style" />
	
	<base href="<?=$GLOBALS["http_path"]?>" />
	</head>
 
<body bgcolor="#F6F7F7" leftmargin="0" topmargin="0" marginwidth="0">

<?PHP
if($GLOBALS["MODREWRITE"] == "disable") $query_uri = $_GET["vpath"];
else $query_uri = preg_replace("/\?(.*?)$/is", "", $GLOBALS["REQUEST_URI"]);
$query_uri = preg_replace("/^\//is", "", $query_uri);

$url_mode = $this->query_uri.'?';
if($GLOBALS["MODREWRITE"] == "disable") $url_mode = '?vpath='.$query_uri.'&#38;';


print '
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

<form action="'.$GLOBALS["http_path"].str_replace("&#38;", "", $url_mode).'" method="post" name="bcwb_form" style="	padding-top : 0px; padding-bottom : 0px; margin-top : 0px; margin-bottom : 0px;">
<input type="hidden" name="OK" value="1" />	

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>	
		<td>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td onclick="return helpdesk(\'Info\')" style="CURSOR: hand"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_logo.gif" WIDTH="69" HEIGHT="55" ALT="" /></td>
		<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_verline.gif" WIDTH="4" HEIGHT="55" ALT="" /></td>
		<td width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
				<tr>
					<td><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
					'.btn(false, $lang["Save"], "bcwb_form.submit(); return false").'
					'.btn($GLOBALS["http_path"].$query_uri, $lang["Cancel"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>
	'.btn($GLOBALS["http_path"].$url_mode.'action=tree', $lang["Structure"]).'	
	'.btn($GLOBALS["http_path"].$url_mode.'action=stats', $lang["Statistic"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>	
	'.btn($GLOBALS["http_path"].'logout/admin/', $lang["Logout"]).'
					<td width="100%"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
				</tr>
			</table><table width="100%" border="0" cellspacing="0" cellpadding="0" background="'.$GLOBALS["http_path"].'system/v2_bgdw">
				<tr>
					<td><a href="#"  onclick="return helpdesk(\'Template\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: '.$lang["Template_select"].'" /></a></td>
					<td nowrap="nowrap" class="adminarea">'.$lang["Template_select"].': <a class="admin_com">'.$this->xslt_filename.'</a>&#xA0;&#xA0;</td>
					
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
?>
	</td>
</tr>
<tr>
	<td height="100%">
<input type="hidden" name="edit_template_name" value="<?=$this->xslt_filename?>" />
<textarea  name="content"  style="width:100%; height=100%">
 <?PHP
 	if (($fp = @fopen($root_path."dcontent/".$this->xslt_filename, "r")))  {
		while ($block = fread($fp, 4096)) { $content .= $block;  }
	fclose($fp); }
  
 
 print preg_replace("/<(.?)textarea(.*?)>/is",  "{\\1textarea\\2}", $content);
 ?> 
</textarea>
	</td>
</tr>
</table>

</form>

 	</body>
 </html>
