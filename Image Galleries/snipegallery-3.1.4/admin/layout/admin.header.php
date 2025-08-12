<?php
/**
* Admin Header
*
* The main admin interface header layout file.  Editing
* this file is not recommended unless you know what you're
* doing :)  Although it mostly contains basic HTML table layout
* information, it also pulls in the subnav file
* so that the correct sub-navigation items are displayed
* depending on what section you're in.
* 
* @package	admin
* @author	A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd"> 
<html>
<head>
<title>Snipe Gallery <?php echo $cfg_program_version ?> <?php if (!empty($PAGE_TITLE)) echo " - ". $PAGE_TITLE; ?></title>
<HTML lang="<?php echo $cfg_use_langfile; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if (isset($cfg_admin_url)) { ?>
<link rel="stylesheet" href="<?php echo $cfg_admin_url; ?>/layout/admin-style.css" type="text/css">
<?php } else { ?>
<link rel="stylesheet" href="layout/admin-style.css" type="text/css">
<?php } ?>

<script language="JavaScript" type="text/javascript" src="<?php echo $cfg_admin_url; ?>/js/gallery-admin.js">
</script>

<script language="JavaScript" type="text/javascript" src="<?php echo $cfg_admin_url; ?>/js/wz_dragdrop.js">
</script>

</head>

<body>

<center>
<table border="0" cellspacing="0" cellpadding="0" class="mainadmin" width="780">
<tr>
	<td><a href="<?php echo $cfg_admin_url; ?>"><img src="<?php echo $cfg_admin_url; ?>/images/header.gif" width="780" height="74" border="0" alt=""></a></td>
</tr>
<tr>
	<td class="adminheader">		
		<span class="subheading"><?php echo $LANG_NAV_ADMIN; ?> <?php if (!empty($PAGE_TITLE)) echo " - ". $PAGE_TITLE; ?></span>
	</td>
</tr>
<?php if ($GALLERY_SECTION != "install") { ?>
<tr>
	<td>
	<table border="0" cellspacing="1" cellpadding="0" width="100%">
	<tr>
		<td class="topnav" onclick="javascript:document.location.href='<?php echo $cfg_admin_url; ?>/gallery/'" onmouseover="this.style.background='#006A9D'" onmouseout="this.style.background='#6699CC'" style="cursor: pointer"><?php echo $LANG_NAV_IMAGES; ?></td>
		<td class="topnav" onclick="javascript:document.location.href='<?php echo $cfg_admin_url; ?>/frames/'" onmouseover="this.style.background='#006A9D'" onmouseout="this.style.background='#6699CC'" style="cursor: pointer"><?php echo $LANG_NAV_FRAMES; ?></td>
		<td class="topnav" onclick="javascript:document.location.href='<?php echo $cfg_admin_url; ?>/import/'" onmouseover="this.style.background='#006A9D'" onmouseout="this.style.background='#6699CC'" style="cursor: pointer"><?php echo $LANG_NAV_IMPORT; ?></td>
		<td class="topnav" onclick="javascript:document.location.href='<?php echo $cfg_admin_url; ?>/settings/'" onmouseover="this.style.background='#006A9D'" onmouseout="this.style.background='#6699CC'" style="cursor: pointer"><?php echo $LANG_NAV_SETTINGS; ?></td>
		<td class="topnav" onclick="javascript:document.location.href='<?php echo $cfg_admin_url; ?>/faq/'" onmouseover="this.style.background='#006A9D'" onmouseout="this.style.background='#6699CC'" style="cursor: pointer"><?php echo $LANG_NAV_FAQ; ?></td>		
	</tr>
	</table>
	<?php include ("admin.subnav.php"); ?>
	
	</td>
</tr>
<?php } ?>
<tr>
	<td class="adminbody">
	