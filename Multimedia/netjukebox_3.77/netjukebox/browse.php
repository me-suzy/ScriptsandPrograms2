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
//  | browse.php                                                                |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');

$command 	= GetPost('command');
$mode		= GetPost('mode');
$artist	 	= GetPost('artist');
$title	 	= GetPost('title');
$order	 	= GetPost('order');
$sort	 	= GetPost('sort');
$album_id	= GetPost('album_id');
$genre_id 	= GetPost('genre_id');
$filter  	= GetPost('filter');

if ($command == '')		    home();
if ($command == 'view1')	view1($artist, $genre_id, $filter); 						//$artist or $genre_id
if ($command == 'view2')	view2($artist, $genre_id, $filter, $order, $sort, $mode);	//$artist or $genre_id
if ($command == 'view3')	view3($album_id);
if ($command == 'view1all') view1all($artist, $filter);
if ($command == 'view3all')	view3all($artist, $title, $filter, $order, $sort);			// $artist or ($title and $filter)

exit();



//  +---------------------------------------------------------------------------+
//  | onmouseover Download Info                                                 |
//  +---------------------------------------------------------------------------+
function onmouseoverDownloadInfo($track_id)
{
$query = mysql_query('SELECT file_size,
					audio_dataformat,
					audio_encoder,
					audio_profile,
					audio_bits_per_sample,
					audio_sample_rate,
					audio_channels,
					video_codec,
					video_resolution_x,
					video_resolution_y,
					video_framerate
					FROM track
					WHERE track_id="' . mysql_real_escape_string($track_id) . '"');
$track = mysql_fetch_array($query);

$list	 = FormattedSize($track['file_size']) . '<br>';
$list	.= '<hr class=\\\'black\\\'>';

if ($track['video_codec'])
	{
	$list .= $track['video_codec'] . '<br>';
	$list .= $track['video_resolution_x'] . 'x';
	$list .= $track['video_resolution_y'] . '<br>';
	$list .= $track['video_framerate'] . ' fps';
	}
if ($track['audio_dataformat'])
	{
	if		($track['audio_channels'] == 1)	$channels = 'Mono';
	elseif	($track['audio_channels'] == 2)	$channels = 'Stereo';
	else									$channels = $track['audio_channels'] . ' Channels';
	if ($track['video_codec']) $list .= '<br><hr class=\\\'black\\\'>';
	$list .= $track['audio_dataformat'] . '<br>';
	$list .= $track['audio_encoder'] . '<br>';
	$list .= $track['audio_profile'];
	$list .= '<br><hr class=\\\'black\\\'>';
	$list .= $track['audio_bits_per_sample'] . ' bit | ' . FormattedFrequency($track['audio_sample_rate']) . ' | ' . $channels;
	}
if (!$track['video_codec'] && !$track['audio_dataformat'])
	{
	$list .= '-';
	}

return 'onmouseover="return overlib(\'' . htmlentities($list) . '\', CAPTION, \'Download:\', WIDTH, 200);" onmouseout="return nd();"';
}



//  +---------------------------------------------------------------------------+
//  | Genre Navigator                                                           |
//  +---------------------------------------------------------------------------+
function GenreNavigator($genre_id)
{
if ($genre_id)
	{
	$name	= array('Browse');
	$url	= array('browse.php');
	}
else
	{
	$name	= array('Browse');
	$url	= array();
	}
for($i=1; $i < strlen($genre_id); $i++)
	{
	$search	= substr($genre_id, 0, $i);
	$query	= mysql_query('SELECT genre, genre_id FROM genre WHERE genre_id LIKE "' . mysql_real_escape_string($search) . '" ORDER BY genre');
	$genre	= mysql_fetch_array($query);
	$url[]	= 'browse.php?command=view1&amp;genre_id=' . $search;
	$name[]	= $genre['genre'];
	}
$query = mysql_query('SELECT genre, genre_id FROM genre WHERE genre_id LIKE "' . mysql_real_escape_string($genre_id) . '" ORDER BY genre');
$genre = mysql_fetch_array($query);
if (substr($genre_id, -1) == '~')
	{
	$name[] = 'General';
	$url[]  = '';
	}
if ($genre['genre'])
	{
	$name[] = $genre['genre'];
	$url[]  = '';
	}
FormattedNavigator($url, $name, false);

//select genre
$query = mysql_query('SELECT genre, genre_id FROM genre WHERE genre_id LIKE "' . mysql_real_escape_string($genre_id) . '_" ORDER BY genre');
if (mysql_num_rows($query) > 0)
	{
	echo '<font class="small">';
	while ($genre = mysql_fetch_array($query))
		{
		echo ' | <a href="browse.php?command=view1&amp;genre_id=' . $genre['genre_id'] . '">' . htmlentities($genre['genre']) . '</a>';
		}
	if (mysql_num_rows(mysql_query('SELECT genre_id FROM album WHERE genre_id LIKE "' . mysql_real_escape_string($genre_id) . '"'))
	AND mysql_num_rows(mysql_query('SELECT genre_id FROM genre WHERE genre_id LIKE "' . mysql_real_escape_string($genre_id) . '_"')))
		{
		echo ' | <a href="browse.php?command=view1&amp;genre_id=' . $genre_id . '~">General</a>';
		}
	echo ' |</font>' . "\n";
	}
echo '<br><br>' . "\n";
}



//  +---------------------------------------------------------------------------+
//  | Home                                                                      |
//  +---------------------------------------------------------------------------+
function home()
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

GenreNavigator('');
?>
<table cellspacing="0" cellpadding="0" class="border">
<tr class="footer" style="height: 10px;"><td colspan="5"></td></tr>
<tr class="footer">
	<td class="spacer"></td>
	<td align="right">(album based) Artist:</td>
	<td class="spacer"></td>
	<td>
	<form action="browse.php?p=12" method="post" name="view1" id="view1" target="main">
		<input type="hidden" name="command" value="view1">
		<select name="filter">
		<option value="smart">Smart...</option>
		<option value="contains">Contains...</option>
		</select>
		<input type="text" name="artist" maxlength="255" style="width: 175px;">
	</form>
	</td>
	<td class="spacer"></td>
</tr>
<tr class="footer" style="height: 5px;"><td colspan="5"></td></tr>
<tr class="footer">
	<td></td>
	<td align="right">(all tracks) Artist:</td>
	<td></td>
	<td>
	<form action="browse.php" method="post" target="main">
		<input type="hidden" name="command" value="view1all">
		<select name="filter">
		<option value="smart">Smart...</option>
		<option value="contains">Contains...</option>
		</select>
		<input type="text" name="artist" maxlength="255" style="width: 175px;">
	</form>
	</td>
	<td></td>
</tr>
<tr class="footer" style="height: 5px;"><td colspan="5"></td></tr>
<tr class="footer">
	<td></td>
	<td align="right">Title:</td>
	<td></td>
	<td>
	<form action="browse.php" method="post" target="main">
		<input type="hidden" name="command" value="view3all">
		<input type="hidden" name="order" value="title">
		<select name="filter" class="forms">
		<option value="smart">Smart...</option>
		<option value="contains">Contains...</option>
		</select>
		<input type="text" name="title" maxlength="255" class="forms" style="width: 175px;">
	</form>
	</td>
	<td></td>
</tr>
<tr class="footer" style="height: 10px;"><td colspan="5"></td></tr>
</table>

<script type="text/javascript">
	<!--
	document.view1.artist.focus();
	//-->
</script>

<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View 1                                                                    |
//  +---------------------------------------------------------------------------+
function view1($artist, $genre_id, $filter)
{
global $cfg;

if ($genre_id)
	{
	if (substr($genre_id, -1) == '~')	$filter = substr($genre_id, 0, -1);
	else								$filter = $genre_id . '%';
	$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE genre_id LIKE "' . mysql_real_escape_string($filter) . '" GROUP BY artist_alphabetic');
	
	if (mysql_num_rows($query) == 1)
		{
		$album = mysql_fetch_array($query);
		view2('', $genre_id, $filter, 'year', 'asc', 'list');
		require_once('include/footer.inc.php');
		exit();
		}
	authenticate('access_browse');
	require_once('include/header.inc.php');
	GenreNavigator($genre_id);
	
	$expand_url		= 'browse.php?command=view2&amp;genre_id=' . $genre_id . '&amp;order=artist';
	$thumbnail_url	= 'browse.php?command=view2&amp;mode=thumbnail&amp;genre_id=' . $genre_id . '&amp;order=artist';
	}
else
	{
	$query = '';
	if ($filter == 'all')		$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE 1 GROUP BY artist_alphabetic');
	if ($filter == 'symbol')	$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE artist_alphabetic  NOT BETWEEN "a" AND "zzzzzz" GROUP BY artist_alphabetic');
	if ($filter == 'exact')		$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE artist_alphabetic = "' . mysql_real_escape_string($artist) . '" OR artist = "' . mysql_real_escape_string($artist) . '" GROUP BY artist_alphabetic');
	if ($filter == 'smart')		$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE artist_alphabetic  LIKE "' . mysql_real_escape_string($artist) . '%" OR artist LIKE "' . mysql_real_escape_string($artist) . '%" OR soundex(artist) = soundex("' . mysql_real_escape_string($artist) . '") GROUP BY artist_alphabetic');
	if ($filter == 'start')		$query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE artist_alphabetic  LIKE "' . mysql_real_escape_string($artist) . '%" GROUP BY artist_alphabetic');
	if ($filter == 'contains')  $query = mysql_query('SELECT artist_alphabetic, artist, album_id FROM album WHERE artist_alphabetic  LIKE "%' . mysql_real_escape_string($artist) . '%" OR artist LIKE "%' . mysql_real_escape_string($artist) . '%" GROUP BY artist_alphabetic');
	
	if (mysql_num_rows($query) == 1)
		{
		$album = mysql_fetch_array($query);
		view2($album['artist_alphabetic'], '', 'exact', 'year', 'asc', 'list');
		exit();
		}
	authenticate('access_browse');
	require_once('include/header.inc.php');
	
	//FormattedNavigator
	$name	= array('Browse');
	$url	= array('browse.php');
	if ($artist != '') $name[] = $artist;
	FormattedNavigator($url, $name);
	
	$expand_url		= 'browse.php?command=view2&amp;artist=' . rawurlencode($artist) . '&amp;filter=' . $filter . '&amp;order=artist';
	$thumbnail_url	= 'browse.php?command=view2&amp;mode=thumbnail&amp;artist=' . rawurlencode($artist) . '&amp;filter=' . $filter . '&amp;order=artist';
	}
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Artist</td>
	<td class="spacer"></td>
	<td align="right"><a href="<?php echo $thumbnail_url; ?>"><img src="<?php echo $cfg['img']; ?>/small_thumbnail.gif" alt="" width="21" height="21" border="0"></a></td>	
	<td align="right"><a href="<?php echo $expand_url; ?>"><img src="<?php echo $cfg['img']; ?>/small_expand.gif" alt="" width="21" height="21" border="0"></a></td>	
</tr>
<tr class="line"><td colspan="5"></td></tr>
<?php

$i = 0;
while ($album = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td colspan="3"><a href="browse.php?command=view2&amp;artist=<?php echo rawurlencode($album['artist_alphabetic']); ?>&amp;order=year"><?php echo htmlentities($album['artist_alphabetic']); ?></a></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View 2                                                                    |
//  +---------------------------------------------------------------------------+
function view2($artist, $genre_id, $filter, $order, $sort, $mode)
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

if ($mode == '') $mode	= 'list';
if ($sort == '') $sort	= 'asc';

$sort_artist			= 'asc';
$sort_album				= 'asc';
$sort_genre				= 'asc';
$sort_year 				= 'asc';

$order_bitmap_artist	= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_album		= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_genre		= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_year		= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';

if ($artist)
	{
	//FormattedNavigator
	$name	= array('Browse');
	$url	= array('browse.php');
	$name[] = $artist;
	FormattedNavigator($url, $name);

	if ($filter == '') 			$filter = 'exact';
	if ($filter == 'all')		$filter_query = 'WHERE 1';
	if ($filter == 'symbol')	$filter_query = 'WHERE (artist_alphabetic  NOT BETWEEN "a" AND "zzzzzz")';
	if ($filter == 'exact')		$filter_query = 'WHERE (artist_alphabetic = "' . mysql_real_escape_string($artist) . '" OR artist = "' . mysql_real_escape_string($artist) . '")';
	if ($filter == 'start')		$filter_query = 'WHERE (artist_alphabetic  LIKE "' . mysql_real_escape_string($artist) . '%")';
	if ($filter == 'smart')		$filter_query = 'WHERE (artist_alphabetic  LIKE "' . mysql_real_escape_string($artist) . '%" OR artist LIKE "' . mysql_real_escape_string($artist) . '%" OR soundex(artist) = soundex("' . mysql_real_escape_string($artist) . '"))';
	if ($filter == 'contains')  $filter_query = 'WHERE (artist_alphabetic  LIKE "%' . mysql_real_escape_string($artist) . '%" OR artist LIKE "%' . mysql_real_escape_string($artist) . '%")';

	if ($order == 'year' && $sort == 'asc')
		{
		$order_query = 'ORDER BY year, month, artist_alphabetic, album';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_year = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_year = 'desc';
		}
	if ($order == 'year' && $sort == 'desc')
		{
		$order_query = 'ORDER BY year DESC, month DESC, artist_alphabetic DESC, album DESC';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_year = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_year = 'asc';
		}
	if ($order == 'album' && $sort == 'asc')
		{
		$order_query = 'ORDER BY album, artist_alphabetic, year, month';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_album = 'desc';
		}
	if ($order == 'album' && $sort == 'desc')
		{
		$order_query = 'ORDER BY album DESC, artist_alphabetic DESC, year DESC, month DESC';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_album = 'asc';
		}
	if ($order == 'artist' && $sort == 'asc')
		{
		$order_query = 'ORDER BY artist_alphabetic, year, month, album';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_artist = 'desc';
		}
	if ($order == 'artist' && $sort == 'desc')
		{
		$order_query = 'ORDER BY artist_alphabetic DESC, year DESC, month DESC, album DESC';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
		$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_artist = 'asc';
		}
	if ($order == 'genre' && $sort == 'asc')
		{
		$order_query = 'ORDER BY genre, artist_alphabetic, year, month';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, album.genre_id, album_id FROM album, genre ' . $filter_query . ' AND album.genre_id = genre.genre_id ' . $order_query);
		$order_bitmap_genre = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_genre = 'desc';
		}
	if ($order == 'genre' && $sort == 'desc')
		{
		$order_query = 'ORDER BY genre DESC, artist_alphabetic DESC , year DESC, month DESC';
		$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, album.genre_id, album_id FROM album, genre ' . $filter_query . ' AND album.genre_id = genre.genre_id ' . $order_query);
		$order_bitmap_genre = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_genre = 'asc';
		}

	$url = 'browse.php?command=view2&amp;artist=' . rawurlencode($artist) . '&amp;filter=' . $filter;
	$expand_url	= 'browse.php?command=view2&amp;artist=' . rawurlencode($artist) . '&amp;filter=' . $filter . '&amp;order=' . $order . '&amp;sort=' . $sort;
	$thumbnail_url = 'browse.php?command=view2&amp;mode=thumbnail&amp;artist=' . rawurlencode($artist) . '&amp;filter=' . $filter . '&amp;order=' . $order . '&amp;sort=' . $sort;
	}

if ($genre_id)
	{
	GenreNavigator($genre_id);
	
	if (substr($genre_id, -1) == '~') 
		$filter = substr($genre_id, 0, -1);
	else
		$filter = $genre_id . '%';
	$filter_query = 'WHERE genre_id LIKE "' . mysql_real_escape_string($filter) . '"';
	
	if ($order == 'artist' && $sort == 'asc')
		{
		$order_query = 'ORDER BY artist_alphabetic, year, month, album';
		$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_artist = 'desc';
		}
	if ($order == 'artist' && $sort == 'desc')
		{
		$order_query = 'ORDER BY artist_alphabetic DESC, year DESC, month DESC, album DESC';
		$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_artist = 'asc';
		}
	if ($order == 'album' && $sort == 'asc')
		{
		$order_query = 'ORDER BY album, artist_alphabetic, year, month';
		$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_album = 'desc';
		}
	if ($order == 'album' && $sort == 'desc')
		{
		$order_query = 'ORDER BY album DESC, artist_alphabetic DESC, year DESC, month DESC';
		$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_album = 'asc';
		}
	if ($order == 'genre' && $sort == 'asc')
		{
		$order_query = 'ORDER BY genre_id, artist_alphabetic, year, month, album';
		$order_bitmap_genre = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_genre = 'desc';
		}
	if ($order == 'genre' && $sort == 'desc')
		{
		$order_query = 'ORDER BY genre_id DESC, artist_alphabetic DESC, year DESC, month DESC, album DESC';
		$order_bitmap_genre = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_genre = 'asc';
		}
	if ($order == 'year' && $sort == 'asc')
		{
		$order_query = 'ORDER BY year, month, artist_alphabetic, album';
		$order_bitmap_year = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_year = 'desc';
		}
	if ($order == 'year' && $sort == 'desc')
		{
		$order_query = 'ORDER BY year DESC, month DESC, artist_alphabetic DESC, album DESC';
		$order_bitmap_year = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
		$sort_year = 'asc';
		}
	
	$query = mysql_query('SELECT album, artist, artist_alphabetic, year, month, genre_id, album_id FROM album ' . $filter_query . ' ' . $order_query);
	$url = 'browse.php?command=view2&amp;genre_id=' . $genre_id;
	$expand_url = 'browse.php?command=view2&amp;genre_id=' . $genre_id . '&amp;filter=' . $filter . '&amp;order=' . $order;
	$thumbnail_url = 'browse.php?command=view2&amp;mode=thumbnail&amp;genre_id=' . $genre_id . '&amp;filter=' . $filter . '&amp;order=' . $order;
	}



//  +---------------------------------------------------------------------------+
//  | View 2 - List mode                                                        |
//  +---------------------------------------------------------------------------+
if ($mode == 'list')
{
?>
<form action="genre.php" method="post" target="main">
<input type="hidden" name="command" value="select">
<table class="border" border="0" cellspacing="0" cellpadding="0">
<tr class="header">
	<td width="4"></td><!-- spacer -->
	<td></td><!-- bitmap -->
	<td class="spacer"></td>
	<td></td><!-- optional play -->
	<td></td><!-- optional add -->
	<td<?php if ($cfg['access_play'] || $cfg['access_add']) echo' class="spacer"'; ?>></td>
	<td><a href="<?php echo $url; ?>&amp;order=artist&amp;sort=<?php echo $sort_artist; ?>">Arist<?php echo $order_bitmap_artist; ?></a></td>
	<td class="textspace"></td>
	<td><a href="<?php echo $url; ?>&amp;order=album&amp;sort=<?php echo $sort_album; ?>">Album<?php echo $order_bitmap_album; ?></a></td>
	<td class="textspace"></td>
	<td><a href="<?php echo $url; ?>&amp;order=genre&amp;sort=<?php echo $sort_genre; ?>">Genre<?php echo $order_bitmap_genre; ?></a></td>
	<td class="textspace"></td>
	<td><a href="<?php echo $url; ?>&amp;order=year&amp;sort=<?php echo $sort_year; ?>">Year<?php echo $order_bitmap_year; ?></a></td>
	<td align="right"><a href="<?php echo $thumbnail_url; ?>"><img src="<?php echo $cfg['img']; ?>/small_thumbnail.gif" alt="" width="21" height="21" border="0"></a></td>
</tr>
<tr class="line"><td colspan="14"></td></tr>
<?php

$i=0;
while ($album = mysql_fetch_array($query))
	{
	$genre_id = $album['genre_id'];
	$genre = mysql_fetch_array(mysql_query('SELECT genre FROM genre WHERE genre_id = "' . mysql_real_escape_string($genre_id) . '"'));
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td height="52"></td>
	<td><a href="browse.php?command=view3&amp;album_id=<?php echo $album['album_id']; ?>" onMouseOver="return overlib('<?php echo FormattedDate($album['year'], $album['month']); ?>');" onMouseOut="return nd();"><img src="image.php?album_id=<?php echo $album['album_id']; ?>&amp;size=50" alt="" width="50" height="50" border="0" class="align"></a></td>
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;album_id=' . $album['album_id'] . '" target="dummy" onMouseOver="return overlib(\'Play album\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/medium_play.gif" alt="" width="25" height="50" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_add']) echo '<a href="httpq.php?command=AddSelect&amp;album_id=' . $album['album_id'] . '" target="dummy" onMouseOver="return overlib(\'Add album\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/medium_add.gif" alt="" width="25" height="51" border="0"></a>'; ?></td>
	<td></td>
	<td><a href="browse.php?command=view2&amp;artist=<?php echo rawurlencode($album['artist_alphabetic']); ?>&amp;order=year"><?php echo htmlentities($album['artist_alphabetic']); ?></a></td>
	<td></td>
	<td><a href="browse.php?command=view3&amp;album_id=<?php echo $album['album_id']; ?>"><?php echo htmlentities($album['album']); ?></a></td>
	<td></td>
	<td><?php if ($cfg['access_config']) echo '<input type="checkbox" name="album_id_array[]" value="' . $album['album_id'] . '" class="space">'; ?><a href="browse.php?command=view1&amp;genre_id=<?php echo $album['genre_id']; ?>"><?php echo htmlentities($genre['genre']); ?></a></td>
	<td></td>
	<td><?php echo $album['year']; ?></td>
	<td></td>
</tr>
<?php
	}


if (mysql_num_rows(mysql_query('SELECT artist_alphabetic FROM album ' . $filter_query . ' GROUP BY artist_alphabetic')) < 2)
	{
	$album = mysql_fetch_array(mysql_query('SELECT artist FROM album ' . $filter_query));
	if ($album['artist'] == '') $album['artist'] = $artist;
	$tracks = mysql_num_rows(mysql_query('SELECT album_id from track where artist = "' . mysql_real_escape_string($album['artist']) . '"'));
?>
<tr class="line"><td colspan="14"></td></tr>
<tr class="footer">
	<td></td>
	<td colspan="9"><a href="browse.php?command=view3all&amp;artist=<?php echo rawurlencode($album['artist']); ?>&amp;order=title">View all <?php echo $tracks; ?> track(s) from <?php echo htmlentities($album['artist']); ?></a></td>
	<td colspan="5"><?php if ($cfg['access_config']) echo '<input type="image" src="' . $cfg['img'] . '/small_button_edit.gif" onMouseOver="return overlib(\'Edit selected genre(s)\');" onMouseOut="return nd();">'; ?></td>
</tr>
</table>
</form>
<?php
	}
else
	{
?>
<tr class="line"><td colspan="14"></td></tr>
<tr class="footer">
	<td></td>
	<td colspan="9"></td>
	<td colspan="5"><?php if ($cfg['access_config']) echo '<input type="image" src="' . $cfg['img'] . '/small_button_edit.gif" onMouseOver="return overlib(\'Edit selected genre(s)\');" onMouseOut="return nd();">'; ?></td>
</tr>
</table>
</form>
<?php
	}
}



//  +---------------------------------------------------------------------------+
//  | View 2 - Thumbnail mode                                                   |
//  +---------------------------------------------------------------------------+
elseif ($mode == 'thumbnail')
{
$size = get('size');
if (in_array($size, array('50', '100', '200')))
	{
	mysql_query('UPDATE configuration_session SET
				thumbnail_size		= "' . mysql_real_escape_string($size) . '"
				WHERE session_id	= "' . mysql_real_escape_string($cfg['session_id']) . '"');
	}
else
	{
	$query_size = mysql_query('SELECT thumbnail_size
								FROM configuration_session
								WHERE session_id = "' . mysql_real_escape_string($cfg['session_id']) . '"');
	$size = @mysql_result($query_size, 'thumbnail_size');
	}

$i			= 0;
$colombs	= floor((cookie('netjukebox_width') - 20) / ($size + 10));
$sort_url	= $url . '&amp;mode=thumbnail';
$size_url	= $url . '&amp;mode=thumbnail&amp;order=' . $order . '&amp;sort=' . $sort;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td colspan="<?php echo $colombs; ?>">
	<!-- ---- begin table header ---- -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="header">
		<td class="spacer"></td>
		<td>
			<a href="<?php echo $sort_url; ?>&amp;order=artist&amp;sort=<?php echo $sort_artist; ?>">Arist<?php echo $order_bitmap_artist; ?></a>
			&nbsp;<a href="<?php echo $sort_url; ?>&amp;order=album&amp;sort=<?php echo $sort_album; ?>">Album<?php echo $order_bitmap_album; ?></a>
			&nbsp;<a href="<?php echo $sort_url; ?>&amp;order=genre&amp;sort=<?php echo $sort_genre; ?>">Genre<?php echo $order_bitmap_genre; ?></a>
			&nbsp;<a href="<?php echo $sort_url; ?>&amp;order=year&amp;sort=<?php echo $sort_year; ?>">Year<?php echo $order_bitmap_year; ?></a>
		</td>
		<td width="22" align="right"><a href="<?php echo $size_url; ?>&amp;size=50"><img src="<?php echo $cfg['img']; ?>/small_image50_<?php if ($size == '50') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
		<td width="22" align="right"><a href="<?php echo $size_url; ?>&amp;size=100"><img src="<?php echo $cfg['img']; ?>/small_image100_<?php if ($size == '100') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
		<td width="22" align="right"><a href="<?php echo $size_url; ?>&amp;size=200"><img src="<?php echo $cfg['img']; ?>/small_image200_<?php if ($size == '200') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
		<td width="22" align="right"><a href="<?php echo $expand_url; ?>"><img src="<?php echo $cfg['img']; ?>/small_expand.gif" alt="" width="21" height="21" border="0"></a></td>
	</tr>
	</table>
	<!-- ---- end table header ---- -->
	</td>
</tr>
<tr class="line"><td colspan="<?php echo $colombs; ?>"></td></tr>
<?php
while ($album = mysql_fetch_array($query) AND $album)
	{
	$class = ($i++ & 1) ? 'even' : 'odd';
	echo '<tr class="' . $class . '">'. "\n";
	for($j=1; $j <= $colombs; $j++)
		{
		if ($j != 1) $album = mysql_fetch_array($query);
		if ($album)
			{
			$genre_id = $album['genre_id'];
			$genre = mysql_fetch_array(mysql_query('SELECT genre FROM genre WHERE genre_id = "' . mysql_real_escape_string($genre_id) . '"'));
			if (($colombs + 1) / $j > 2)		$alignment = 'RIGHT';
			elseif (($colombs + 1) / $j == 2)	$alignment = 'CENTER';
			else								$alignment = 'LEFT';
?>
	<td height="<?php echo $size + 20; ?>" align="center">
	<a href="browse.php?command=view3&amp;album_id=<?php echo $album['album_id']; ?>" onMouseOver="return overlib('<?php echo addslashes(htmlentities($album['album'])); ?><br><?php if ($genre['genre']) echo addslashes(htmlentities($genre['genre'])) . '<br>'; echo FormattedDate($album['year'], $album['month']); ?>', CAPTION, '<?php echo addslashes(htmlentities($album['artist'])); ?>', WIDTH, 200, <?php echo $alignment; ?>);" onMouseOut="return nd();"><img src="image.php?album_id=<?php echo $album['album_id']; ?>&amp;size=<?php echo $size; ?>" alt="" width="<?php echo $size; ?>" height="<?php echo $size; ?>" border="0"></a>
	</td>
<?php
			}
		else
			{
			echo '	<td height="' . ($size + 20) . '" align="center"><img src="images/dummy.gif" alt="" width="' . $size . '" height="' . $size . '" border="0"></td>' . "\n";
			}
		}
	echo '</tr>' . "\n";
	}
echo '</table>' . "\n";
}
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View 3                                                                    |
//  +---------------------------------------------------------------------------+
function view3($album_id)
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

$query = mysql_query('SELECT genre.genre,
					genre.genre_id
					FROM album, genre 
					WHERE album.genre_id = genre.genre_id
					AND album.album_id = "' . mysql_real_escape_string($album_id) . '"');
$genre = mysql_fetch_array($query);

$query = mysql_query('SELECT artist_alphabetic,
					artist,
					album,
					year,
					month
					FROM album
					WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$album = mysql_fetch_array($query);

//FormattedNavigator
$name	= array('Browse');
$url	= array('browse.php');
$name[] = $album['artist_alphabetic'];
$url[]  = 'browse.php?command=view2&amp;artist=' . rawurlencode($album['artist_alphabetic']) . '&amp;order=year';
$name[] = $album['album'];
FormattedNavigator($url, $name);

$option	= array();
if ($cfg['access_play'])	$option[] = '<a href="httpq.php?command=PlaySelect&amp;album_id=' . $album_id . '" target="dummy"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0" class="space">Play album</a>';
if ($cfg['access_add'])		$option[] = '<a href="httpq.php?command=AddSelect&amp;album_id=' . $album_id . '" target="dummy"><img src="' . $cfg['img'] . '/small_add.gif" alt="" width="21" height="21" border="0" class="space">Add album</a>';
if ($cfg['access_stream'])	$option[] = '<a href="stream.php?command=playlist&amp;album_id=' . $album_id . '"><img src="' . $cfg['img'] . '/small_stream.gif" alt="" width="21" height="21" border="0" class="space">Stream album</a>';
if ($cfg['access_cover'])	$option[] = '<a href="cover.php?command=download&amp;album_id='. $album_id . '"><img src="' . $cfg['img'] . '/small_pdf.gif" alt="" width="21" height="21" border="0" class="space">Download cover</a>';
							$option[] = '<a href="ridirect.php?url=http%3A%2F%2Fwww.allmusic.com%2Fcg%2Famg.dll&amp;P=amg&amp;OPT1=1&amp;SQL=' . rawurlencode($album['artist']) . '" target="_blank"><img src="' . $cfg['img'] . '/small_internet.gif" alt="" width="21" height="21" border="0" class="space">' . $album['artist'] . '</a>';
							$option[] = '<a href="ridirect.php?url=http%3A%2F%2Fwww.allmusic.com%2Fcg%2Famg.dll&amp;P=amg&amp;OPT1=2&amp;SQL=' . rawurlencode($album['album']) . '" target="_blank"><img src="' . $cfg['img'] . '/small_internet.gif" alt="" width="21" height="21" border="0" class="space">' . $album['album'] . '</a>';
if ($genre['genre_id'])		$option[] = '<a href="browse.php?command=view1&amp;genre_id=' . $genre['genre_id'] . '"><img src="' . $cfg['img'] . '/small_info.gif" alt="" width="21" height="21" border="0" class="space">' . $genre['genre'] . '</a>';
if ($album['year'])			$option[] = '<img src="' . $cfg['img'] . '/small_info.gif" alt="" width="21" height="21" border="0" class="space">' . FormattedDate($album['year'], $album['month']);
?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="border">
<tr class="odd">
	<td rowspan="8"><?php if ($cfg['access_cover']) echo'<a href="cover.php?command=inline&amp;album_id=' . $album_id . '"><img src="image.php?album_id=' . $album_id . '&amp;size=200" alt="" width="200" height="200" border="0" onMouseOver="return overlib(\'View cover\');" onMouseOut="return nd();"></a>'; else echo '<img src="image.php?album_id=' . $album_id .'&amp;size=200" alt="" width="200" height="200" border="0">'; ?></td>
	<td rowspan="8" class="vertical_line"></td>
	<td height="25" class="spacer"></td>
	<td><?php echo $option[0]; ?></td>	
	<td class="spacer"></td>
</tr>
<?php
for($i=1; $i < 8; $i++)
	{
?>
<tr class="<?php echo ($i & 1) ? 'even' : 'odd'; ?>">
	<td height="25"></td>
	<td><?php if (isset($option[$i])) echo $option[$i]; else echo '&nbsp;'; ?></td>
	<td></td>
</tr>
<?php
	}
if (mysql_fetch_array(mysql_query('SELECT featuring FROM track WHERE featuring <> "" AND album_id = "' . mysql_real_escape_string($album_id) . '"'))) $featuring = true;
else $featuring = false;
?>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td></td><!-- optional play -->
	<td></td><!-- optional add -->
	<td<?php if ($cfg['access_play'] || $cfg['access_add']) echo' class="spacer"'; ?>></td>
	<td>Artist</td>
	<td class="textspace"></td>
	<td>Title</td>
	<td class="textspace"></td>
	<td><?php if ($featuring) echo'Featuring'; ?></td><!-- optional featuring -->
	<td<?php if ($featuring) echo' class="textspace"'; ?>></td><!-- optional featuring -->
	<td align="right">Time</td>
	<td<?php if ($cfg['access_download'] or $cfg['access_stream']) echo' class="spacer"'; ?>></td>
	<td></td><!-- optional download -->
	<td></td><!-- optional stream -->
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="15"></td></tr>
<?php
$query = mysql_query('SELECT cds FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$album = mysql_fetch_array($query);
for ($cd=1; $cd <= $album['cds']; $cd++)
	{
	$query = mysql_query('SELECT artist, title, featuring, playtime, track_id FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" AND cd = "' . mysql_real_escape_string($cd) . '" ORDER BY relative_file');
	$i=0;
	while ($track = mysql_fetch_array($query))
		{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'Play track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_add'])  echo '<a href="httpq.php?command=AddSelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'Add track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_add.gif" alt="" width="21" height="21" border="0"></a>';?></td>
	<td></td>
	<td><?php if (mysql_num_rows(mysql_query('SELECT track_id FROM track WHERE artist="' . $track['artist'] . '"')) > 1) echo '<a href="browse.php?command=view2&amp;artist=' . rawurlencode($track['artist']) . '&amp;order=year" target="main">' . htmlentities($track['artist']) . '</a>'; else echo htmlentities($track['artist']); ?></td>
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'play track\');" onMouseOut="return nd();">' . htmlentities($track['title']) . '</a>';  else echo htmlentities($track['title']); ?></td>
	<td></td>
	<td><?php if ($track['featuring']) echo htmlentities($track['featuring']); ?></td>
	<td></td>
	<td align="right"><?php echo $track['playtime']; ?></td>
	<td></td>
	<td><?php if ($cfg['access_download']) echo '<a href="stream.php?command=download&amp;track_id=' . $track['track_id'] .'" ' . onMouseOverDownloadInfo($track['track_id']) . '><img src="' . $cfg['img'] . '/small_download.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_stream']) echo '<a href="stream.php?command=playlist&amp;track_id=' . $track['track_id'] . '" onMouseOver="return overlib(\'Stream track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_stream.gif" alt="" width="21" height="21" border="0"></a>';?></td>
	<td></td>
</tr>
<?php
		}
	$query = mysql_query('SELECT SUM(playtime_miliseconds) AS sum_playtime FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" AND cd = "' . mysql_real_escape_string($cd) . '"');
	$track = mysql_fetch_array($query);
?>
<tr class="line"><td colspan="15"></td></tr>
<tr class="footer">
	<td></td>
	<td colspan="2"><?php if ($cfg['access_record']) echo '<a href="record.php?album_id=' . $album_id . '&amp;cd=' . $cd . '" target="main"><img src="' . $cfg['img'] . '/small_record.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td></td>
	<td colspan="10"><?php if ($cfg['access_record']) echo '<a href="record.php?album_id=' . $album_id . '&amp;cd=' . $cd . '" target="main">Record ' . $i . ' tracks (' . FormattedTime($track['sum_playtime']) . ' playtime)</a>'; else echo $i . ' tracks (' . FormattedTime($track['sum_playtime']) . ' playtime)</font>';?></td>
	<td></td>
</tr>
<?php if ($cd < $album['cds']) echo '<tr class="line"><td colspan="15"></td></tr>' . "\n";
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View 1 All                                                                |
//  +---------------------------------------------------------------------------+
function view1all($artist, $filter)
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Browse');
$url	= array('browse.php');
$name[] = $artist;
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Artist</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="3"></td></tr>
<?php

$i = 0;
if ($filter == 'smart')		$query = mysql_query('SELECT artist FROM track WHERE artist LIKE "' . mysql_real_escape_string($artist) . '%" OR soundex(artist) = soundex("' . mysql_real_escape_string($artist) . '") GROUP BY artist');
if ($filter == 'contains')	$query = mysql_query('SELECT artist FROM track WHERE artist LIKE "%' . mysql_real_escape_string($artist) . '%" GROUP BY artist');

while ($track = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="browse.php?command=view3all&amp;artist=<?php echo rawurlencode($track['artist']); ?>&amp;order=title"><?php echo htmlentities($track['artist']); ?></a></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | View 3 All                                                                |
//  +---------------------------------------------------------------------------+
function view3all($artist, $title, $filter, $order, $sort)
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

if ($sort == '') $sort	= 'asc';

$sort_artist 			= 'asc';
$sort_title 			= 'asc';
$sort_featuring 		= 'asc';
$sort_album 			= 'asc';

$order_bitmap_artist	= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_title		= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_featuring = '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';
$order_bitmap_album		= '<img src="' . $cfg['img'] . '/small_sorting.gif" alt="" width="21" height="21" border="0" class="align">';

if ($title)
	{
	//FormattedNavigator
	$name	= array('Browse');
	$url	= array('browse.php');
	$name[] = $title;
	FormattedNavigator($url, $name);
	
	if ($filter == '')			$filter = 'start';
	if ($filter == 'start')		$filter_query = 'WHERE track.title LIKE "' . mysql_real_escape_string($title) . '%" AND track.album_id = album.album_id';
	if ($filter == 'smart')		$filter_query = 'WHERE (track.title LIKE "' . mysql_real_escape_string($title) . '%" OR soundex(track.title) = soundex("' . mysql_real_escape_string($title) . '")) AND track.album_id = album.album_id';
	if ($filter == 'contains')	$filter_query = 'WHERE track.title LIKE "%' . mysql_real_escape_string($title) . '%" AND track.album_id = album.album_id';
	$url = 'browse.php?command=view3all&amp;title=' . rawurlencode($title) . '&amp;filter=' . $filter;
	}
else //Artist or empty
	{
	//FormattedNavigator
	$name	= array('Browse');
	$url	= array('browse.php');
	$name[] = $artist;
	$url[]  = 'browse.php?command=view2&amp;artist=' . rawurlencode($artist) . '&amp;order=year';
	$name[] = 'All tracks';
	FormattedNavigator($url, $name);
	
	$filter_query = 'WHERE track.artist="' . mysql_real_escape_string($artist) . '" AND track.album_id = album.album_id';
	$url = 'browse.php?command=view3all&amp;artist=' . rawurlencode($artist);
	}

if ($order == 'artist' && $sort == 'asc')
	{
	$order_query = 'ORDER BY artist, title';
	$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_artist = 'desc';
	}
if ($order == 'artist' && $sort == 'desc')
	{
	$order_query = 'ORDER BY artist DESC, title DESC';
	$order_bitmap_artist = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_artist = 'asc';
	}
if ($order == 'title' && $sort == 'asc')
	{
	$order_query = 'ORDER BY title, album';
	$order_bitmap_title = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_title = 'desc';
	}
if ($order == 'title' && $sort == 'desc')
	{
	$order_query = 'ORDER BY title DESC, album DESC';
	$order_bitmap_title = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_title = 'asc';
	}
if ($order == 'featuring' && $sort == 'asc')
	{
	$order_query = 'ORDER BY featuring, title, artist';
	$order_bitmap_featuring = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_featuring = 'desc';
	}
if ($order == 'featuring' && $sort == 'desc')
	{
	$order_query = 'ORDER BY featuring DESC, title DESC, artist DESC';
	$order_bitmap_featuring = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_featuring = 'asc';
	}
if ($order == 'album' && $sort == 'asc')
	{
	$order_query = 'ORDER BY album, relative_file';
	$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_asc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_album = 'desc';
	}
if ($order == 'album' && $sort == 'desc')
	{
	$order_query = 'ORDER BY album DESC, relative_file DESC';
	$order_bitmap_album = '<img src="' . $cfg['img'] . '/small_sorting_desc.gif" alt="" width="21" height="21" border="0" class="align">';
	$sort_album = 'asc';
	}
if (mysql_fetch_array(mysql_query('SELECT featuring FROM track, album ' . $filter_query . ' AND featuring <> "" '))) $featuring = true;
else $featuring = false;
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td></td><!-- optional play -->
	<td></td><!-- optional add -->
	<td<?php if ($cfg['access_play'] or $cfg['access_add']) echo' class="spacer"'; ?>></td>
	<td><a href="<?php echo $url; ?>&amp;order=artist&amp;sort=<?php echo $sort_artist; ?>">Artist<?php echo $order_bitmap_artist; ?></a></td>
	<td class="textspace"></td>
	<td><a href="<?php echo $url; ?>&amp;order=title&amp;sort=<?php echo $sort_title; ?>">Title<?php echo $order_bitmap_title; ?></a></td>
	<td class="textspace"></td>
	<td><a href="<?php echo $url; ?>&amp;order=album&amp;sort=<?php echo $sort_album; ?>">Album<?php echo $order_bitmap_album; ?></a></td>
	<td class="textspace"></td>
	<td><?php if ($featuring) { ?><a href="<?php echo $url; ?>&amp;order=featuring&amp;sort=<?php echo $sort_featuring; ?>">Featuring<?php echo $order_bitmap_featuring; }; ?></td><!-- optional featuring -->
	<td<?php if ($featuring) echo' class="textspace"'; ?>></td><!-- optional featuring -->
	<td align="right">Time</td>
	<td<?php if ($cfg['access_download'] || $cfg['access_stream']) echo' class="spacer"'; ?>></td>
	<td></td><!-- optional download -->
	<td></td><!-- optional stream -->
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="17"></td></tr>
<?php
$i=0;
$query = mysql_query('SELECT track.artist, track.title, track.featuring, track.album_id, track.track_id, track.playtime, album.album FROM track, album ' . $filter_query . ' ' . $order_query);
while ($track = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'Play track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_add'])  echo '<a href="httpq.php?command=AddSelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'Add track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_add.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td></td>	
	<td><?php if (mysql_num_rows(mysql_query('SELECT track_id FROM track WHERE artist="' . $track['artist'] . '"')) > 1) echo '<a href="browse.php?command=view2&amp;artist=' . rawurlencode($track['artist']) . '&amp;order=year" target="main">' . htmlentities($track['artist']) . '</a>'; else echo htmlentities($track['artist']); ?></td>
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect&amp;track_id=' . $track['track_id'] . '" target="dummy" onMouseOver="return overlib(\'play track\');" onMouseOut="return nd();">' . htmlentities($track['title']) . '</a>'; else echo htmlentities($track['title']); ?></td>
	<td></td>
	<td><a href="browse.php?command=view3&amp;album_id=<?php echo $track['album_id']; ?>"><?php echo htmlentities($track['album']); ?></a></td>
	<td></td>
	<td><?php if ($track['featuring']) echo htmlentities($track['featuring']); ?></td>
	<td></td>
	<td align="right"><?php echo $track['playtime']; ?></td>
	<td></td>
	<td><?php if ($cfg['access_download']) echo '<a href="stream.php?command=download&amp;track_id=' . $track['track_id'] .'" ' . onMouseOverDownloadInfo($track['track_id']) . '><img src="' . $cfg['img'] . '/small_download.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_stream']) echo '<a href="stream.php?command=playlist&amp;track_id=' . $track['track_id'] . '" onMouseOver="return overlib(\'Stream track\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_stream.gif" alt="" width="21" height="21" border="0"></a>';?></td>
	<td></td>
</tr>
<?php
	}
$url = '';
if ($artist)	$url .= '&amp;artist=' . htmlentities($artist);
if ($title)		$url .= '&amp;title=' . htmlentities($title);
if ($filter)	$url .= '&amp;filter=' . $filter;
				$url .= '&amp;order=' . $order;
				$url .= '&amp;sort=' . $sort;
?>
<tr class="line"><td colspan="17"></td></tr>
<tr class="footer">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlaySelect' . $url . '" target="dummy" onMouseOver="return overlib(\'Play all tracks\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_play_dark.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td><?php if ($cfg['access_add'])  echo '<a href="httpq.php?command=AddSelect' . $url . '" target="dummy" onMouseOver="return overlib(\'Add all tracks\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_add_dark.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td colspan="12"></td>
	<td><?php if ($cfg['access_stream']) echo '<a href="stream.php?command=playlist' . $url . '" target="dummy" onMouseOver="return overlib(\'Stream all tracks\');" onMouseOut="return nd();"><img src="' . $cfg['img'] . '/small_stream_dark.gif" alt="" width="21" height="21" border="0"></a>';?></td>
	<td></td>
</tr>
</table>
<?php
require_once('include/footer.inc.php');
}
?>