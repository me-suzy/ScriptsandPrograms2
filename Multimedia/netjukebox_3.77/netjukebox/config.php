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
//  | config.php                                                                |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
authenticate('access_config');
require_once('include/header.inc.php');
require_once('include/httpq.inc.php');

//FormattedNavigator
$name	= array('Configuration');
$url	= array();
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Configuration</td>
	<td class="textspace"></td>
	<td>Comment</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<tr class="odd">
	<td height="31"></td>
	<td><a href="httpq.php?command=HttpqConfiguration"><img src="<?php echo $cfg['img']; ?>/medium_httpq.gif" alt="" width="50" height="25" border="0" class="space">httpQ&nbsp;configuration</a></td>
	<td></td>
	<td><?php echo htmlentities($cfg['httpq_host']) . ':'. $cfg['httpq_port'] . ' (' . htmlentities($cfg['media_share']) . ')'; ?></td>
	<td></td>
</tr>
<tr class="even">
	<td height="31"></td>
	<td><a href="users.php"><img src="<?php echo $cfg['img']; ?>/medium_users.gif" alt="" width="50" height="25" border="0" class="space">Users</a></td>
	<td></td>
	<td>Users authentication</td>
	<td></td>
</tr>
<tr class="odd">
	<td height="31"></td>
	<td><a href="users.php?command=online"><img src="<?php echo $cfg['img']; ?>/medium_online.gif" alt="" width="50" height="25" border="0" class="space">Online</a></td>
	<td></td>
	<td>Online in the last 24 hours</td>
	<td></td>
</tr>
<tr class="even">
	<td height="31"></td>
	<td><a href="statistics.php"><img src="<?php echo $cfg['img']; ?>/medium_statistics.gif" alt="" width="50" height="25" border="0" class="space">Statistics</a></td>
	<td></td>
	<td>Show media statistics</td>
	<td></td>
</tr>
<tr class="odd">
	<td height="31"></td>
	<td><a href="phpinfo.php" target="_blank"><img src="<?php echo $cfg['img']; ?>/medium_php.gif" alt="" width="50" height="25" border="0" class="space">PHP information</a></td>
	<td></td>
	<td>Show PHP information</td>
	<td></td>
</tr>
<tr class="even">
	<td height="31"></td>
	<td><a href="update.php?command=update"><img src="<?php echo $cfg['img']; ?>/medium_mysql.gif" alt="" width="50" height="25" border="0" class="space">Update</a></td>
	<td></td>
	<td>Full update</td>
	<td></td>
</tr>
<tr class="odd">
	<td height="31"></td>
	<td><a href="update.php?command=FastUpdate"><img src="<?php echo $cfg['img']; ?>/medium_mysql.gif" alt="" width="50" height="25" border="0" class="space">Fast&nbsp;udate</a></td>
	<td></td>
	<td>Only add new albums</td>
	<td></td>
</tr>
<?php
$i = 1;
$no_image		= mysql_num_rows(mysql_query('SELECT album_id FROM bitmap WHERE filemtime = "' . filemtime($cfg['home_dir'] . '/images/image.gif') . '" AND flag = 0'));
$skipped_image	= mysql_num_rows(mysql_query('SELECT album_id FROM bitmap WHERE flag > 1'));

if ($no_image > 0)
	{ ?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td height="31"></td>
	<td><a href="update.php?command=ImageUpdate&amp;flag=1"><img src="<?php echo $cfg['img']; ?>/medium_image.gif" alt="" width="50" height="25" border="0" class="space">Update images from internet</a></td>
	<td></td>
	<td><?php echo $no_image; ?> images</td>
	<td></td>
</tr>
<?php
	}
if ($skipped_image > 0)
	{ ?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td height="31"></td>
	<td><a href="update.php?command=ImageUpdate&amp;flag=3"><img src="<?php echo $cfg['img']; ?>/medium_image.gif" alt="" width="50" height="25" border="0" class="space">Update images from internet</a></td>
	<td></td>
	<td><?php echo $skipped_image; ?> skipped images</td>
	<td></td>
</tr>
<?php
	}
?>
</table>
<?php
require_once('include/footer.inc.php');
?>
