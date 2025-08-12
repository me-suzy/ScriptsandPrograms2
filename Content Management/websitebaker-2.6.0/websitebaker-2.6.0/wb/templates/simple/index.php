<?php

// $Id: index.php,v 1.4 2005/04/15 06:38:13 rdjurovich Exp $

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

<table cellpadding="5" cellspacing="0" border="0" width="750" align="center">
<tr>
	<td colspan="2" class="header">
		<?php page_title('','[WEBSITE_TITLE]'); ?>
	</td>
</tr>
<tr>
	<td colspan="2" class="footer">
		&nbsp;
	</td>
</tr>
<tr>
	<td class="menu">
		<?php if(SHOW_MENU) { /* Only shown menu if we need to */ ?>	
			Menu: <br />
			<?php page_menu(); ?>
		<?php } ?>
		
		<?php if(SHOW_SEARCH) { /* Only show search box if search is enabled */ ?>
			<br />
			Search: <br />
			<form name="search" action="<?php echo WB_URL; ?>/search/index<?php echo PAGE_EXTENSION; ?>" method="post">
				<input type="text" name="string" style="width: 100%;" />
				<input type="submit" name="submit" value="Search" style="width: 100%;" />
			</form>
		<?php } ?>
		
		<br />
		<a href="http://www.websitebaker.org" target="_blank">Powered by <br /> Website Baker</a>
	</td>
	<td class="content">
		<?php page_content(); ?>
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