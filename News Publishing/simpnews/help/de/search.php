<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../../config.php');
require_once('../../functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('../../language/lang_'.$act_lang.'.php');
include_once('../../includes/get_settings.inc');
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
else
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
?>
<title>SimpNews - Suche - Hilfe</title>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>">
<table width="<?php echo $TableWidth?>" align="<?php echo $tblalign?>" class="sntable">
<tr bgcolor="<?php echo $headingbgcolor?>"><td>
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<b>Hilfe zur Suchfunktion</b></font></td></tr>
<tr bgcolor="<?php echo $pagebgcolor?>">
<td>
<table border=0 cellpadding=0 cellspacing=0 width="100%" align="CENTER"><TR><td bgcolor="<?php echo $bordercolor?>">
<table border=0 cellpadding=4 cellspacing=1 width="100%">
<TR bgcolor="<?php echo $contentbgcolor?>"><TD>
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
Diese Formular bietet Ihnen eine einfacher zu bedienende Suchfunktion. Sie k&ouml;nnen hier angeben,
welche Worte das zu suchende Dokument enthalten <i>muss</i>, <i>kann</i> und <i>nicht enthalten</i> darf.<br>
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
</font></td></tr>
</table>
</td></tr></table></td></tr></table>
</body>
</html>