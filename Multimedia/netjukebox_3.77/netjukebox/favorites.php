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
//  | favorites.php                                                             |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');

$command 		= GetPost('command');
$favorites_id	= GetPost('favorites_id');

if ($command == '')		    			home();
if ($command == 'ViewNew')				ViewNew();
if ($command == 'ViewPopular')			ViewPopular();
if ($command == 'edit') 				edit($favorites_id);
if ($command == 'add') 					add();
if ($command == 'update') 				update($favorites_id);
if ($command == 'ImportPlaylist')		UpdateFavorites($favorites_id, 'import');
if ($command == 'AddPlaylist')			UpdateFavorites($favorites_id, 'add');
if ($command == 'delete') 				del($favorites_id);

exit();



//  +---------------------------------------------------------------------------+
//  | Home                                                                      |
//  +---------------------------------------------------------------------------+
function home()
{
global $cfg;
authenticate('access_favorites');
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Favorites');
$url	= array();
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Name</td>
	<td class="textspace"></td>
	<td>Comment</td>
	<td class="textspace"></td>
	<td colspan="2" align="right"><?php if ($cfg['access_config']) echo'<a href="favorites.php?command=add" target="main" onMouseOver="return overlib(\'Add current playlist to favorites\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_edit_add.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="8"></td></tr>
<?php

$i=0;
$query = mysql_query('SELECT name, comment, stream, favorites_id FROM favorites WHERE 1 ORDER BY stream, name');
while ($favorites = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play'])
			{
			echo '<a href="httpq.php?command=PlayFavorites&amp;favorites_id=' . $favorites['favorites_id'] . '" target="dummy"><img src="' . $cfg['img'] . '/';
			if ($favorites['stream']) echo 'small_stream.gif'; else echo 'small_play.gif';
			echo '" alt="" width="21" height="21" border="0" class="space">' . htmlentities($favorites['name']) . '</a>';
			}
		else
			{
			echo htmlentities($favorites['name']);
			} ?></td>
	<td></td>
	<td><?php echo htmlentities($favorites['comment']); ?></td>
	<td></td>
	<td><?php if ($cfg['access_config']) echo'<a href="favorites.php?command=delete&amp;favorites_id=' . $favorites['favorites_id'] . '" target="main" onClick="return confirm(\'Are you sure you want to delete favorite: ' . htmlentities($favorites['name']) . '?\');" onMouseOver="return overlib(\'Delete\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_delete.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_config']) echo'<a href="favorites.php?command=edit&amp;favorites_id=' . $favorites['favorites_id'] . '" target="main" onMouseOver="return overlib(\'Edit\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_edit.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View New                                                                  |
//  +---------------------------------------------------------------------------+
function ViewNew()
{
global $cfg;
authenticate('access_favorites');
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Favorites');
$url	= array('favorites.php');
$name[] = 'New';
FormattedNavigator($url, $name);
?>
<table class="border" border="0" cellspacing="0" cellpadding="0">
<tr class="header">
	<td class="spacer"></td>
	<td></td><!-- optional play -->
	<td></td><!-- optional add -->
	<td<?php if ($cfg['access_play']) echo' class="spacer"'; ?>></td>
	<td>Artist</td>
	<td class="textspace"></td>
	<td>Album</td>
	<td class="textspace"></td>
	<td align="center">Date</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="11"></td></tr>
<?php

$i=0;
$query = mysql_query('SELECT artist, artist_alphabetic, album, album_add_time, album_id FROM album WHERE album_add_time ORDER BY album_add_time DESC');
while ($album = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;album_id=' . $album['album_id'] . '" target="dummy"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0" onMouseOver="return overlib(\'Play album\');" onMouseOut="return nd();"></a>'; ?></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=AddSelect&amp;album_id=' . $album['album_id'] . '" target="dummy"><img src="' . $cfg['img'] . '/small_add.gif" alt="" width="21" height="21" border="0" onMouseOver="return overlib(\'Add album\');" onMouseOut="return nd();"></a>'; ?></td>
	<td></td>
	<td><a href="index.php?menu=browse&amp;command=view2&amp;artist=<?php echo rawurlencode($album['artist_alphabetic']); ?>&amp;order=year" target="_top"><?php echo htmlentities($album['artist']); ?></a></td>
	<td></td>
	<td><a href="index.php?menu=browse&amp;command=view3&amp;album_id=<?php echo $album['album_id']; ?>" target="_top"><?php echo htmlentities($album['album']); ?></a></td>
	<td></td>
	<td align="center"><?php echo date('d F Y', $album['album_add_time']); ?></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View Popular                                                              |
//  +---------------------------------------------------------------------------+
function ViewPopular()
{
global $cfg;
authenticate('access_favorites');
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Favorites');
$url	= array('favorites.php');
$name[] = 'Popular';
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td></td><!-- optional play -->
	<td></td><!-- optional add -->
	<td<?php if ($cfg['access_play']) echo' class="spacer"'; ?>></td>
	<td>Artist</td>
	<td class="textspace"></td>
	<td>Album</td>
	<td class="textspace"></td>
	<td>Counter</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="11"></td></tr>
<?php

$i=0;
$query = mysql_query('SELECT artist, artist_alphabetic, album, counter, counter_update_time, album_id FROM album WHERE 1 ORDER BY counter DESC, counter_update_time DESC');
while ($album = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;album_id=' . $album['album_id'] . '" target="dummy"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0" onMouseOver="return overlib(\'Play album\');" onMouseOut="return nd();"></a>'; ?></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=AddSelect&amp;album_id=' . $album['album_id'] . '" target="dummy"><img src="' . $cfg['img'] . '/small_add.gif" alt="" width="21" height="21" border="0" onMouseOver="return overlib(\'Add album\');" onMouseOut="return nd();"></a>'; ?></td>
	<td></td>
	<td><a href="index.php?menu=browse&amp;command=view2&amp;artist=<?php echo rawurlencode($album['artist_alphabetic']); ?>&amp;order=year" target="_top"><?php echo htmlentities($album['artist']); ?></a></td>
	<td></td>
	<td><a href="index.php?menu=browse&amp;command=view3&amp;album_id=<?php echo $album['album_id']; ?>" target="_top"><?php echo htmlentities($album['album']); ?></a></td>
	<td></td>
	<td align="center"><?php echo $album['counter']; ?></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Edit                                                                      |
//  +---------------------------------------------------------------------------+
function edit($favorites_id)
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');
//FormattedNavigator
$name	= array('Favorites');
$url	= array('favorites.php');
$name[]	= 'Edit';
FormattedNavigator($url, $name);

$query = mysql_query('SELECT name, comment FROM favorites WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');
$favorites = mysql_fetch_array($query);
?>	
<form action="favorites.php" method="post" name="favorites" target="main">
	<input type="hidden" name="command" value="update">
	<input type="hidden" name="favorites_id" value="<?php echo $favorites_id; ?>">
<table border="0" cellspacing="0" cellpadding="1">
<tr>
	<td width="75">Name</td>
	<td><input type="text" name="name" value="<?php echo htmlentities($favorites['name']); ?>" maxlength="255" style="width: 175px;"></td>
</tr>
<tr>
	<td>Comment</td>
	<td><input type="text" name="comment" value="<?php echo htmlentities($favorites['comment']); ?>" maxlength="255" style="width: 100%;"></td>
</tr>
<tr>
	<td height="10" colspan="2"></td>
</tr>
<tr>
	<td></td>
	<td>
		<input type="image" value="Update" src="<?php echo $cfg['img']; ?>/button_save.gif">
		<a href="favorites.php"><img src="<?php echo $cfg['img']; ?>/button_cancel.gif" alt="" width="106" height="26" border="0"></a>
		<br><br><hr class="light"><br>
		<a href="favorites.php?command=ImportPlaylist&amp;favorites_id=<?php echo $favorites_id; ?>"><img src="<?php echo $cfg['img']; ?>/button_import.gif" alt="" width="106" height="26" border="0"></a>
		<a href="favorites.php?command=AddPlaylist&amp;favorites_id=<?php echo $favorites_id; ?>"><img src="<?php echo $cfg['img']; ?>/button_add.gif" alt="" width="106" height="26" border="0"></a>
	</td>
</tr>
<tr>
	<td height="10" colspan="2"></td>
</tr>
<tr>
	<td valign="top">Track(s)</td>
	<td>
	<!-- ---- begin indent ---- -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Artist</td>
	<td class="textspace"></td>
	<td>Title</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="6"></td></tr>
<?php

$i=0;
$query1 = mysql_query('SELECT track_id, stream_url FROM favoritesitems WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '" ORDER BY position');
while ($favoritesitems = mysql_fetch_array($query1))
	{
	if ($favoritesitems['track_id'])
		{
		$query2	= mysql_query('SELECT artist, title FROM track WHERE track_id = "' . $favoritesitems['track_id'] . '"');
		$track	= mysql_fetch_array($query2);
		$artist	= $track['artist'];
		$title	= $track['title'];
		}
	elseif ($favoritesitems['stream_url'])
		{
		$artist	= 'stream: ' . $favoritesitems['stream_url'];
		$title	= '';
		}
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php echo $artist; ?></td>
	<td></td>
	<td><?php echo $title; ?></td>
	<td></td>
</tr>
<?php
	}
?>
</table>
	<!-- ---- end indent ---- -->
	</td>
</tr>
</table>
</form>

<script type="text/javascript">
	<!--
	document.favorites.name.focus();
	//-->
</script>

<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Add                                                                       |
//  +---------------------------------------------------------------------------+
function add()
{
authenticate('access_config', true, false);
mysql_query('INSERT INTO favorites (name) VALUES ("")');
$favorites_id = mysql_insert_id();

UpdateFavorites($favorites_id, 'import');
}



//  +---------------------------------------------------------------------------+
//  | Update                                                                    |
//  +---------------------------------------------------------------------------+
function update($favorites_id)
{
authenticate('access_config', true, false);
$name	 = GetPost('name');
$comment = GetPost('comment');
mysql_query('UPDATE favorites SET
			name	= "' . mysql_real_escape_string($name) . '",
			comment	= "' . mysql_real_escape_string($comment) . '"
			WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');

home();
}



//  +---------------------------------------------------------------------------+
//  | Upate Favorites                                                           |
//  +---------------------------------------------------------------------------+
function UpdateFavorites($favorites_id, $mode)
{
global $cfg;
authenticate('access_config', true, false);
require_once('include/httpq.inc.php');

$name	 = GetPost('name');
$comment = GetPost('comment');

$files		= httpq('getplaylistfile', 'delim=*');
$files		= str_replace('\\', '/', $files);
$file		= explode('*', $files);

if ($mode == 'import')
	{
	mysql_query('DELETE FROM favoritesitems WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');
	$offset = 0;
	}
if ($mode = 'add')
	{
	$query = mysql_query('SELECT position FROM favoritesitems WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '" ORDER BY position DESC');
	$track = mysql_fetch_array($query);
	$offset = $track['position'];
	}

$stream = 0;
for($i=0; $i < count($file); $i++)
	{
	$position = $i + $offset + 1;
	if (strtolower(substr($file[$i], 0, strlen($cfg['media_share']))) == strtolower($cfg['media_share']))
		{
		$relative_file = substr($file[$i], strlen($cfg['media_share']));
		$query = mysql_query('SELECT track_id FROM track WHERE relative_file = "' . mysql_real_escape_string($relative_file) . '"');
		$track = mysql_fetch_array($query);
		mysql_query('INSERT INTO favoritesitems (track_id, position, favorites_id) VALUES ("' . $track['track_id'] . '", "' . $position . '", "' . mysql_real_escape_string($favorites_id) . '")');
		}
	else
		foreach($cfg['stream_prefix'] as $stream_prefix)
			If (strtolower(substr($file[$i], 0, strlen($stream_prefix))) == $stream_prefix)
				{
				$stream = 1;
				mysql_query('INSERT INTO favoritesitems (stream_url, position, favorites_id) VALUES ("' . mysql_real_escape_string($file[$i]) . '", "' . $position . '", "' . mysql_real_escape_string($favorites_id) . '")');
				}
	}

mysql_query('UPDATE favorites SET
			stream = "' . $stream . '"
			WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');

edit($favorites_id);
}



//  +---------------------------------------------------------------------------+
//  | Del                                                                       |
//  +---------------------------------------------------------------------------+
function del($favorites_id)
{
authenticate('access_config', true, false);
mysql_query('DELETE FROM favorites WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');
mysql_query('DELETE FROM favoritesitems WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '"');
home();
}
?>