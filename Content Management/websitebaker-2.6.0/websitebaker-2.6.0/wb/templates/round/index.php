<?php

// $Id: index.php 256 2005-11-28 08:33:29Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php page_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
<meta name="description" content="<?php page_description(); ?>" />
<meta name="keywords" content="<?php page_keywords(); ?>" />
<link href="<?php echo TEMPLATE_DIR; ?>/screen.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo TEMPLATE_DIR; ?>/print.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>

<table cellpadding="0" cellspacing="0" border="0" align="center" class="main" width="750">
<tr>
	<td colspan="2" class="header" height="80">
		<a href="<?php echo WB_URL; ?>"><img src="<?php echo TEMPLATE_DIR; ?>/banner.jpg" border="0" width="750" height="80" alt="<?php page_title('', '[WEBSITE_TITLE]'); ?>" /></a>
	</td>
</tr>
<tr>
	<?php
	// Only show menu items if we are supposed to
	if(SHOW_MENU) {
	?>	
	<td style="padding: 10px; background-color: #FFFFFF;" valign="top">
		<table cellpadding="0" cellspacing="0" border="0" width="150" align="center" class="menu">
		<tr>
			<td class="border">
				<img src="<?php echo TEMPLATE_DIR; ?>/menu_top.gif" border="0" alt="" />
			</td>
		</tr>
		<tr>
			<td width="170">
				<?php page_menu(); ?>
			</td>
		</tr>
		<tr>
			<td class="border">
				<img src="<?php echo TEMPLATE_DIR; ?>/menu_bottom.gif" border="0" alt="" />
			</td>
		</tr>
		</table>
		
		<?php if(SHOW_SEARCH) { ?>
		<form name="search" action="<?php echo WB_URL.'/search/index'.PAGE_EXTENSION; ?>" method="post">
			<table cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
				<tr>
					<td class="border">
						<img src="<?php echo TEMPLATE_DIR; ?>/menu_top.gif" border="0" alt="" />
					</td>
				</tr>
				<tr>
					<td class="login">
						<input type="text" name="string" />
					</td>
				</tr>
				<tr>
					<td class="login">

						<input type="submit" name="submit" value="<?php if(isset($TEXT['SUBMIT'])) { echo $TEXT['SEARCH']; } else { echo 'Search'; } ?>" />
					</td>
				</tr>
				<tr>
					<td class="border">
						<img src="<?php echo TEMPLATE_DIR; ?>/menu_bottom.gif" border="0" alt="" />
					</td>
				</tr>
			</table>
		</form>
		<?php } ?>
		
		<?php
		if(FRONTEND_LOGIN AND !$wb->is_authenticated()) {
		?>
		<form name="login" action="<?php echo LOGIN_URL; ?>" method="post">
			
			<table cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/menu_top.gif" border="0" alt="" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-transform: uppercase;">
					<b><?php echo $TEXT['LOGIN']; ?></b>
				</td>
			</tr>
			<tr>
				<td class="login" style="text-align: left;">
					<?php echo $TEXT['USERNAME']; ?>:
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="text" name="username" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-align: left;">
					<?php echo $TEXT['PASSWORD']; ?>:
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="password" name="password" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<a href="<?php echo FORGOT_URL; ?>"><?php echo $TEXT['FORGOT_DETAILS']; ?></a>
					<?php if(is_numeric(FRONTEND_SIGNUP)) { ?>
						<a href="<?php echo SIGNUP_URL; ?>"><?php echo $TEXT['SIGNUP']; ?></a>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/menu_bottom.gif" border="0" alt="" />
				</td>
			</tr>
			</table>
		
		</form>
		<?php
		} elseif(FRONTEND_LOGIN AND $wb->is_authenticated()) {
		?>
		<form name="logout" action="<?php echo LOGOUT_URL; ?>" method="post">
			
			<table cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/menu_top.gif" border="0" alt="" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-transform: uppercase;">
					<b><?php echo $TEXT['LOGGED_IN']; ?></b>
				</td>
			</tr>
			<tr>
				<td class="login" style="padding-top: 15px; padding-bottom: 15px;">
					<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?>
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="submit" name="submit" value="<?php echo $MENU['LOGOUT']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a>
				</td>
			</tr>
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/menu_bottom.gif" border="0" alt="" />
				</td>
			</tr>
			</table>
		
		</form>
		<?php
		}
		?>
	</td>
	<?php } ?>
	<td class="content" width="600" rowspan="2">
		<?php page_content(); ?>
	</td>
</tr>
<tr>
	<?php
	// Only show menu items if we are supposed to
	if(defined('SHOW_MENU') AND SHOW_MENU == true) {
	?>	
	<td height="20" width="155" valign="bottom" class="powered_by">
		<a href="http://www.websitebaker.org/" target="_blank">
			<img src="<?php echo TEMPLATE_DIR; ?>/powered.jpg" border="0" alt="Powered By Website Baker" />
		</a>
	</td>
	<?php } ?>
</tr>
<tr>
	<td colspan="2" class="border">
		<img src="<?php echo TEMPLATE_DIR; ?>/footer.png" border="0" alt="" />
	</td>
</tr>
<tr>
	<td colspan="2" class="footer">
		<?php page_footer(); ?>
	</td>
</tr>
</table>

</body>
</html>