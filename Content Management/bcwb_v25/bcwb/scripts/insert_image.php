<?php
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

include("../config.inc.php");
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";

$root_path = preg_replace("/scripts\/insert_image\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);
include('../lang/'.$default_language.'.inc.php');


function sysB_chkgd2()
{

$rep=false;
if(isset($GLOBALS["gBGDVersion"])) {
   $rep=$GLOBALS["gBGDVersion"];
   } else {
   if(function_exists("gd_info")) {
       $gdver=gd_info();
       if(strstr($gdver["GD Version"],"1.")!=false) $rep=false; else  $rep=true;
       } else {
       $size=40;
       $font= '';
       $rep=false;
       //$b=imagettfbbox ($size,0,$font,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
       if(($size+1)<abs($b[5]-$b[3])) $rep=true; 
        }
   $GLOBALS["gBGDVersion"]=$rep;
   }

return $rep;
} 



/**
* @desc Save POST data
*/
if($_POST)
{
	
	include('../scripts/image_resize.inc.php');
	$dcontentpath=$root_path."dcontent/";
	$dcontentpath_pref=$root_path."dcontent/";
	$filedname="ImgUrl"; 
	$filebasename=$HTTP_POST_FILES[$filedname]['name'];

	if( preg_match("/\.php$/is", $filebasename) ) $filebasename = $filebasename.".txt";
	if( preg_match("/\.phtml$/is", $filebasename) ) $filebasename = $filebasename.".txt";
	if( preg_match("/\.php3$/is", $filebasename) ) $filebasename = $filebasename.".txt";

	

	if($HTTP_POST_FILES[$filedname]['tmp_name']!="" && $HTTP_POST_FILES[$filedname]['tmp_name']!="none")
	{ 

		$newfilename = str_replace("/","_", $_POST["script_uri"]);
		if(!copy($HTTP_POST_FILES[$filedname]['tmp_name'], $dcontentpath_pref.$newfilename.$filebasename)) $error_message=$lang["Can_not_save_uploaded_file"];
		elseif($flag_icon) {
			$farr['tmp_name'] = $HTTP_POST_FILES[$filedname]['tmp_name'];
			$farr['name'] = $HTTP_POST_FILES[$filedname]['name'];
			$farr['type'] = $HTTP_POST_FILES[$filedname]['type'];
			$farr['error'] = $HTTP_POST_FILES[$filedname]['error'];
			$farr['size'] = $HTTP_POST_FILES[$filedname]['size'];
			create_tnail($farr, $icon_width, $icon_height, $dcontentpath_pref, "icon_".$newfilename  );
		}
	}

$content = '<body>
'.( $error_message ? "<br /><br /><br /><br /><div align=\"center\">'".$error_message."'</div>\n" : 
'<script language="JavaScript">
	window.opener.frmImagePick.flag_unload_access.value = 1;
	window.opener.window.close();


window.close();
</script>
').'
</body>
';
print $content;
exit;
}


?>
<HTML>
<HEAD>
<TITLE><?=$lang["Insert_Picture"]?></TITLE>
<STYLE TYPE="text/css">
 BODY   {margin-left:10; font-family:Verdana; font-size:10pt; background:menu}
 BUTTON {width:9em}
 TABLE  {font-family:Verdana; font-size:10pt;}
 P      {text-align:center}
</STYLE>

<SCRIPT LANGUAGE=JavaScript>
<!--
var imgCaptions = new Array();
var imgHeights = new Array();
var imgWidths = new Array();
imgCaptions[0] = "";
imgHeights[0] = "0";
imgWidths[0] = "0";



function IsDigit()
{
	return ((event.keyCode >= 48) && (event.keyCode <= 57));
}

function showPreview()
{
	if(document.frmImagePick.ImgUrl.value != "")
	document.frmImagePick.BackupImgUrl.value = document.frmImagePick.ImgUrl.value;
	if (document.frmImagePick.BackupImgUrl.value != "")
	{
		document.PREVIEWPIC.src=document.frmImagePick.BackupImgUrl.value ;
		document.frmImagePick.ImgHeight.value = document.PREVIEWPIC.height;
		document.frmImagePick.ImgWidth.value = document.PREVIEWPIC.width;
	}
	else
	document.PREVIEWPIC.src='<?=$GLOBALS['http_path']?>system/imgpreview.gif';
}
// -->
</SCRIPT>

<SCRIPT LANGUAGE=JavaScript FOR=PREVIEWPIC EVENT=onreadystatechange>
<!--
if(readyState == "complete"){
	PREVIEWPIC.style.visibility = "visible";
	if(document.readyState == "complete"){
		document.frmImagePick.ImgHeight.value = document.PREVIEWPIC.height;
		document.frmImagePick.ImgWidth.value = document.PREVIEWPIC.width;
	}
}
//-->
</SCRIPT>


<SCRIPT LANGUAGE=JavaScript FOR=window EVENT=onload>
<!--
document.frmImagePick.script_uri.value = window.opener.bcwb_form.script_uri.value;
document.frmImagePick.BackupImgUrl.value = window.opener.bcwb_form.arg_ImgUrl.value.replace("file:///", "");
showPreview();
document.frmImagePick.AltText.value = window.opener.bcwb_form.arg_AltText.value;
document.frmImagePick.ImgBorder.value = window.opener.bcwb_form.arg_ImgBorder.value;
document.frmImagePick.HorSpace.value = window.opener.bcwb_form.arg_HorSpace.value;
document.frmImagePick.VerSpace.value = window.opener.bcwb_form.arg_VerSpace.value;
document.frmImagePick.ImgHeight.value = window.opener.bcwb_form.arg_ImgHeight.value;
document.frmImagePick.ImgWidth.value = window.opener.bcwb_form.arg_ImgWidth.value;
for(i=1;i<(document.frmImagePick.ImgAlign.length-1);i++)
if(document.frmImagePick.ImgAlign[i].value==window.opener.bcwb_form.arg_ImgAlign.value);
document.frmImagePick.ImgAlign.selectedIndex = i;

// -->
</SCRIPT>



<SCRIPT LANGUAGE=JavaScript>
<!--

function FrameUnload() {

	if(frmImagePick.flag_unload_access.value==0) return false;

	var arr = new Array();

	if (document.PREVIEWPIC.src=='<?=$GLOBALS['http_path']?>system/imgpreview.gif')
	{
		alert('You did not select a picture. Page not updated.');
		arr=null;
	}
	else
	{
		var arg_AltText = document.frmImagePick.AltText.value;
		var arg_HorSpace = document.frmImagePick.HorSpace.value;
		var arg_VerSpace = document.frmImagePick.VerSpace.value;
		var arg_ImgUrl = false;
		
		
		if (document.frmImagePick.ImgUrl.value != "")
			arg_ImgUrl = 'file:///' + document.frmImagePick.ImgUrl.value;
		else
		{
			if( document.frmImagePick.BackupImgUrl.value.substr(0,5) == 'http:' )
				arg_ImgUrl = document.frmImagePick.BackupImgUrl.value;
			else
				arg_ImgUrl = 'file:///' + document.frmImagePick.BackupImgUrl.value;
		}
		
		var arg_ImgUrl = arg_ImgUrl.split("\\").join("/");
		var arg_ImgBorder = document.frmImagePick.ImgBorder.value;
		var arg_ImgAlign = document.frmImagePick.ImgAlign[document.frmImagePick.ImgAlign.selectedIndex].value;
		var arg_ImgHeight = document.frmImagePick.ImgHeight.value;
		var arg_ImgWidth = document.frmImagePick.ImgWidth.value;
	
		var arr = arg_ImgUrl.split("\/");
		var fpref = document.frmImagePick.script_uri.value;
		var imgfname = fpref.split("\/").join("_")+arr[arr.length-1];
		
		if(document.frmImagePick.flag_icon.checked)
			var output = unescape("%3C")+'a onclick="window.open(\'<?=$http_path?>preview_image.php?filename=' + imgfname + '\', \'w\', \'status=yes,toolbar=no,menubar=no,resizable=yes\')" style="cursor: pointer"'+unescape("%3E")+unescape("%3C")+'img src="' + 
			'<?=$http_path?>dcontent/icon_' + imgfname + '"  alt="' +arg_AltText + '" vspace="' + arg_VerSpace + '" hspace="' + arg_HorSpace +  '" align="' + arg_ImgAlign +'" border="' + arg_ImgBorder + '" /'+unescape("%3E")+unescape("%3C")+'/a'+unescape("%3E");   
		else
			var output = unescape("%3C")+'img src="<?=$http_path?>dcontent/' + imgfname + '"  alt="' +arg_AltText + '" vspace="' + arg_VerSpace + '" hspace="' + arg_HorSpace +  '" align="' + arg_ImgAlign +'" border="' + arg_ImgBorder + '" /'+unescape("%3E");   
		
		window.opener.frames.area_<?=$_GET["tag"]?>.focus();
		var mysel = window.opener.frames.area_<?=$_GET["tag"]?>.document.selection;
		if( mysel.type == "Control")  mysel.clear(); 
		mysel.createRange().pasteHTML( output );
		window.opener.frames.area_<?=$_GET["tag"]?>.focus();
	  }

   window.close();
}
//  window.close();
// -->
</SCRIPT>

</HEAD>

<BODY onUnload="FrameUnload()">
<form method="post" target="_blank" name="frmImagePick" id="frmImagePick" encType="multipart/form-data" style="padding-top: 0px; padding-bottom: 0px;">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
<input type="hidden" name="BackupImgUrl" value="0">
<input type="hidden" name="flag_unload_access" ID="flag_unload_access" value="0">
<input type="hidden" name="tag" value="<?=$_GET["tag"]?>">
<input type="hidden" name="script_uri" value="">

	<TABLE CELLSPACING=2 border="0">
	<TR>
		<TD VALIGN="top" align="left" nowrap>
			<div align="center">
			<span style="background-color:gray;overflow:auto;width:400px;height:200px;border-width:1px; border-style:solid;border-color:threeddarkshadow white white threeddarkshadow;">
			<IMG ID="PREVIEWPIC" NAME="PREVIEWPIC" bgcolor="#ffffff" src="<?=$GLOBALS["http_path"]?>system/imgpreview.gif" alt="Preview" align="absmiddle" valign="middle"></span>
	</div>
	</TD>
	</TR>
	<TR>
	<TD VALIGN="top" align="left" colspan="2" nowrap>
	<input type="file" name="ImgUrl" size="40" style="width : 300px;" value="" onChange="showPreview();">
	&nbsp;
	<BUTTON ONCLICK="showPreview();"><?=$lang["Preview"]?></BUTTON>
	<br><?=$lang["Alternate_Text"]?>:<br>
	<INPUT TYPE=TEXT SIZE=40 NAME=AltText style="width : 300px;">
	</TD>
	</TR>
	
	<TR>
		<TD VALIGN="top" align="left" colspan="2">
			<table border=0 cellpadding=2 cellspacing=2>
			<tr>
			<td nowrap>
			<fieldset style="padding : 2px;"><legend> <?=$lang["Layout"]?> </legend>
			<table border=0 cellpadding=2 cellspacing=2>
			<tr>
			<td><?=$lang["Alignment"]?>:</td>
			<td><select NAME=ImgAlign style="width : 80px;">
			<option value=""></option>
			<option value="left"><?=$lang["Left"]?></option>
			<option value="right"><?=$lang["Right"]?></option>
			<option value="top"><?=$lang["Top"]?></option>
			<option value="middle"><?=$lang["Middle"]?></option>
			<option value="bottom"><?=$lang["Bottom"]?></option>
			<option value=""></option>
			</select>
			</td>
			</tr>
			<tr>
			<td nowrap><?=$lang["Border_Thickness"]?>:</td>
			<td><INPUT TYPE=TEXT SIZE=2 value="0" NAME=ImgBorder  ONKEYPRESS="event.returnValue=IsDigit();" style="width : 80px;"></td>
			</tr>
			</table>
			</fieldset>
			</td>
			<td nowrap>
			<fieldset style="padding : 5px;"><legend> <?=$lang["Spacing"]?> </legend>
			<table border=0 cellpadding=2 cellspacing=1>
			<tr>
			<td><?=$lang["Horizontal"]?>:</td>
			<td><INPUT TYPE=TEXT SIZE=2 value="0" NAME=HorSpace  ONKEYPRESS="event.returnValue=IsDigit();" style="width : 80px;"> </td>
			</tr>
			<tr>
			<td><?=$lang["Vertical"]?>:</td>
			<td><INPUT TYPE=TEXT SIZE=2 value="0" NAME=VerSpace  ONKEYPRESS="event.returnValue=IsDigit();" style="width : 80px;"></td>
			</tr>
			</table>
			</fieldset>
			</td>
			</tr>
			<tr><td colspan="2">
			
			
			
			
		
		<fieldset ><legend> <?=$lang["Icon"]?> </legend>
		<table width="100%" border=0 cellpadding=2 cellspacing=1><tr><td valign="top">
		<INPUT type="checkbox" <? print ( sysB_chkgd2() ? '': 'disabled') ?> name="flag_icon" value="1" onclick="if(this.checked) icon.style.display='block'; else  icon.style.display='none';" /> <?=$lang["CreateIcon"]?>
		</td>
		<td>
			<table border=0 cellpadding=2 cellspacing=1 ID="icon" style="display: none">
			<tr>
			<td><?=$lang["Width"]?>:</td>
			<td><INPUT TYPE=TEXT SIZE=2 value="0" NAME="icon_width" ONKEYPRESS="event.returnValue=IsDigit();" style="width : 80px;"> </td>
			</tr>
			<tr>
			<td><?=$lang["Height"]?>:</td>
			<td><INPUT TYPE=TEXT SIZE=2 value="0" NAME="icon_height"  ONKEYPRESS="event.returnValue=IsDigit();" style="width : 80px;"></td>
			</tr>
			</table>
		</td></tr></table>
		</fieldset>
			
			
			
			
			
			</td></tr>
			</table>
		
	
	
	</TD>
	</TR>
	</TABLE>
	
	
	
	<p align=center>
	<BUTTON ID=Ok ONCLICK="this.form.submit()">   OK   </BUTTON>
	&nbsp;
	<BUTTON ONCLICK="window.close();"><?=$lang["Cancel"]?></BUTTON>
	</p>
	<INPUT TYPE=HIDDEN SIZE=5 value="0" NAME=ImgHeight>
	<INPUT TYPE=HIDDEN SIZE=5 value="0" NAME=ImgWidth>
	</FORM>
	</BODY>
	</HTML>
