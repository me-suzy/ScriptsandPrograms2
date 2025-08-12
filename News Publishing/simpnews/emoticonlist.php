<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?> <?php echo $copyright_asc?>">
<meta name="fid" content="<?php echo $fid?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_emoticonlist?></title>
<?php
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
?>
<script language='javascript'>
function chooseemoticon(code)
{
	mywin=parent.window.opener;
	addText = " "+code+" ";
	mywin.document.inputform.<?php echo $inputfield?>.value+=addText;
	parent.window.focus();
	top.window.close();
	mywin.document.inputform.<?php echo $inputfield?>.focus();
	return;
}
</SCRIPT>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>" <?php echo $addbodytags?>>
<?php
	$sql = "select * from ".$tableprefix."_emoticons";
	if(!$result = mysql_query($sql, $db))
	   	die("Could not connect to the database.");
?>
<table width="98%" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_emoticonlist?></b></font></td>
</td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	if(!$myrow=mysql_fetch_array($result))
	{
?>
<tr BGCOLOR="<?php echo $tablebgcolor?>" align="center">
<td align="left" valign="middle" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_noneavailable?></font></td></tr>
<?php
	}
	else
	{
?>
<tr BGCOLOR="<?php echo $headingbgcolor?>" align="center">
<td align="center" valign="middle" width="2%">&nbsp;</td>
<td class="rowheadings" align="center" valign="middle" width="20%">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_code?></b></font></td>
<td class="rowheadings" align="center" valign="middle">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_emotion?></b></font></td>
<?php
		do{
?>
<tr BGCOLOR="<?php echo $tablebgcolor?>"  align="center">
<td align="center" valign="middle" width="2%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<a class="listlink" href="javascript:chooseemoticon('<?php echo " ".stripslashes($myrow["code"])." "?>')"><img src="<?php echo "$url_emoticons/".stripslashes($myrow["emoticon_url"])?>" border="0"></a></font></td>
<td align="center" valign="middle" width="20%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo do_htmlentities(stripslashes($myrow["code"]))?></font></td>
<td align="center" valign="middle">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo do_htmlentities(stripslashes($myrow["emotion"]))?></font></td></tr>
<?php
		}while($myrow=mysql_fetch_array($result));
	}
?>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER"><td>&nbsp;</td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr></table>
</body></html>
