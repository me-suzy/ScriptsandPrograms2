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
//  | record.php                                                                |
//  +---------------------------------------------------------------------------+
ini_set('max_execution_time', '3600');
require_once('include/initialize.inc.php');
authenticate('access_record');
require_once('include/header.inc.php');

temp('create');

$album_id	= get('album_id');
$cd 		= get('cd') or $cd = 1;

$album = mysql_fetch_array(mysql_query('SELECT album_id FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"'));
if (!$album['album_id']) message('error', '<strong>Album id not found:</strong><br>' . $album['album_id']);

$cuefile  = $cfg['temp'] . $cfg['slash'] . $album_id . '_cd' . $cd . '.cue';

//FormattedNavigator
$url = $name = array();
$name[]	= 'Browse';
$url[]	= 'browse.php';
$name[] = 'Record';
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Extract</td>
	<td class="textspace"></td>
	<td></td>
	<td class="textspace"></td>
	<td></td><!-- status -->
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="7"></td></tr>
<?php
$filehandle = fopen($cuefile, 'w');
$album = mysql_fetch_array(mysql_query('SELECT artist, album FROM album WHERE album_id = "' . mysql_real_escape_string($album_id) . '"'));
fwrite($filehandle, 'PERFORMER "' . $album['artist'] . '"' . "\r\n");
fwrite($filehandle, 'TITLE "' . $album['album'] . '"' . "\r\n");
$i=0;
$query = mysql_query('SELECT title, artist, relative_file, track_id FROM track WHERE album_id = "' . mysql_real_escape_string($album_id). '" AND cd = "' . mysql_real_escape_string($cd) . '" ORDER BY relative_file');
while ($track = mysql_fetch_array($query))
	{
	$i++;
	
	fwrite($filehandle, 'FILE "' . $cfg['temp'] . $cfg['slash'] . $track['track_id'] . '.wav" WAVE' . "\r\n");
	fwrite($filehandle, '  TRACK ' . $i . ' AUDIO' . "\r\n");
	fwrite($filehandle, '    PERFORMER "' . $track['artist'] . '"' . "\r\n");
	fwrite($filehandle, '    TITLE "' . $track['title'] . '"' . "\r\n");
	fwrite($filehandle, '    INDEX 01 00:00:00' . "\r\n");
?>
<tr class="<?php echo ($i & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php echo htmlentities($track['artist']); ?></td>
	<td></td>
	<td><?php echo htmlentities($track['title']); ?></td>
	<td></td>
	<td align="center"><div id="status<?php echo $i; ?>"></div></td>
	<td></td>
</tr>
<?php
	}
?>
<tr class="line"><td colspan="7"></td></tr>
<tr class="footer">
	<td></td>
	<td colspan="4">Record to disc</td>
	<td align="center"><div id="record"></div></td>
	<td></td>
</tr>
</table>
<?php
$cfg['footer'] = 'dynamic';
require_once('include/footer.inc.php');
flush();
fclose($filehandle);

$i = 0;
$query = mysql_query('SELECT relative_file, track_id FROM track WHERE album_id = "' . mysql_real_escape_string($album_id). '" AND cd = "' . mysql_real_escape_string($cd) . '" ORDER BY relative_file');
while ($track = mysql_fetch_array($query))
	{
	$i++;
	echo '<script type="text/javascript">document.getElementById(\'status' . $i . '\').innerHTML=\'<img src="' . $cfg['img'] . '/animated_progress.gif" alt="" width="19" height="13" border="0">\';</script>' . "\n";
	flush();
	
	$source = $cfg['media_dir'] . $track['relative_file'];
	if ($cfg['windows'])
		$source = str_replace('/', '\\', $source);
	
	$extension	= substr(strrchr($source, '.'), 1);
	$extension	= strtolower($extension);
	
	$destination = $cfg['temp'] . $cfg['slash'] . $track['track_id'] . '.wav';
	
	$decode = $cfg['decode_stdout'][$extension];
	$decode = str_replace('%source', '"' . $source . '"', $decode);
	$decode = $decode . ' > "' . $destination . '"';
	exec($decode);
	
	if (file_exists($destination) && filesize($destination) > 0)	echo '<script type="text/javascript">document.getElementById(\'status' . $i . '\').innerHTML=\'<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">\';</script>' . "\n";
	else															echo '<script type="text/javascript">document.getElementById(\'status' . $i . '\').innerHTML=\'<img src="' . $cfg['img'] . '/small_error.gif" alt="" width="21" height="21" border="0">\';</script>' . "\n";
	flush(); 
	}


echo '<script type="text/javascript">document.getElementById(\'record\').innerHTML=\'<img src="' . $cfg['img'] . '/animated_record.gif" alt="" width="19" height="13" border="0">\';</script>' . "\n";
flush();

$record  = str_replace('%cuefile', '"' . $cuefile . '"', $cfg['record']);
exec($record);
temp('delete');

echo '<script type="text/javascript">document.getElementById(\'record\').innerHTML=\'<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">\';</script>' . "\n";
$cfg['footer'] = 'close';
require('include/footer.inc.php');
?>
