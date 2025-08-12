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
<title>SimpNews - Search - Help</title>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>">
<table width="<?php echo $TableWidth?>" align="<?php echo $tblalign?>" class="sntable">
<tr bgcolor="<?php echo $headingbgcolor?>"><td>
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<b>search help</b></font></td></tr>
<tr bgcolor="<?php echo $pagebgcolor?>">
<td>
<table border=0 cellpadding=0 cellspacing=0 width="100%" align="CENTER"><TR><td bgcolor="<?php echo $bordercolor?>">
<table border=0 cellpadding=4 cellspacing=1 width="100%">
<TR bgcolor="<?php echo $contentbgcolor?>"><TD>
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
This form provides You with the ability to search news entries. You can specify which words the document you search
<i>must</i>, <i>can</i> or <i>must not</i> contain.<br>
Use the following special characters for this:<ul>
<li>prepending the word with <b>+</b>, will find all documents containing all words marked this way.</li>
<li>prepending the word with <b>-</b>, will find all documents <i>not</i> containing any words marked this way.</li>
<li>with no character in front of the word, all documents containing at least one of these words will be found</li>
</ul>
example:<br>
+Donald +Duck -Mikey Goofy<br>
returns all documents containing Donald <i>and</i> Duck and <i>not</i> Mikey and may contain Goofy.
</font></td></tr>
</table>
</td></tr></table></td></tr></table>
</body>
</html>