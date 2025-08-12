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
//  | list.php                                                                  |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
authenticate('access_config');

$audio_dataformat 	= get('audio_dataformat');
$video_dataformat 	= get('video_dataformat');
$command	 		= get('command');

if ($audio_dataformat)
	$query = mysql_query('SELECT album.artist_alphabetic, album.album
						FROM track, album 
						WHERE track.audio_dataformat = "' . mysql_real_escape_string($audio_dataformat) . '"
						AND track.video_dataformat = ""
						AND track.album_id = album.album_id 
						GROUP BY album.album_id 
						ORDER BY album.artist_alphabetic, album.album');
elseif ($video_dataformat)
	$query = mysql_query('SELECT album.artist_alphabetic, album.album
						FROM track, album 
						WHERE track.video_dataformat = "' . mysql_real_escape_string($video_dataformat) . '"
						AND track.album_id = album.album_id 
						GROUP BY album.album_id 
						ORDER BY album.artist_alphabetic, album.album');
elseif ($command == 'all')
	$query = mysql_query('SELECT artist_alphabetic, album
						FROM album 
						ORDER BY artist_alphabetic, album');
elseif ($command == 'no_image')
	$query = mysql_query('SELECT album.artist_alphabetic, album.album
						FROM album, bitmap
						WHERE filemtime = "' . filemtime($cfg['home_dir'] . '/images/image.gif') . '"
						AND album.album_id = bitmap.album_id 
						ORDER BY album.artist_alphabetic, album.album');
elseif ($command == 'no_cd_front')
	$query = mysql_query('SELECT album.artist_alphabetic, album.album
						FROM album, bitmap
						WHERE cd_front = ""
						AND album.album_id = bitmap.album_id 
						ORDER BY album.artist_alphabetic, album.album');
elseif ($command == 'no_cd_back')
	$query = mysql_query('SELECT album.artist_alphabetic, album.album
						FROM album, bitmap
						WHERE cd_back = ""
						AND album.album_id = bitmap.album_id 
						ORDER BY album.artist_alphabetic, album.album');
else exit();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>netjukebox - list</title>
</head>
<body>
<table cellspacing="0" cellpadding="2" border="1">
<tr>
	<td align="center">nr</td>
	<td>Artist</td>
	<td>Album</td>
</tr>
<?php
$i = 1;
while ($album = mysql_fetch_array($query))
	{
?>
<tr>
	<td align="right"><?php echo $i++; ?></td>
	<td><?php echo htmlentities($album['artist_alphabetic']); ?></td>
	<td><?php echo htmlentities($album['album']); ?></td>
</tr>
<?php
	}
?>
</table>
</body>
</html>
	
