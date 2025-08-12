<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | menu.php                                                                  |
//  +---------------------------------------------------------------------------+
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');// HTTP/1.0

require_once('include/globalize.inc.php');
require_once('include/config.inc.php');
$menu = get('menu') or $menu = 'browse';
$seperation = '|';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>netjukebox - the flexible media share</title>
	<meta name="robots" content="noindex, follow">
	<meta name="author" content="Willem Bartels">
	<link href="<?php echo $cfg['css']; ?>/styles.css" rel="stylesheet" type="text/css">
</head>

<body id="menu">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="menu">
<tr>
	<td><img src="<?php echo $cfg['img']; ?>/menu_top_left.gif" alt="" width="13" height="33" border="0"></td>
	<td><img src="<?php echo $cfg['img']; ?>/menu_top.gif" alt="" width="11" border="0"></td>
	<td><a href="index.php?menu=browse" target="_top"><img src="<?php echo $cfg['img']; ?>/menu_browse_<?php if ($menu == 'browse') echo 'on'; else echo 'off'; ?>.gif" alt="" width="90" height="33" border="0"></a></td>
	<td><a href="index.php?menu=favorites" target="_top"><img src="<?php echo $cfg['img']; ?>/menu_favorites_<?php if ($menu == 'favorites') echo 'on'; else echo 'off'; ?>.gif" alt="" width="90" height="33" border="0"></a></td>
	<td><a href="index.php?menu=playlist" target="_top"><img src="<?php echo $cfg['img']; ?>/menu_playlist_<?php if ($menu == 'playlist') echo 'on'; else echo 'off'; ?>.gif" alt="" width="90" height="33" border="0"></a></td>
	<td><a href="index.php?menu=config" target="_top"><img src="<?php echo $cfg['img']; ?>/menu_config_<?php if ($menu == 'config') echo 'on'; else echo 'off'; ?>.gif" alt="" width="90" height="33" border="0"></a></td>
	<td width="100%"></td>
	<td><a href="http://www.netjukebox.nl" target="_blank"><img src="<?php echo $cfg['img']; ?>/menu_netjukebox.gif" alt="" width="156" height="33" border="0"></a></td>
	<td><img src="<?php echo $cfg['img']; ?>/menu_top_right.gif" alt="" width="2" border="0"></td>
</tr>
<tr>
	<td><img src="<?php echo $cfg['img']; ?>/menu_middle_left.gif" alt="" width="13" height="21" border="0"></td>
	<td colspan="7">
<?php
if ($menu == 'browse')
	{
	echo '<a href="browse.php?command=view1&amp;filter=symbol&amp;artist=%23" target="main">&nbsp;# </a>' . $seperation;
	for ($letter = 'a'; $letter != 'aa'; $letter++) 
		echo '<a href="browse.php?command=view1&amp;filter=start&amp;artist=' . $letter . '" target="main"> ' . $letter . ' </a>' . $seperation;
	echo '<a href="browse.php?command=view1&amp;artist=all&amp;filter=all" target="main"> * </a>' . $seperation .
	'<a href="browse.php?command=view2&amp;artist=Various&amp;filter=exact&amp;order=album" target="main"> &amp;&nbsp;</a>';
	}

elseif ($menu == 'favorites')
	{
	echo '<a href="favorites.php" target="main">&nbsp;favorites </a>' . $seperation .
	'<a href="favorites.php?command=ViewPopular" target="main"> popular </a>'. $seperation .
	'<a href="favorites.php?command=ViewNew" target="main"> new&nbsp;</a>';
	}

elseif ($menu == 'playlist')
	{
	echo '<a href="httpq.php?command=prev" target="dummy">&nbsp;previous </a>' . $seperation .
	'<a href="httpq.php?command=play" target="dummy"> play </a>' . $seperation .
	'<a href="httpq.php?command=pause" target="dummy"> pause </a>' . $seperation .
	'<a href="httpq.php?command=stop" target="dummy"> stop </a>' . $seperation .
	'<a href="httpq.php?command=next" target="dummy"> next </a>' . $seperation .
	'<a href="index.php?menu=favorites&amp;command=add" target="_top"> add playlist to favorites&nbsp;</a>';
	}

elseif ($menu == 'config')
	{
	echo '<a href="httpq.php?command=HttpqConfiguration" target="main">&nbsp;httpQ configuration </a>' . $seperation .
	'<a href="users.php" target="main"> users </a>' . $seperation .
	'<a href="users.php?command=online" target="main"> online </a>' . $seperation .
	'<a href="statistics.php" target="main"> statistics </a>' . $seperation .
	'<a href="update.php?command=update" target="main"> update&nbsp;</a>';
	}
?>
	</td>
	<td><img src="<?php echo $cfg['img']; ?>/menu_middle_right.gif" alt="" width="2" border="0"></td>
</tr>
<tr>
	<td height="2" colspan="9"></td>
</tr>
</table>
</body>
</html>
