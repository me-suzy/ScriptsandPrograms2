<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title>FAQEngine - Administration</title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body onload="top.window.focus()">
<table width="100%">
<tr class="actionrow"><td>&nbsp;</td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
<tr class="info"><td align="left" colspan="2">
<?php
echo $$note;
?>
</td></tr>
</table>
</body>
</html>