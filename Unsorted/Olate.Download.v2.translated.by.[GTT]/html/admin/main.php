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
$admin = 1;
require_once('../includes/init.php');   

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
<link rel="stylesheet" type="text/css" href="../css/style.css" title="default" />
</head>

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
<td width="71%"><strong><?= $language['title_admin_main'] ?></strong></td>
<td width="29%"><div align="right"><font size="1" face="Arial, Helvetica, sans-serif"><strong><?= $language['description_loggedinas'].' '.$_SESSION['admin_username']; ?>. <a href="logout.php"><?= $language['link_logout'] ?></a>.</strong></font></div></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p><?= $language['description_main'] ?></p>
<table width="100%"  border="0" cellspacing="2" cellpadding="0">
<tr valign="top">
<td width="33%"><p><strong><?= $language['title_downloads'] ?></strong></p>
<ul>
<li><a href="downloads/add.php"><?= $language['link_adddownload'] ?></a></li>
<li><a href="downloads/edit.php"><?= $language['link_editdownload'] ?></a></li>
<li><a href="downloads/delete.php"><?= $language['link_deletedownload'] ?></a></li>
</ul></td>
<td width="33%"><p><strong><?= $language['title_categories'] ?></strong></p>
<ul>
<li><a href="categories/add.php"><?= $language['link_addcategory'] ?></a> </li>
<li><a href="categories/edit.php"><?= $language['link_editcategory'] ?></a></li>
<li><a href="categories/delete.php"><?= $language['link_deletecategory'] ?></a></li>
</ul></td>
<td width="33%"><p><strong><?= $language['title_users'] ?></strong></p>
<ul>
<li><a href="users/add.php"><?= $language['link_adduser'] ?></a></li>
<li><a href="users/delete.php"><?= $language['link_deleteuser'] ?></a></li>
</ul></td>
</tr>
<tr valign="top">
<td width="33%"><p><strong><?= $language['title_configuration'] ?></strong></p>
<ul>
<li><a href="config/general.php"><?= $language['link_generalsettings'] ?></a></li>
<li><a href="config/languages.php"><?= $language['link_languages'] ?></a></li>
</ul></td>
<td width="33%"><p><strong><?= $language['title_other'] ?></strong></p>
<ul>
<li><a href="other/update.php"><?= $language['link_updates'] ?></a></li>
<li><a href="other/support.php"><?= $language['link_support'] ?></a></li>
<li><a href="other/license.php"><?= $language['link_license'] ?></a> </li>
</ul></td>
<td width="33%"><p>&nbsp;</p></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td height="25" valign="middle" bordercolor="#FFFFFF" bgcolor="#E3E8EF"><?php
// Include Credits - REMOVAL WILL VOID LICENSE
require('../includes/credits.php');
?></td>
</tr>
  </table>
  <hr width="1" size="1" color="#FFFFFF"></td>
  </tr>
</table>
</body>
</html>