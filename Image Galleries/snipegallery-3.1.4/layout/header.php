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
<title>Snipe Gallery v.3 <?php if (!empty($PAGE_TITLE)) echo " - ". $PAGE_TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if (isset($cfg_admin_url)) { ?>
<link rel="stylesheet" href="<?php echo $cfg_app_url; ?>/layout/style.css" type="text/css">
<?php } else { ?>
<link rel="stylesheet" href="layout/admin-style.css" type="text/css">
<?php } ?>

</head>

<body>

<center>
<table border="0" cellspacing="0" cellpadding="0" class="mainadmin" width="780">
<tr>
	<td class="adminheader">
		<span class="heading">Snipe Gallery <?php echo $cfg_program_version ?> <?php if (!empty($PAGE_TITLE)) echo " - ". $PAGE_TITLE; ?></span>
	</td>
</tr>
<tr>
	<td>
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="subnav">
	<tr>
		<td>
		&#187; <b><a href="<?php echo $cfg_app_url; ?>/search.php" class="subnav">Search</a></b> &nbsp;&nbsp;&nbsp;&nbsp;&#187; <b><a href="<?php echo $cfg_app_url; ?>/" class="subnav">Gallery List</a></b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
			if (!empty($_REQUEST['gallery_id'])) {
				echo '&#187;  <a href="'.$cfg_app_url.'/view.php?gallery_id='.$_REQUEST['gallery_id'].'&page='.$_REQUEST['page'].'" class="subnav">Images in: '.stripslashes($this_catname).'</a>';
			}

		?>
		</td></tr></table>
	
	</td>
</tr>
<tr>
	<td class="adminbody">
	