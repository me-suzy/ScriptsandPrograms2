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
//  | stream.php                                                                |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');

$command		= get('command');
$track_id		= get('track_id');
$album_id		= get('album_id');

if ($command == 'playlist')			playlist($track_id);
if ($command == 'stream')			stream($track_id);
if ($command == 'download')			download($track_id);

exit();



//  +---------------------------------------------------------------------------+
//  | Create stream playlist                                                    |
//  +---------------------------------------------------------------------------+
function playlist($track_id)
{
global $cfg;
authenticate('access_stream', false, false);

$album_id	= get('album_id');
$artist		= get('artist');
$title		= get('title');
$filter		= get('filter');
$order		= get('order');
$sort		= get('sort');

header('Content-type: audio/x-mpegurl');
header('Content-disposition: inline; filename=playlist.m3u');

$query 		= mysql_query('SELECT stream_id FROM configuration_session WHERE session_id = "' . $cfg['session_id'] . '"');
$stream_id	= @mysql_result($query, 'stream_id');

if ($track_id)
	{
	$query 	= mysql_query('SELECT playtime_miliseconds FROM track WHERE track_id = "' . mysql_real_escape_string($track_id) . '"');
	$track 	= mysql_fetch_array($query);
	$query	= mysql_query('SELECT artist, title, relative_file, playtime_miliseconds, track_id FROM track WHERE track_id = "' . mysql_real_escape_string($track_id) . '"');
	}
elseif ($album_id)
	{
	$query 	= mysql_query('SELECT SUM(playtime_miliseconds) AS sum_playtime FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
	$track	= mysql_fetch_array($query);
	$query	= mysql_query('SELECT artist, title, relative_file, playtime_miliseconds, track_id FROM track WHERE album_id = "' . mysql_real_escape_string($album_id) . '" ORDER BY relative_file');
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
	
	$query 	= mysql_query('SELECT SUM(track.playtime_miliseconds) AS sum_playtime FROM track, album ' . $filter_query);
	$track	= mysql_fetch_array($query);
	$query	= mysql_query('SELECT track.artist, track.title, track.relative_file, track.playtime_miliseconds, track.track_id FROM track, album ' . $filter_query . ' ' . $order_query);
	}

echo '#EXTM3U' . "\r\n";
while ($track = mysql_fetch_array($query))
	{
	$hash = hmacsha1($cfg['secret'], $cfg['session_id'] . $track['track_id'] . $stream_id);
	
	if ($stream_id == -1)
		$stream_extension = substr(strrchr($track['relative_file'], '.'), 1);
	else
		$stream_extension = $cfg['stream_extension'][$stream_id];
	
	echo '#EXTINF:' . round($track['playtime_miliseconds'] / 1000) . ',' . $track['artist'] . ' - ' . $track['title'] . "\r\n";
	echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?command=stream&stream_id=' . $stream_id . '&track_id=' . $track['track_id'] . '&session_id=' . $cfg['session_id'] . '&hash=' . $hash . '&ext=.' . strtolower($stream_extension) . "\r\n";
	}
}



//  +---------------------------------------------------------------------------+
//  | Stream                                                                    |
//  +---------------------------------------------------------------------------+
function stream($track_id)
{
global $cfg;

$stream_id			= get('stream_id');
$track_id			= get('track_id');
$session_id			= get('session_id');
$hash				= get('hash');
$stream_extension	= get('ext');

ini_set('max_execution_time', 3600 * 2);

$query 				= mysql_query('SELECT logged_in, idle_time, user_id, ip, secret FROM configuration_session WHERE session_id = "' . mysql_real_escape_string($session_id) . '"');
$session			= mysql_fetch_array($query);
$query 				= mysql_query('SELECT password, access_stream FROM configuration_users WHERE user_id = "' . $session['user_id'] . '"');
$users 				= mysql_fetch_array($query);

if (	$hash == hmacsha1($session['secret'], $session_id . $track_id . $stream_id) &&
		$session['logged_in'] &&
		$session['idle_time'] + $cfg['authenticate_expire'] > time() &&
		$session['ip'] == $_SERVER['REMOTE_ADDR'] &&
		$users['access_stream'] &&
		$users['password'] != '' &&
		$users['password'] != 'd41d8cd98f00b204e9800998ecf8427e')
	{
	$query = mysql_query('SELECT artist, title, relative_file, playtime_miliseconds, file_size, audio_bitrate, audio_bits_per_sample, audio_sample_rate, audio_channels FROM track WHERE track_id = "' . mysql_real_escape_string($track_id) . '"');
	$track = mysql_fetch_array($query);
	
	$file 		= $cfg['media_dir'] . $track['relative_file'];
	$extension	= substr(strrchr($track['relative_file'], '.'), 1);
	$extension	= strtolower($extension);
	
	if (isset($cfg['mime_type'][$stream_extension]))	$mime_type = $cfg['mime_type'][$stream_extension];
	else 												$mime_type = $stream_extension;
	
	header('ICY 200 OK');
	header('icy-notice2: netjukebox ' . $cfg['netjukebox_version'] . '<BR>');
	header('icy-name: ' . $track['title']);
	header('Content-Disposition: inline; filename=' . $track['artist'] . ' - ' . $track['title']);
	header('Content-Type: ' . $mime_type);
	
	if (file_exists($cfg['home_dir'] . '/-'))
		@unlink($cfg['home_dir'] . '/-');
	if ($stream_id == -1 || $extension == $cfg['stream_extension'][$stream_id] && $cfg['stream_transcode_treshold'][$stream_id] >= $track['audio_bitrate'])
		{
		header('Content-Length: ' . $track['file_size']);
		header('Accept-Ranges: bytes');
		
		if (version_compare(phpversion(), '5.0.0', '>='))
			{
			$filehandle = @fopen($file, 'rb') or exit();
			while (!feof($filehandle))
				echo fread($filehandle, 1024 * 1024);
			fclose($filehandle);
			}
		else
			@readfile($file);
		}
	else
		{
		if ($cfg['windows'])
			$file = str_replace('/', '\\', $file);
		if ($track['audio_channels'] > $cfg['stream_max_channels'][$stream_id])
			$track['audio_channels'] = $cfg['stream_max_channels'][$stream_id];
		$cmd = $cfg['decode_stdout'][$extension] . ' | ' . $cfg['stream_encode'][$stream_id];
		$cmd = str_replace('%source', '"' . $file . '"', $cmd);
		$cmd = str_replace('%artist', '"' . $track['artist'] . '"', $cmd);
		$cmd = str_replace('%title', '"' . $track['title'] . '"', $cmd);
		$cmd = str_replace('%comment', '"netjukebox ' . $cfg['netjukebox_version'] . '"', $cmd);
		$cmd = str_replace('%bits_per_sample', $track['audio_bits_per_sample'], $cmd);
		$cmd = str_replace('%sample_rate', $track['audio_sample_rate'], $cmd);
		$cmd = str_replace('%channels', $track['audio_channels'], $cmd);
		
		header('Accept-Ranges: none');
		@passthru($cmd);
		}
	exit();
	}
else
	{
	header('Status: 403 Forbidden');
	header('HTTP/1.0 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
	}
}



//  +---------------------------------------------------------------------------+
//  | Download                                                                  |
//  +---------------------------------------------------------------------------+
function download($track_id)
{
global $cfg;
authenticate('access_download', true, false);
ini_set('max_execution_time', $cfg['download_timeout']);

$query = mysql_query('SELECT relative_file, playtime_miliseconds, file_size FROM track WHERE track_id = "' . mysql_real_escape_string($track_id) . '"');
$track = mysql_fetch_array($query);

$file = $cfg['media_dir'] . $track['relative_file'];

if (isset($cfg['download_longfilename']) && $cfg['download_longfilename'])
	{
	$temp		= substr($track['relative_file'], 1);
	$temp   	= explode('/', $temp);
	$filename	= $temp[count($temp) - 3] . ' - ' . $temp[count($temp) - 2] . ' - ' . $temp[count($temp) - 1];
	}
else
	{
	$pathinfo = pathinfo($track['relative_file']);
	$filename = $pathinfo['basename'];
	}

header('Content-Type: application/force-download');
header('Content-Transfer-Encoding: binary');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: '. $track['file_size']); 

if (version_compare(phpversion(), '5.0.0', '>='))
	{
	$filehandle = @fopen($file, 'rb') or exit();
	while (!feof($filehandle))
		echo fread($filehandle, 1024 * 1024);
	fclose($filehandle);
	}
else
	@readfile($file);
}
?>