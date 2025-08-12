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
//  | httpq.php                                                                 |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
require_once('include/httpq.inc.php');


$command		= GetPost('command');
$httpq_id		= GetPost('httpq_id');
$favorites_id	= get('favorites_id');
$index			= get('index');


if ($command == 'PlaySelect')		PlaySelect();
if ($command == 'AddSelect')		AddSelect();
if ($command == 'SeekImageMap')		SeekImageMap();
if ($command == 'PlayFavorites')	PlayFavorites($favorites_id);
if ($command == 'PlayIndex')		PlayIndex($index);
if ($command == 'DeleteIndex')		DeleteIndex($index);
if ($command == 'VolumeImageMap')	VolumeImageMap();
if ($command == 'ToggleShuffle')	ToggleShuffle();
if ($command == 'ToggleRepeat') 	ToggleRepeat();
if (in_array ($command, array('prev', 'play', 'pause', 'stop', 'next')))
	{
	authenticate('access_play', true, false);
	httpq($command);
	}
if ($command == 'SaveHttpqProfile')		{SaveHttpqProfile($httpq_id);	$command = 'HttpqConfiguration';}
if ($command == 'DeleteHttpqProfile')	{DeleteHttpqProfile($httpq_id);	$command = 'HttpqConfiguration';}
if ($command == 'SetHttpqProfile')		{SetHttpqProfile($httpq_id);	$command = 'HttpqConfiguration';}
if ($command == 'ServerAutoDetect')		{ServerAutoDetect($httpq_id);	$command = 'EditHttpqProfile';}
if ($command == 'ClientAutoDetect')		{ClientAutoDetect($httpq_id);	$command = 'EditHttpqProfile';}
if ($command == 'HttpqConfiguration')	HttpqConfiguration();
if ($command == 'EditHttpqProfile')		EditHttpqProfile($httpq_id);

exit();



//  +---------------------------------------------------------------------------+
//  | Play Select                                                               |
//  +---------------------------------------------------------------------------+
function PlaySelect()
{
authenticate('access_play', true, false);
httpq('delete');
AddTracks();
httpq('stop');
httpq('play');
}



//  +---------------------------------------------------------------------------+
//  | Add Select                                                                |
//  +---------------------------------------------------------------------------+
function AddSelect()
{
authenticate('access_add', true, false);
AddTracks();
}



//  +---------------------------------------------------------------------------+
//  | Add Tracks                                                                |
//  +---------------------------------------------------------------------------+
function AddTracks()
{
global $cfg;

$track_id	= get('track_id');
$album_id	= get('album_id');
$artist		= get('artist');
$title		= get('title');
$filter		= get('filter');
$order		= get('order');
$sort		= get('sort');

if ($track_id)
	{
	$query = mysql_query('SELECT relative_file FROM track WHERE track_id = "' . mysql_real_escape_string($track_id) . '"');
	}
elseif ($album_id)
	{
	$query = mysql_query('SELECT relative_file FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" ORDER BY relative_file');
	}
elseif ($artist)
	{
	$filter_query = 'WHERE track.artist="' . mysql_real_escape_string($artist) . '" AND track.album_id = album.album_id';
	}
elseif ($title)
	{
	if ($filter == 'start')		$filter_query = 'WHERE track.title LIKE "' . mysql_real_escape_string($title) . '%" AND track.album_id = album.album_id';
	if ($filter == 'smart')		$filter_query = 'WHERE (track.title LIKE "' . mysql_real_escape_string($title) . '%" OR soundex(track.title) = soundex("' . mysql_real_escape_string($title) . '")) AND track.album_id = album.album_id';
	if ($filter == 'contains')	$filter_query = 'WHERE track.title LIKE "%' . mysql_real_escape_string($title) . '%" AND track.album_id = album.album_id';
	}
if ($artist || $title)
	{
	if ($order == 'artist' && $sort == 'asc')		$order_query = 'ORDER BY artist, title';
	if ($order == 'artist' && $sort == 'desc')		$order_query = 'ORDER BY artist DESC, title DESC';
	if ($order == 'title' && $sort == 'asc')		$order_query = 'ORDER BY title, album';
	if ($order == 'title' && $sort == 'desc')		$order_query = 'ORDER BY title DESC, album DESC';
	if ($order == 'featuring' && $sort == 'asc')	$order_query = 'ORDER BY featuring, title, artist';
	if ($order == 'featuring' && $sort == 'desc')	$order_query = 'ORDER BY featuring DESC, title DESC, artist DESC';
	if ($order == 'album' && $sort == 'asc')		$order_query = 'ORDER BY album, relative_file';
	if ($order == 'album' && $sort == 'desc')		$order_query = 'ORDER BY album DESC, relative_file DESC';
	
	$query	= mysql_query('SELECT track.relative_file FROM track, album ' . $filter_query . ' ' . $order_query);
	}

while ($track = mysql_fetch_array($query))
	httpq('playfile', 'file=' . rawurlencode($cfg['media_share'] . $track['relative_file']));

if ($album_id)
	AlbumCounter($album_id);
}



//  +---------------------------------------------------------------------------+
//  | Album Counter                                                             |
//  +---------------------------------------------------------------------------+
function AlbumCounter($album_id)
{
$time  = time();
$query = mysql_query('SELECT counter_update_time FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$album = mysql_fetch_array($query);

if ($album['counter_update_time'] + 600 - $time < 0)
	{
	mysql_query('UPDATE album SET
				counter				= counter + 1,
				counter_update_time	= "' . $time . '"
				WHERE album_id		= "' . mysql_real_escape_string($album_id) . '"');
	}
else
	{
	mysql_query('UPDATE album SET
				counter_update_time	= "' . $time . '"
				WHERE album_id		= "' . mysql_real_escape_string($album_id) . '"');
	}
}



//  +---------------------------------------------------------------------------+
//  | Seek Image Map                                                            |
//  +---------------------------------------------------------------------------+
function SeekImageMap()
{
global $cfg;
authenticate('access_play', true, false);

$dx         = get('dx');
$xy			= get('xy');		//$xy = ?xx,yy
$xy			= substr($xy, 1);	//remove ?
list($x, $y)= explode(',', $xy);

$listpos	= httpq('getlistpos');
$file		= httpq('getplaylistfile', 'index=' . $listpos);
$relative_file = str_replace('\\', '/', $file);
$relative_file = substr($relative_file, strlen($cfg['media_share']));

$query 		= mysql_query('SELECT playtime_miliseconds FROM track WHERE relative_file = "' . $relative_file . '"');
$track 		= mysql_fetch_array($query);

$miliseconds = round($track['playtime_miliseconds'] * $x / ($dx-1));
httpq('jumptotime', 'ms=' . $miliseconds);
}



//  +---------------------------------------------------------------------------+
//  | Play Index                                                                |
//  +---------------------------------------------------------------------------+
function PlayIndex($index)
{
authenticate('access_play', true, false);
httpq('setplaylistpos', 'index=' . $index);
httpq('stop');
httpq('play');
}



//  +---------------------------------------------------------------------------+
//  | Delete Index                                                              |
//  +---------------------------------------------------------------------------+
function DeleteIndex($index)
{
authenticate('access_play', true, false);
httpq('deletepos', 'index='. $index);
}



//  +---------------------------------------------------------------------------+
//  | Play Favorites                                                            |
//  +---------------------------------------------------------------------------+
function PlayFavorites($favorites_id)
{
global $cfg;
authenticate('access_play', true, false);
httpq('delete');

$query = mysql_query('SELECT track_id, stream_url FROM favoritesitems WHERE favorites_id = "' . mysql_real_escape_string($favorites_id) . '" ORDER BY position');
while ($favoritesitems = mysql_fetch_array($query))
	{
	if ($favoritesitems['track_id'])
		{
		$query2 = mysql_query('SELECT relative_file FROM track WHERE track_id = "' . $favoritesitems['track_id'] . '"');
		$track = mysql_fetch_array($query2);
		httpq('playfile', 'file=' . rawurlencode($cfg['media_share'] . $track['relative_file']));
		}
	elseif ($favoritesitems['stream_url'])
		httpq('playfile', 'file=' . rawurlencode($favoritesitems['stream_url']));
	}

httpq('stop');
httpq('play');
}



//  +---------------------------------------------------------------------------+
//  | Volume Image Map                                                          |
//  +---------------------------------------------------------------------------+
function VolumeImageMap()
{
global $cfg;
authenticate('access_play', true, false);

$dx         = get('dx');
$xy			= get('xy');		//$xy = ?xx,yy
$xy			= substr($xy, 1);	//remove ?
list($x, $y)= explode(',', $xy);
$volume		= round(255 * $x / ($dx-1));
if ($volume < round(255 * 0.05)) $volume = 0;
if ($volume > round(255 * 0.95)) $volume = 255;
httpq('setvolume', 'level=' . $volume);
}



//  +---------------------------------------------------------------------------+
//  | Toggle Shuffle                                                            |
//  +---------------------------------------------------------------------------+
function ToggleShuffle()
{
authenticate('access_play', true, false);
$invert = (httpq('shuffle_status') xor true);
httpq('shuffle', 'enable=' . $invert);
}



//  +---------------------------------------------------------------------------+
//  | Toggle Repeat                                                             |
//  +---------------------------------------------------------------------------+
function ToggleRepeat()
{
authenticate('access_play', true, false);
$invert = (httpq('repeat_status') xor true);
httpq('repeat', 'enable=' . $invert);
}


//  +---------------------------------------------------------------------------+
//  | httpQ Configuration                                                       |
//  +---------------------------------------------------------------------------+
function HttpqConfiguration()
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Configuration');
$url	= array('config.php');
$name[]	= 'httpQ configuration';
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>httpQ profile</td>
	<td class="textspace"></td>
	<td>Client</td>
	<td class="textspace"></td>
	<td>Share</td>
	<td class="textspace"></td>
	<td colspan="2" align="right"><a href="httpq.php?command=EditHttpqProfile&amp;httpq_id=0" target="main" onMouseOver="return overlib('Add httpQ profile');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_edit_add.gif" alt="" width="21" height="21" border="0"></a></td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="10"></td></tr>
<?php
$i=0;
$query = mysql_query('SELECT name, httpq_host, httpq_port, httpq_id, media_share FROM configuration_httpq ORDER BY name');
while ($configuration_httpq = mysql_fetch_array($query))
	{
?>
<tr class="<?php if ($configuration_httpq['httpq_id'] == $cfg['httpq_id']) echo 'select'; else echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="httpq.php?command=SetHttpqProfile&amp;httpq_id=<?php echo $configuration_httpq['httpq_id']; ?>"><img src="<?php echo $cfg['img']; ?>/small_favorites.gif" alt="" width="21" height="21" border="0" class="space"><?php echo htmlentities($configuration_httpq['name']); ?></a></td>
	<td></td>
	<td><?php echo htmlentities($configuration_httpq['httpq_host']) . ':' . $configuration_httpq['httpq_port']; ?></td>
	<td></td>
	<td><?php echo htmlentities($configuration_httpq['media_share']); ?></td>
	<td></td>
	<td><a href="httpq.php?command=DeleteHttpqProfile&amp;httpq_id=<?php echo $configuration_httpq['httpq_id']; ?>" target="main" onClick="return confirm('Are you sure you want to delete user profile: <?php echo htmlentities($configuration_httpq['name']); ?>?');" onMouseOver="return overlib('Delete');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_delete.gif" alt="" width="21" height="21" border="0"></a></td>
	<td><a href="httpq.php?command=EditHttpqProfile&amp;httpq_id=<?php echo $configuration_httpq['httpq_id']; ?>" target="main"onMouseOver="return overlib('Edit');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_edit.gif" alt="" width="21" height="21" border="0"></a></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Edit httpQ Profile                                                        |
//  +---------------------------------------------------------------------------+
function EditHttpqProfile($httpq_id)
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');

if ($httpq_id == '0') // Add configuraton
	{
	$txt_menu			= 'Add profile';
	$name				= 'Name';
	mysql_query('INSERT INTO configuration_httpq (name) VALUES ("")');
	$httpq_id = mysql_insert_id();
	}
else // Edit configutaion
	{
	$query = mysql_query('SELECT name, httpq_host, httpq_port, httpq_pass, media_share FROM configuration_httpq WHERE httpq_id = "' . mysql_real_escape_string($httpq_id) . '"');
	$configuration_httpq = mysql_fetch_array($query);
	$txt_menu			= 'Edit profile';
	$name				= $configuration_httpq['name'];
	$cfg['httpq_host']	= $configuration_httpq['httpq_host'];
	$cfg['httpq_port']	= $configuration_httpq['httpq_port'];
	$cfg['httpq_pass']	= $configuration_httpq['httpq_pass'];
	$cfg['media_share']	= $configuration_httpq['media_share'];
	}
//FormattedNavigator
$nav_name	= array('Configuration');
$nav_url	= array('config.php');
$nav_name[]	= 'httpQ configuration';
$nav_url[]	= 'httpq.php?command=HttpqConfiguration';
$nav_name[]	= $txt_menu;
FormattedNavigator($nav_url, $nav_name);
?>
<form action="httpq.php" method="post" name="config" target="main">
		<input type="hidden" name="command" value="SaveHttpqProfile">
		<input type="hidden" name="httpq_id" value="<?php echo $httpq_id ; ?>">
<table border="0" cellspacing="0" cellpadding="1">
<tr>
	<td>Name</td>
	<td class="spacer"></td>
	<td><input type="text" name="name" value="<?php echo htmlentities($name); ?>" maxlength="255" style="width: 175px;"></td>
</tr>
<tr>
	<td height="15" colspan="3"></td>
</tr>
<tr>
	<td>Client</td>
	<td></td>
	<td><a href="httpq.php?command=ClientAutoDetect&amp;httpq_id=<?php echo $httpq_id; ?>"><img src="<?php echo $cfg['img']; ?>/small_network.gif" alt="" width="21" height="21" border="0" class="space">Client auto detect</a></td>
</tr>
<tr>
	<td>Server</td>
	<td></td>
	<td><a href="httpq.php?command=ServerAutoDetect&amp;httpq_id=<?php echo $httpq_id; ?>"><img src="<?php echo $cfg['img']; ?>/small_drive.gif" alt="" width="21" height="21" border="0" class="space">Server auto detect</a></td>
</tr>
<tr>
	<td height="15" colspan="3"></td>
</tr>
<tr>
	<td width="100">httpQ host</td>
	<td></td>
	<td><input type="text" name="httpq_host" value="<?php echo htmlentities($cfg['httpq_host']); ?>" maxlength="255" style="width: 175px;"></td>
</tr>
<tr>
	<td>httpQ port</td>
	<td></td>
	<td><input type="text" name="httpq_port" value="<?php echo $cfg['httpq_port']; ?>" maxlength="5" style="width: 175px;"></td>
</tr>
<tr>
	<td>httpQ password</td>
	<td></td>
	<td><input type="password" name="httpq_pass" value="<?php echo htmlentities($cfg['httpq_pass']); ?>" maxlength="255" style="width: 175px;"></td>
</tr>
<tr>
	<td>Media share</td>
	<td></td>
	<td><input type="text" name="media_share" value="<?php echo htmlentities($cfg['media_share']); ?>" maxlength="255" style="width: 175px;"></td>
</tr>
<tr>
	<td height="15" colspan="3"></td>
</tr>
<tr>
	<td colspan="2"></td>
	<td>
		<input type="image" value="update" src="<?php echo $cfg['img']; ?>/button_save.gif" alt="">
		<a href="httpq.php?command=HttpqConfiguration"><img src="<?php echo $cfg['img']; ?>/button_cancel.gif" alt="" width="106" height="26" border="0"></a>
	</td>
</tr>
</table>
</form>

<script type="text/javascript">
	<!--
	document.config.name.focus();
	//-->
</script>

<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | URL Syntax fix                                                            |
//  +---------------------------------------------------------------------------+
function UrlSyntaxFix($url)
{
$url = trim($url);
$url = str_replace('\\', '/', $url);
while (substr($url, -1, 1) == '/')
	$url = substr($url, 0, -1);
return $url;
}



//  +---------------------------------------------------------------------------+
//  | Server Auto Detect                                                        |
//  +---------------------------------------------------------------------------+
function ServerAutoDetect($httpq_id)
{
global $cfg;
authenticate('access_config', true, false);
$httpq_host		= '127.0.0.1';
$httpq_port		= '4800';
$httpq_pass		= 'pass';
$media_share	= $cfg['media_dir'];

mysql_query('UPDATE configuration_httpq SET
			httpq_host	= "' . $httpq_host . '",
			httpq_port	= "' . $httpq_port . '",
			httpq_pass	= "' . $httpq_pass . '",
			media_share	= "' . $media_share . '"
			WHERE httpq_id = "' . mysql_real_escape_string($httpq_id) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Client Auto Detect                                                        |
//  +---------------------------------------------------------------------------+
function ClientAutoDetect($httpq_id)
{
global $cfg;
authenticate('access_config', true, false);

if (isset($_SERVER['SERVER_ADDR']))	$server = $_SERVER['SERVER_ADDR'];
else								$server	= $_SERVER['SERVER_NAME'];
$temp = explode('/', $cfg['media_dir']);

$httpq_host 	= $_SERVER['REMOTE_ADDR'];
$httpq_port 	= '4800';
$httpq_pass 	= 'pass';
$media_share 	= '//' . $server . '/' . $temp[count($temp)-1];

mysql_query('UPDATE configuration_httpq SET
			httpq_host	= "' . $httpq_host . '",
			httpq_port	= "' . $httpq_port . '",
			httpq_pass	= "' . $httpq_pass . '",
			media_share	= "' . $media_share . '"
			WHERE httpq_id = "' . mysql_real_escape_string($httpq_id) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Save httpQ Settings                                                       |
//  +---------------------------------------------------------------------------+
function SaveHttpqProfile($httpq_id)
{
global $cfg;
authenticate('access_config', true, false);
if ($httpq_id == '0')
	{
	mysql_query('INSERT INTO configuration_httpq (name) VALUES ("")');
	$httpq_id = mysql_insert_id();
	}
$name 			= post('name');
$httpq_host		= post('httpq_host');
$httpq_port		= post('httpq_port');
$httpq_pass		= post('httpq_pass');
$media_share	= post('media_share');
$media_share	= UrlSyntaxFix($media_share);

mysql_query('UPDATE configuration_httpq SET
			name		= "' . mysql_real_escape_string($name) . '",
			httpq_host	= "' . mysql_real_escape_string($httpq_host) . '",
			httpq_port	= "' . mysql_real_escape_string($httpq_port) . '",
			httpq_pass	= "' . mysql_real_escape_string($httpq_pass) . '",
			media_share	= "' . mysql_real_escape_string($media_share) . '"
			WHERE httpq_id = "' . mysql_real_escape_string($httpq_id) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Set httpQ Profile                                                         |
//  +---------------------------------------------------------------------------+
function SetHttpqProfile($httpq_id)
{
global $cfg;
authenticate('access_config', true, false);
$cfg['httpq_id'] = $httpq_id;

mysql_query('UPDATE configuration_session SET
			httpq_id		= "' . mysql_real_escape_string($httpq_id) . '"
			WHERE session_id = "' . mysql_real_escape_string($cfg['session_id']) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Delete httpQ Profile                                                      |
//  +---------------------------------------------------------------------------+
function DeleteHttpqProfile($httpq_id)
{
authenticate('access_config', true, false);
mysql_query('DELETE FROM configuration_httpq WHERE httpq_id = "' . mysql_real_escape_string($httpq_id) . '"');
}
?>