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
include_once('../../language/lang_'.$act_lang.'.php');
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
<title>FAQEngine - Suche - Hilfe</title>
</head>
<body onload="top.window.focus()" bgcolor="<?php echo $row_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<table width="<?php echo $TableWidth?>" align="center">
<tr bgcolor="<?php echo $heading_bgcolor?>"><td width="98%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
Hilfe zur Suchfunktion</span>
</td>
<td align="center" valign="middle" width="2%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" alt="<?php echo $l_close?>"></a></span></td></tr>
</tr>
<TR bgcolor="<?php echo $row_bgcolor?>"><TD>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
Diese Formular bietet Ihnen die M&ouml;glichkeit innerhalb der Knowledge Base zu suchen.<br>
Sie k&ouml;nnen hier angeben, welche Worte das zu suchende Dokument enthalten <i>muss</i>, <i>kann</i> und <i>nicht enthalten</i> darf.<br>
Dazu benutzen Sie folgende Sonderzeichen:<ul>
<li>stellen Sie vor das Wort ein <b>+</b>, so werden nur Dokumente angezeigt, die dieses Wort enthalten. Geben
Sie mehrere solch gekennzeichnete Worte an, so werden nur die Dokumente angezeigt, die <i>alle</i> diese Worte enthalten.</li>
<li>stellen Sie vor das Wort ein <b>-</b>, so werden nur Dokumente angezeigt, die dieses Wort nicht enthalten. Geben
Sie mehrere solch gekennzeichnete Worte an, so werden nur Dokumente angezeigt, die <i>keines</i> dieser Worte enthalen.</li>
<li>stellen Sie kein Kennzeichen vor das Wort, so bedeutet dies, dass die zu suchenden Dokumente das Wort enthalten sollen. Geben
Sie mehrere solch gekennzeichnete Worte an, so werden die Dokumente angezeigt, die <i>mind. eins</i> dieser Worte enthalten.</li>
</ul>
Beispiel:<br>
+Donald +Duck -Mikey Goofy<br>
gibt alle Dokumente aus, die Donald <i>und</i> Duck, aber <i>nicht</i> Mickey und evtl. noch Goofy enthalten.
<tr bgcolor="<?php echo $heading_bgcolor?>"><td width="98%">&nbsp;</td>
<td align="center" valign="middle" width="2%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>:"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" alt="<?php echo $l_close?>"></a></span></td></tr>
</tr></table>
</body>
</html>