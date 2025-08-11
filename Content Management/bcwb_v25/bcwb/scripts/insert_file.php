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

include_once("../config.inc.php");
$root_path = preg_replace("/scripts\/insert_file\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
include_once('../lang/'.$default_language.'.inc.php');

/**
* @desc Save POST data
*/
if($_POST)
{

	$dcontentpath=$root_path."dcontent/";
	$dcontentpath_pref=$root_path."dcontent/";
	$filedname="FileUrl"; 
	$filebasename=$HTTP_POST_FILES[$filedname]['name'];
	
	if( preg_match("/\.php$/is", $filebasename) ) $filebasename = $filebasename.".txt";
	if( preg_match("/\.phtml$/is", $filebasename) ) $filebasename = $filebasename.".txt";
	if( preg_match("/\.php3$/is", $filebasename) ) $filebasename = $filebasename.".txt";


	if($HTTP_POST_FILES[$filedname]['tmp_name']!="" && $HTTP_POST_FILES[$filedname]['tmp_name']!="none")
	{
		if(!move_uploaded_file($HTTP_POST_FILES[$filedname]['tmp_name'], $dcontentpath_pref.$filebasename)) $error_message=$lang["Can_not_save_uploaded_file"];
	}

$content = '<body>
'.( $error_message ? "<br /><br /><br /><br /><div align=\"center\">'".$error_message."'</div>\n" : 
'<script language="JavaScript">
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
<TITLE><?=$lang["Insert_File"]?></TITLE>
<STYLE TYPE="text/css">
 BODY   {margin-left:10; font-family:Verdana; font-size:10pt; background:menu}
 BUTTON {width:9em}
 TABLE  {font-family:Verdana; font-size:10pt;}
 P      {text-align:center}
</STYLE>

<SCRIPT LANGUAGE=JavaScript FOR=window EVENT=onload>
<!--
var mysel = window.opener.frames.area_<?=$_GET["tag"]?>.document.selection;
document.frmFilePick.FileDesc.value = mysel.createRange().text;
// -->
</SCRIPT>

<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
var mysel = window.opener.frames.area_<?=$_GET["tag"]?>.document.selection;
var imageString = "";
if(mysel.type == "Control")
{
    var oImg = mysel.createRange().item(0);
	imageString = '<img src="' + oImg.src + '"  alt="' + oImg.alt + '" height="'+ oImg.height + '" width="'+ oImg.width +'" vspace="' + oImg.vspace + '" hspace="' + oImg.hspace +  '" align="' + oImg.align +'" border="' + oImg.border+ '" />';
} 

if (document.frmFilePick.FileUrl.value=='none')
{
	alert('You did not select a file. Page not updated.');
}
else
{
	
  var pos;
  var newfilename;
  var output;
  	pos = document.frmFilePick.FileUrl.value.lastIndexOf('\\');
  	newfilename = '<?=$GLOBALS["http_path"]?>dcontent/' + document.frmFilePick.FileUrl.value.substr( pos+1 ); 
	if(imageString.length>0)
  		output = unescape("%3C")+'a href="' + newfilename + '" target="' + document.frmFilePick.FileTarget.value + '" '+unescape("%3E") + imageString +  unescape("%3C") +'/a'+unescape("%3E");   
  	else
  		output = unescape("%3C")+'a href="' + newfilename + '" target="' + document.frmFilePick.FileTarget.value + '" '+unescape("%3E") + document.frmFilePick.FileDesc.value +  unescape("%3C") +'/a'+unescape("%3E");   
	window.opener.frames.area_<?=$_GET["tag"]?>.focus();	
  	if( mysel.type == "Control")  mysel.clear(); 
  	mysel.createRange().pasteHTML( output );
  }

  document.frmFilePick.submit();
  window.close();
// -->
</SCRIPT>

</HEAD>

<BODY>
<form method="post" name="frmFilePick" id="frmFilePick" encType="multipart/form-data" style="padding-top: 0px; padding-bottom: 0px;">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
<input type="hidden" name="flag_unload_access" value="">
<input type="hidden" name="tag" value="<?=$_GET["tag"]?>">

	<TABLE CELLSPACING=2 border="0">
		<TR>
			<TD VALIGN="top" align="left" colspan="2" nowrap>
				<input type="file" name="FileUrl" size="40" style="width : 300px;" value="">
				&nbsp;
				<br /><?=$lang["Link"]?>:<br />
				<INPUT TYPE="TEXT" SIZE=40 NAME="FileDesc" style="width : 300px;">
			</TD>
		</TR>
	<TR>
		<TD VALIGN="top" align="left" colspan="2">
			<fieldset style="padding : 2px;"><legend> <?=$lang["Show"]?> </legend>
				<table border=0 cellpadding=2 cellspacing=2>
					<tr>
						
						<td><select NAME=FileTarget style="width : 250px;">
							<option value=""></option>
							<option value="_blank">_blank</option>
							<option value="_top">_top</option>
							<option value="_self">_self</option>
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	</table>
	</TD>
	</TR>
	</TABLE>
	<p align=center>
	<BUTTON ID=Ok>   OK   </BUTTON>
	&nbsp;
	<BUTTON ONCLICK="window.close();"><?=$lang["Cancel"]?></BUTTON>
	</p>
	</FORM>
	</BODY>
	</HTML>
	