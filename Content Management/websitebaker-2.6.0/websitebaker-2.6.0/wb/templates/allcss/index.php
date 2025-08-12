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

<div class="main">
	
	<div class="banner">
		<a href="<?php echo WB_URL; ?>/" target="_top"><?php echo WEBSITE_TITLE; ?></a>
		<font color="#D0D0D0">| <?php echo PAGE_TITLE; ?></font>
	</div>
	
	<div class="search_box">
		<?php if(SHOW_SEARCH) { ?>
		<form name="search" action="<?php echo WB_URL.'/search/index'.PAGE_EXTENSION; ?>" method="post">
		<input type="text" name="string" class="search_string" />
		<input type="submit" name="submit" value="Search" class="search_submit" />
		</form>
		<?php } ?>
	</div>
	
	<?php
	// Only show menu items if we are supposed to
	if(SHOW_MENU) {
	?>	
	<div class="menu">
		<?php page_menu(0, 1, '<li class="menu_main"[class]>[a][menu_title][/a]</li>', '<ul>', '</ul>', '', ' style="font-weight: bold;"'); ?>
		
		<?php
		if(FRONTEND_LOGIN == 'enabled' AND VISIBILITY != 'private' AND $wb->get_session('USER_ID') == '') {
		?>
		<form name="login" action="<?php echo LOGIN_URL; ?>" method="post" class="login_table">
			<h1><?php echo $TEXT['LOGIN']; ?></h1>
			<?php echo $TEXT['USERNAME']; ?>:
			<input type="text" name="username" style="text-transform: lowercase;" />
			<?php echo $TEXT['PASSWORD']; ?>:
			<input type="password" name="password" />
			<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
			<a href="<?php echo FORGOT_URL; ?>"><?php echo $TEXT['FORGOT_DETAILS']; ?></a>
				<?php if(is_numeric(FRONTEND_SIGNUP)) { ?>
					<a href="<?php echo SIGNUP_URL; ?>"><?php echo $TEXT['SIGNUP']; ?></a>
				<?php } ?>
		</form>
		<?php
		} elseif(FRONTEND_LOGIN == 'enabled' AND is_numeric($wb->get_session('USER_ID'))) {
		?>
		<form name="logout" action="<?php echo LOGOUT_URL; ?>" method="post" class="login_table">
			<h1><?php echo $TEXT['LOGGED_IN']; ?></h1>
			<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?>
			<br />
			<input type="submit" name="submit" value="<?php echo $MENU['LOGOUT']; ?>" />
			<br />
			<a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a>
			<a href="<?php echo ADMIN_URL; ?>/index.php"><?php echo $TEXT['ADMINISTRATION']; ?></a>
		</form>
		<?php
		}
		?>
	</div>
	<?php } ?>
	
	<div class="content">
		<?php page_content(); ?>
	</div>
	
	<div class="footer">
		<?php page_footer(); ?>
	</div>
	
</div>

<div class="powered_by">
	Powered by <a href="http://www.websitebaker.org" target="_blank">Website Baker</a>
</div>

</body>
</html>