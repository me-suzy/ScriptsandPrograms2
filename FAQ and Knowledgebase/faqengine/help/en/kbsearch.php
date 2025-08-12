<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../../config.php');
require_once('../../functions.php');
include_once('../../includes/get_settings.inc');
if(!isset($lang) || !$lang)
	$act_lang=$default_lang;
else
	$act_lang=$lang;
if(!language_avail($act_lang,"../../language"))
	die ("Language <b>$act_lang</b> not configured");
include('../../language/lang_'.$act_lang.'.php');
$closepic="../../".$closepic;
$TableWidth="95%";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
include_once('../../includes/styles.inc');
?>
<title>FAQEngine - Search - Help</title>
</head>
<body onload="top.window.focus()" bgcolor="<?php echo $row_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<table width="<?php echo $TableWidth?>" align="center">
<tr bgcolor="<?php echo $heading_bgcolor?>"><td width="98%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
KB search help</span>
</td>
<td align="center" valign="middle" width="2%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" alt="<?php echo $l_close?>"></a></span></td></tr>
</tr>
<TR bgcolor="<?php echo $row_bgcolor?>"><TD>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
This form provides You with the ability to search the KB.<br>
You can specify which words the document you search <i>must</i>, <i>can</i> or <i>must not</i> contain.<br>
Use the following special characters for this:<ul>
<li>prepending the word with <b>+</b>, will find all documents containing all words marked this way.</li>
<li>prepending the word with <b>-</b>, will find all documents <i>not</i> containing any words marked this way.</li>
<li>with no character in front of the word, all documents containing at least one of these words will be found</li>
</ul>
example:<br>
+Donald +Duck -Mikey Goofy<br>
returns all documents containing Donald <i>and</i> Duck and <i>not</i> Mikey and may contain Goofy.
<tr bgcolor="<?php echo $heading_bgcolor?>"><td>&nbsp;</td>
<td align="center" valign="middle" width="2%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" alt="<?php echo $l_close?>"></a></span></td></tr>
</tr></table>
</body>
</html>