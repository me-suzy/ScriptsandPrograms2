<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

// Start script
$admin = 2;
require_once('../../includes/init.php');  

// Start session
session_start();

// Function: Make sure user has logged in
admin_authenticate($config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Olate Download - <?= $language['title_admin'] ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="../../css/style.css" title="default" />

</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top"><hr width="1" size="1" color="#FFFFFF">
<table class="admin_main" align="center" border="1">
<tr>
<td class="admin_title"><table class="admin_title_table">
<tr>
<td class="admin_title"><strong>Olate Download - <?= $language['title_script'] ?></strong></td>
</tr>
</table></td>
</tr>
<tr>
<td class="admin_breadcrumb">
<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="71%"><strong><a href="<?= $config['urlpath']; ?>/admin/main.php"><?= $language['link_administration']; ?></a><?= $language['title_config_general']; ?></strong></td>
<td width="29%"><div align="right"><font size="1" face="Arial, Helvetica, sans-serif"><strong><?= $language['description_loggedinas'].' '.$_SESSION['admin_username']; ?>. <a href="<?= $config['urlpath']; ?>/admin/logout.php"><?= $language['link_logout']; ?></a>.</strong></font></div></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p><?= $language['description_config_general']; ?></p>
<form action="general_process.php" method="post" name="config" id="config">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="30" valign="middle"><?= $language['title_config_general_version']; ?></td>
<td height="30" valign="middle"><?= $config['version']; ?></td>
</tr>
<tr>
<td width="25%" height="30" valign="middle"><?= $language['title_config_general_path']; ?></td>
<td width="75%" height="30" valign="middle"><input name="urlpath" type="text" id="path" value="<?= $config['urlpath']; ?>" size="50"> 
<em><?= $language['description_config_general_noslash']; ?></em></td>
</tr>
<tr valign="middle">
<td height="30"><?= $language['title_config_general_displaytd']; ?></td>
<td height="30">
<?php
if ($config['topdownloadslink'] == 1) {
	echo '<input name="topdownloadslink" type="checkbox" value="1" checked>';
} else {
	echo '<input name="topdownloadslink" type="checkbox" value="1">';
}
?></td>
</tr>
<tr valign="middle">
  <td height="30"><?= $language['title_config_general_alldownloads']; ?></td>
  <td height="30"><?php
if ($config['alldownloads'] == 1) {
	echo '<input name="alldownloads" type="checkbox" value="1" checked>';
} else {
	echo '<input name="alldownloads" type="checkbox" value="1">';
}
?></td>
</tr>
<tr valign="middle">
	<td height="30"><?= $language['title_config_general_searchlink']; ?></td>
	<td height="30"><?php
if ($config['searchlink'] == 1) {
	echo '<input name="searchlink" type="checkbox" value="1" checked>';
} else {
	echo '<input name="searchlink" type="checkbox" value="1">';
}
?></td>
</tr>
<tr valign="middle">
  <td height="30"><?= $language['title_config_general_sorting']; ?></td>
  <td height="30"><?php
if ($config['sorting'] == 1) {
	echo '<input name="sorting" type="checkbox" value="1" checked>';
} else {
	echo '<input name="sorting" type="checkbox" value="1">';
}
?></td>
</tr>
<tr valign="middle">
  <td height="30"><?= $language['title_config_general_ratings']; ?></td>
  <td height="30"><?php
if ($config['ratings'] == 1) {
	echo '<input name="ratings" type="checkbox" value="1" checked>';
} else {
	echo '<input name="ratings" type="checkbox" value="1">';
}
?></td>
</tr>
<tr valign="middle">
<td height="30"><?= $language['title_config_general_numbertd']; ?></td>
<td height="30"><input name="notopdownloads" type="text" id="notopdownloads" value="<?= $config['notopdownloads']; ?>" size="3"></td>
</tr>
<tr valign="middle">
<td height="30"><?=$language['title_config_general_numberpage']; ?></td>
<td height="30"><input name="pages" type="text" id="pages" value="<?= $config['pages']; ?>" size="3"></td>
</tr>
</table>
<p><em><?= $language['description_config_colours']; ?></em></p>
<p>
<input type="submit" name="Submit" value="<?= $language['button_update']; ?>">
</p>
</form></td>
</tr>
</table></td>
</tr>
<tr>
<td height="25" valign="middle" bordercolor="#FFFFFF" bgcolor="#E3E8EF"><?php
// Include Credits - REMOVAL WILL VOID LICENSE
require('../../includes/credits.php');
?></td>
</tr>
</table>
  <hr width="1" size="1" color="#FFFFFF"></td>
</tr>
</table>
</body>
</html>