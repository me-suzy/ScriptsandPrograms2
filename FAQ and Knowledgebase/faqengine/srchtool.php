<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = mysql_fetch_array($result))
			echo undo_htmlspecialchars($myrow["text"]);
		else
			echo $l_oldbrowser;
		exit;
	}
}
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
else if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
?>
<script type="text/javascript" language="javascript">
function transferterms()
{
	var mywin=window.opener;
	var srchterm="";
	if(document.inputform.musts.value.length>0)
	{
		var mustsarray=document.inputform.musts.value.split(" ");
		for(i=0;i<mustsarray.length;i++)
		{
			if(srchterm.length>0)
				srchterm+=" ";
			srchterm+="+" + mustsarray[i];
		}
	}
	if(document.inputform.cans.value.length>0)
	{
		if(srchterm.length>0)
			srchterm+=" ";
		srchterm+=document.inputform.cans.value;
	}
	if(document.inputform.nots.value.length>0)
	{
		var notsarray=document.inputform.nots.value.split(" ");
		for(i=0;i<notsarray.length;i++)
		{
			if(srchterm.length>0)
				srchterm+=" ";
			srchterm+="-" + notsarray[i];
		}
	}
	mywin.searchform.<?php echo $inputfield?>.value=srchterm;
	window.close();
}
</script>
<title><?php echo $l_srch_tool?></title>
</head>
<body onload="top.window.focus()" bgcolor="<?php echo $row_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<form name="inputform" action="<?php echo $act_script_url?>" method="post">
<TR BGCOLOR="<?php echo $row_bgcolor?>">
<TD ALIGN="RIGHT" VALIGN="MIDDLE" width="30%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_srch_musts?>:</span></td>
<td align="left" valign="middle" width="70%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="text" name="musts" style="faqeinput" size="40" maxlength="255">
</span></td></tr>
<TR BGCOLOR="<?php echo $row_bgcolor?>">
<TD ALIGN="RIGHT" VALIGN="MIDDLE" width="30%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_srch_cans?>:</span></td>
<td align="left" valign="middle" width="70%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="text" name="cans" style="faqeinput" size="40" maxlength="255">
</span></td></tr>
<TR BGCOLOR="<?php echo $row_bgcolor?>">
<TD ALIGN="RIGHT" VALIGN="MIDDLE" width="30%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_srch_nots?>:</span></td>
<td align="left" valign="middle" width="70%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="text" name="nots" style="faqeinput" size="40" maxlength="255">
</span></td></tr>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="CENTER"><td colspan="2" align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<input type="button" onclick="transferterms()" class="faqebutton" value="<?php echo $l_transfer?>"></span>
</td></tr>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="CENTER">
<td align="right" valign="middle" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a>
</span></td></tr>
</form>
</table></td></tr></table></div>
</body></html>
