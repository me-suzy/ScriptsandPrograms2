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
//  | playlist.php                                                              |
//  +---------------------------------------------------------------------------+
ini_set('max_execution_time', '650');
require_once('include/initialize.inc.php');
authenticate('access_playlist', true, false);
require_once('include/httpq.inc.php');
require_once('include/header.inc.php');



//  +---------------------------------------------------------------------------+
//  | Update Repeat Status                                                      |
//  +---------------------------------------------------------------------------+
function UpdateRepeatStatus($status)
{
global $cfg;
if ($status == '1')	echo '<script type="text/javascript">document.getElementById(\'repeat\').innerHTML=\'<img src="' . $cfg['img'] . '/player_repeat_on.gif" alt="" width="50" height="21" border="0">\';</script>' . "\n";
else				echo '<script type="text/javascript">document.getElementById(\'repeat\').innerHTML=\'<img src="' . $cfg['img'] . '/player_repeat_off.gif" alt="" width="50" height="21" border="0">\';</script>' . "\n";
}



//  +---------------------------------------------------------------------------+
//  | Update Shuffle Status                                                     |
//  +---------------------------------------------------------------------------+
function UpdateShuffleStatus($status)
{
global $cfg;
if ($status == '1')	echo'<script type="text/javascript">document.getElementById(\'shuffle\').innerHTML=\'<img src="' . $cfg['img'] . '/player_shuffle_on.gif" alt="" width="50" height="21" border="0">\';</script>' . "\n";
else				echo'<script type="text/javascript">document.getElementById(\'shuffle\').innerHTML=\'<img src="' . $cfg['img'] . '/player_shuffle_off.gif" alt="" width="50" height="21" border="0">\';</script>' . "\n";
}



//  +---------------------------------------------------------------------------+
//  | Update Play Status                                                        |
//  +---------------------------------------------------------------------------+
function UpdatePlayStatus($status)
{
global $cfg;
if ($status == '0')	// stop
	{
	echo'<script type="text/javascript">document.getElementById(\'play\').innerHTML=\'<img src="' . $cfg['img'] . '/player_play_off.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	echo'<script type="text/javascript">document.getElementById(\'pause\').innerHTML=\'<img src="' . $cfg['img'] . '/player_pause_off.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	}
if ($status == '1') // play
	{
	echo'<script type="text/javascript">document.getElementById(\'play\').innerHTML=\'<img src="' . $cfg['img'] . '/player_play_on.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	echo'<script type="text/javascript">document.getElementById(\'pause\').innerHTML=\'<img src="' . $cfg['img'] . '/player_pause_off.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	}
if ($status == '3') // pause
	{
	echo'<script type="text/javascript">document.getElementById(\'play\').innerHTML=\'<img src="' . $cfg['img'] . '/player_play_off.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	echo'<script type="text/javascript">document.getElementById(\'pause\').innerHTML=\'<img src="' . $cfg['img'] . '/player_pause_on.gif" alt="" width="30" height="21" border="0">\';</script>' . "\n";
	}
}



$listpos		= httpq('getlistpos');
$listlength		= httpq('getlistlength');
$files			= httpq('getplaylistfile', 'delim=*');
$file			= explode('*', $files);

$featuring		= false;
for($i=0; $i < $listlength && !$featuring; $i++)
	{
	$relative_file = str_replace('\\', '/', $file[$i]);
	$relative_file = substr($relative_file, strlen($cfg['media_share']));
	$query = mysql_query('SELECT featuring FROM track WHERE featuring != "" AND relative_file = "' . mysql_real_escape_string($relative_file) . '"');
	if (mysql_fetch_array($query)) $featuring = true;
	}


$relative_file = str_replace('\\', '/', $file[$listpos]);
$relative_file = substr($relative_file, strlen($cfg['media_share']));
$query = mysql_query('SELECT artist, title, featuring, playtime, playtime_miliseconds, album_id FROM track WHERE relative_file = "' . mysql_real_escape_string($relative_file) . '"');
$track = mysql_fetch_array($query);
$query = mysql_query('SELECT artist, album, year FROM album WHERE album_id = "' . mysql_real_escape_string($track['album_id']) . '"');
$album = mysql_fetch_array($query);

if ($track['album_id'])	$image = '<a href="index.php?menu=browse&amp;command=view3&amp;album_id=' . $track['album_id'] . '" target="_top"><img src="image.php?album_id=' . $track['album_id'] . '&amp;size=100" alt="" width="100" height="100" border="0"></a>';
else
	{
	$stream = false;
	foreach($cfg['stream_prefix'] as $stream_prefix)
			If (strtolower(substr($file[$listpos], 0, strlen($stream_prefix))) == $stream_prefix)
				{
				$stream = true;
				break;
				}
	if ($stream)	$image = '<a href="http://www.shoutcast.com" target="main"><img src="images/image_stream.gif" alt="" width="100" height="100" border="0"></a>';
	else			$image = '<img src="images/image_notindatabase.gif" alt="" width="100" height="100" border="0">';
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="odd">
	<td width="100" height="100" rowspan="4"><?php echo $image; ?></td>
	<td rowspan="4" class="vertical_line"><img src="images/dummy.gif" alt="" width="1" height="1" border="0"></td>
	<td><img src="images/dummy.gif" alt="" width="10" height="21" border="0"></td>
	<td><font class="small">Artist:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
	<td width="100%"><?php echo htmlentities($track['artist']); ?></td>
</tr>
<tr class="even">
	<td></td>
	<td><font class="small">Title:</font></td>
	<td><?php echo htmlentities($track['title']); ?></td>
</tr>
<tr class="odd">
	<td></td>
	<td><font class="small">Album:</font></td>
	<td><?php echo htmlentities($album['album']); ?></td>
</tr>
<tr class="even">
	<td></td>
	<td><font class="small">By:</font></td>
	<td><?php if ($track['artist'] != $album['artist'] && !in_array(strtolower($album['artist']), array('various', 'singles', 'compilation', 'remix', 'video', 'movie', 'dvd', 'radio', 'tv'))) echo htmlentities($album['artist']); ?></td>
</tr>
<tr>
	<td colspan="5" class="line"></td>
</tr>
<tr>
	<td colspan="5" class="footer">
	<!-- ---- begin controll bar ---- -->
	<table border="0" cellspacing="0" cellpadding="0">
		<?php if ($cfg['access_play'])
			{ ?>
	<tr class="footer" style="height: auto;">			
		<td rowspan="3" onClick="dummy.location.href='httpq.php?command=ToggleShuffle'" onMouseOver="style.cursor='hand'"><div id="shuffle"><img src="<?php echo $cfg['img']; ?>/player_shuffle.gif" alt="" width="50" height="21" border="0"></div></td>
		<td rowspan="3" onClick="dummy.location.href='httpq.php?command=ToggleRepeat'" onMouseOver="style.cursor='hand'"><div id="repeat"><img src="<?php echo $cfg['img']; ?>/player_repeat.gif" alt="" width="50" height="21" border="0"></div></td>
		<td rowspan="3"><a href="httpq.php?command=prev" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_previous.gif" alt="" width="30" height="21" border="0"></a></td>
		<td rowspan="3" onClick="dummy.location.href='httpq.php?command=play'" onMouseOver="style.cursor='hand'"><div id="play"><img src="<?php echo $cfg['img']; ?>/player_play.gif" alt="" width="30" height="21" border="0"></div></td>
		<td rowspan="3" onClick="dummy.location.href='httpq.php?command=pause'" onMouseOver="style.cursor='hand'"><div id="pause"><img src="<?php echo $cfg['img']; ?>/player_pause.gif" alt="" width="30" height="21" border="0"></div></td>
		<td rowspan="3"><a href="httpq.php?command=stop" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_stop.gif" alt="" width="30" height="21" border="0"></a></td>
		<td rowspan="3"><a href="httpq.php?command=next" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_next.gif" alt="" width="30" height="21" border="0"></a></td>
		
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_left.gif" alt="" width="5" height="21" border="0"></td>
		<td><a href="httpq.php?command=SeekImageMap&amp;dx=200&amp;xy=" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_progress_up.gif" alt="" width="200" height="5" border="0" ismap></a></td>
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_right.gif" alt="" width="5" height="21" border="0"></td>
		<td width="50" rowspan="3" align="center" style="background-image: url(<?php echo $cfg['img']; ?>/player_playtime.gif);"><div id="time"></div></td>
		
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_left.gif" alt="" width="5" height="21" border="0"></td>
		<td><a href="httpq.php?command=VolumeImageMap&amp;dx=200&amp;xy=" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_progress_up.gif" alt="" width="200" height="5" border="0" ismap></a></td>
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_right.gif" alt="" width="5" height="21" border="0"></td>
		<td width="50" rowspan="3" align="center" style="background-image: url(<?php echo $cfg['img']; ?>/player_playtime.gif);"><div id="volume"></div></td>
	</tr>
	<tr>
		<td height="5" style="background-image: url(<?php echo $cfg['img']; ?>/player_progress_background.gif);"><div id="timebar"></div></td>
		<td height="5" style="background-image: url(<?php echo $cfg['img']; ?>/player_progress_background.gif);"><div id="volumebar"></div></td>
	</tr>
	<tr>
		<td width="200"><a href="httpq.php?command=SeekImageMap&amp;dx=200&amp;xy=" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_progress_down.gif" alt="" width="200" height="11" border="0" ismap></a></td>
		<td width="200"><a href="httpq.php?command=VolumeImageMap&amp;dx=200&amp;xy=" target="dummy"><img src="<?php echo $cfg['img']; ?>/player_progress_down.gif" alt="" width="200" height="11" border="0" ismap></a></td>
	</tr>
		<?php
			}
		else
			{ ?>
	<tr class="footer" style="height: auto;">						
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_back_left.gif" alt="" width="5" height="21" border="0"></td>
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_back.gif" alt="" width="90" height="21" border="0"></td>
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_back_right.gif" alt="" width="5" height="21" border="0"></td>
		
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_left.gif" alt="" width="5" height="21" border="0"></td>
		<td><img src="<?php echo $cfg['img']; ?>/player_progress_up.gif" alt="" width="200" height="5" border="0" ismap></td>
		<td rowspan="3"><img src="<?php echo $cfg['img']; ?>/player_progress_right.gif" alt="" width="5" height="21" border="0"></td>
		<td width="50" rowspan="3" align="center" style="background-image: url(<?php echo $cfg['img']; ?>/player_playtime.gif);"><div id="time"></div></td>
	<tr>
		<td height="5" style="background-image: url(<?php echo $cfg['img']; ?>/player_progress_background.gif);"><div id="timebar"></div></td>
	</tr>
	<tr>
		<td width="200"><img src="<?php echo $cfg['img']; ?>/player_progress_down.gif" alt="" width="200" height="11" border="0" ismap></td>
	</tr>
		<?php
			} ?>
	</table>
	<!-- ---- end controll bar ---- -->
	</td>
</tr>
</table>

<br><br>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Artist</td>
	<td class="textspace"></td>
	<td>Title</td>
	<td class="textspace"></td>
	<td><?php if ($featuring) echo'Featuring'; ?></td><!-- optional featuring -->
	<td<?php if ($featuring) echo' class="textspace"'; ?>></td>
	<td></td><!-- optional delete -->
	<td width="40" align="right">Time</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="10"></td></tr>
<?php
for($i=0; $i < $listlength; $i++)
	{
	$relative_file = str_replace('\\', '/', $file[$i]);
	$relative_file = substr($relative_file, strlen($cfg['media_share']));
	$query = mysql_query('SELECT title, artist, featuring, playtime FROM track WHERE relative_file = "' . mysql_real_escape_string($relative_file) . '"');
	$table_track = mysql_fetch_array($query);
	if (!isset($table_track['artist']))
		{
		$table_track['artist']	= 'file/stream';
		$table_track['title']	= $file[$i];
		}
?>
<tr class="<?php if ($i == $listpos) echo 'select'; else echo ($i & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlayIndex&amp;index=' . $i . '" target="dummy"><img src="' . $cfg['img'] . '/small_play.gif" alt="" width="21" height="21" border="0" class="space">' . htmlentities($table_track['artist']); else echo htmlentities($table_track['artist']);?></a></td>
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=PlayIndex&amp;index=' . $i . '" target="dummy">' . htmlentities($table_track['title']); else echo htmlentities($table_track['title']);?></a></td>
	<td></td>
	<td><?php if (isset($table_track['featuring'])) echo htmlentities($table_track['featuring']); ?></td>
	<td></td>
	<td><?php if ($cfg['access_play']) echo '<a href="httpq.php?command=DeleteIndex&amp;index=' . $i . '" target="dummy"><img src="' . $cfg['img'] . '/small_delete.gif" alt="" width="21" height="21" border="0"></a>'; ?></td>
	<td align="right"><?php if (isset($table_track['playtime'])) echo $table_track['playtime']; ?></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
$cfg['footer'] = 'dynamic';
require('include/footer.inc.php');


$refresh 					= false;
$previous_width				= -1; //forse update
$previous_volume			= -1;
$previous_time 				= -1;
$previous_repeat_status		= -1;
$previous_shuffle_status	= -1;
$previous_play_status		= -1;
for ($n = 0; ($n < 500) && !$refresh; $n++) // ($n < 500) Prevent script timeout
	{
	$miliseconds = httpq('getoutputtime', 'frmt=0');
	$time = FormattedTime($miliseconds);
	if ($track['playtime_miliseconds'] > 0) $width = round(200 * $miliseconds / $track['playtime_miliseconds']); else $width = 0;
	if ($width > 200) $width = 200;
	if ($previous_time != $time) 
		{
		echo '<script type="text/javascript">document.getElementById(\'time\').innerHTML=\'' . $time . '\';</script>' . "\n";
		$previous_time = $time;
		}
	if ($previous_width != $width)
		{
		if ($width > 0) echo '<script type="text/javascript">document.getElementById(\'timebar\').innerHTML=\'<img src="' . $cfg['img'] . '/player_progress_on.gif" alt="" width="' . $width . '" height="5" border="0">\';</script>' . "\n";
		else echo '<script type="text/javascript">document.getElementById(\'timebar\').innerHTML=\'\';</script>' . "\n";
		$previous_width	= $width;
		}
	$volume = httpq('getvolume');
	if ($cfg['access_play'] && $previous_volume != $volume)
		{
		$previous_volume	= $volume;
		$volume_percentage	= round(100 * $volume / 255);
		$volume				= round(200 * $volume / 255);
		echo '<script type="text/javascript">document.getElementById(\'volume\').innerHTML=\'' . $volume_percentage . '%\'; </script>' . "\n";
		if ($volume > 0) echo '<script type="text/javascript">document.getElementById(\'volumebar\').innerHTML=\'<img src="' . $cfg['img'] . '/player_progress_on.gif" alt="" width="' . $volume . '" height="5" border="0">\';</script>' . "\n";
		else echo '<script type="text/javascript">document.getElementById(\'volumebar\').innerHTML=\'\';</script>' . "\n";
		}
	if ($cfg['access_play'] && httpq('repeat_status') != $previous_repeat_status)		UpdateRepeatStatus($previous_repeat_status = httpq('repeat_status'));
	if ($cfg['access_play'] && httpq('shuffle_status') != $previous_shuffle_status)	UpdateShuffleStatus($previous_shuffle_status = httpq('shuffle_status'));
	if ($cfg['access_play'] && httpq('isplaying') != $previous_play_status)			UpdatePlayStatus($previous_play_status = httpq('isplaying'));
	flush();
	sleep(1);
	if (httpq('getlistpos') != $listpos)					$refresh = true;
	if (httpq('getlistlength') != $listlength)				$refresh = true;
	if (httpq('getplaylistfile', 'delim=*') != $files)		$refresh = true;
	}
?>
<meta http-equiv="refresh" content="0;URL=playlist.php">
