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
<td width="71%"><strong><a href="<?= $config['urlpath']; ?>/admin/main.php"><?= $language['link_administration']; ?></a><?= $language['title_downloads_add']; ?></strong></td>
<td width="29%"><div align="right"><font size="1" face="Arial, Helvetica, sans-serif"><strong><?= $language['description_loggedinas'].' '.$_SESSION['admin_username']; ?>. <a href="<?= $config['urlpath']; ?>/admin/logout.php"><?= $language['link_logout']; ?></a>.</strong></font></div></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p><?= $language['description_downloads_add']; ?></p>
<form action="add_process.php" method="post" name="add" id="add">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="18%"><?= $language['title_downloads_date']; ?></td>
<td width="82%"><input name="date" type="text" id="date" value="<?= date("d/m/y"); ?>" size="8" maxlength="8"></td>
</tr>
<tr>
<td width="18%"><?= $language['title_downloads_name']; ?></td>
<td><input name="name" type="text" id="name" size="35"></td>
</tr>
<tr>
<td><?= $language['title_downloads_location']; ?></td>
<td><input name="location" type="text" id="location" value="http://" size="60"></td>
</tr>
<tr>
<td><?= $language['title_downloads_size']; ?></td>
<td><input name="size" type="text" id="size" size="5"> 
<?= $language['description_downloads_mb']; ?>
</td>
</tr>
<tr>
<td><?= $language['title_downloads_category']; ?></td>
<td><select name="category" id="category">
<option selected><?= $language['description_downloads_categorysel']; ?></option>
<?php
// Function: Display the categories in the drop down menu
admin_downloads_add_catmenu();
?>
</select>&nbsp;</td>
</tr>
<tr>
<td><p><?= $language['title_downloads_description_b']; ?></p>
<p>&nbsp;</p>
<p>&nbsp;</p></td>
<td><textarea name="description_brief" cols="50" rows="5" id="description_brief"></textarea></td>
</tr>
<tr>
<td><p><?= $language['title_downloads_description_f']; ?></p>
<p>&nbsp;</p>
<p>&nbsp;</p></td>
<td><textarea name="description_full" cols="50" rows="5" id="description_full"></textarea></td>
</tr>
<tr>
  <td valign="top"><?= $language['title_downloads_custom1']; ?></td>
  <td><table width="100%" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="7%"><?= $language['title_downloads_custom_label']; ?></td>
      <td><input name="custom_1_l" type="text" id="custom_1_l4" size="9"></td>
    </tr>
    <tr>
      <td width="7%"><?= $language['title_downloads_custom_value']; ?></td>
      <td><input name="custom_1_v" type="text" id="custom_1_v2" size="35"></td>
    </tr>
  </table>    </td>
</tr>
<tr>
  <td valign="top"><?= $language['title_downloads_custom2']; ?></td>
  <td><table width="100%" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="7%"><?= $language['title_downloads_custom_label']; ?></td>
      <td><input name="custom_2_l" type="text" id="custom_1_l5" size="9"></td>
    </tr>
    <tr>
      <td width="7%"><?= $language['title_downloads_custom_value']; ?></td>
      <td><input name="custom_2_v" type="text" id="custom_1_v3" size="35"></td>
    </tr>
  </table></td>
</tr>
<tr>
<td><?= $language['title_downloads_image']; ?></td>
<td><input name="image" type="text" id="image" size="60"> 
<em><?= $language['description_downloads_noimg']; ?></em></td>
</tr>
</table>
<p>
<input type="submit" name="Submit" value="<?= $language['button_add']; ?>">
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