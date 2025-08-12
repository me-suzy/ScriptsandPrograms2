<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Head-Bereich AdminCenter
|	> $Id: head.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","head.php");

include_once('adminfunc.inc.php');
$auth->checkEnginePerm("canaccessadmincent");
$message = "";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>News Engine - Admincenter</title>
<style>
BODY {
	font-family : Verdana, Arial, sans-serif;
	font-size : 11px;
  	SCROLLBAR-BASE-COLOR: #4665B5;
  	SCROLLBAR-ARROW-COLOR: White;    
}

A, A:ACTIVE {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 11px;
	color : Black;
	text-decoration : underline;
}
A:HOVER {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 11px;
	color : #FF3300;
	text-decoration : none;
}
</style>
</head>
<body bgcolor="#C0C0C0">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="top" style="padding-top: 5px;"><b><?php printf($a_lang['head_logged_in_as'],$auth->user['username']) ?></b> [<a href="<?php echo $config['engine_mainurl'] ?>/misc.php?action=logout" target="_parent"><?php echo $a_lang['head_logout'] ?></a>]</td>
    <td align="right"><img src="images/ac_logo.gif" alt="" width="255" height="35" border="0" /></td>
</tr>
</table>
</body>
</html>