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
//  | statistics.php                                                            |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
authenticate('access_config');
require_once('include/header.inc.php');


$query = mysql_query('SELECT COUNT(cds) AS albums, SUM(cds) AS cds FROM album');
$album = mysql_fetch_array($query);
$query = mysql_query('SELECT COUNT(relative_file) AS all_tracks,
						SUM(playtime_miliseconds) AS sum_playtime,
						SUM(file_size) AS sum_size,
						SUM(audio_raw_decoded) AS audio_raw_decoded
						FROM track');
$track = mysql_fetch_array($query);
$sum_total_playtime = $track['sum_playtime'];

//FormattedNavigator
$name	= array('Configuration');
$url	= array('config.php');
$name[]	= 'Statistics';
FormattedNavigator($url, $name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Quantity:</td>
	<td width="100"></td>
	<td width="100"></td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<tr class="odd">
	<td></td>
	<td>Number of albums:</td>
	<td align="right"><?php echo $album['albums']; ?></td>
	<td></td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td>Number of discs:</td>
	<td align="right"><?php echo $album['cds']; ?></td>
	<td></td>
	<td></td>
</tr>
<tr class="odd">
	<td></td>	
	<td>Number of tracks:</td>
	<td align="right"><?php echo $track['all_tracks']; ?></td>
	<td></td>
	<td></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<tr class="header">
	<td></td>
	<td colspan="3">Filesize:</td>
	<td></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<tr class="odd">
	<td></td>
	<td>Total filesize:</td>
	<td align="right"><?php echo FormattedSize($track['sum_size']); ?></td>
	<td></td>
	<td></td>	
</tr>
<tr class="even">
	<td></td>
	<td>Total decoded audio:</td>
	<td align="right"><?php echo FormattedSize($track['audio_raw_decoded'] + $track['all_tracks'] * 44); /* 44 bytes for wave header */ ?></td>
	<td></td>
	<td></td>	
</tr>
<tr class="line"><td colspan="5"></td></tr>
<tr class="header">
	<td></td>
	<td colspan="3">Playtime:</td>
	<td></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<?php
$i = 0;
$query = mysql_query('SELECT audio_dataformat FROM track WHERE audio_dataformat <> "" AND video_dataformat = "" GROUP BY audio_dataformat ORDER BY audio_dataformat');
while($track = mysql_fetch_array($query))
	{
	$audio_dataformat = $track['audio_dataformat'];
	$track = mysql_fetch_array(mysql_query('SELECT SUM(playtime_miliseconds) AS sum_playtime FROM track WHERE audio_dataformat = "' . $audio_dataformat . '" AND video_dataformat = ""'));
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="list.php?audio_dataformat=<?php echo $audio_dataformat; ?>" target="_blank">Playtime <?php echo $audio_dataformat;?>:</a></td>
	<td align="right"><?php echo FormattedTime($track['sum_playtime']); ?></td>
	<td align="right"><font class="small">(<?php echo number_format($track['sum_playtime'] / $sum_total_playtime * 100, 1);?> %)</font></td>
	<td></td>
</tr>
<?php
	}
$query = mysql_query('SELECT video_dataformat FROM track WHERE video_dataformat <> "" GROUP BY video_dataformat ORDER BY video_dataformat');
while($track = mysql_fetch_array($query))
	{
	$video_dataformat = $track['video_dataformat'];
	$track = mysql_fetch_array(mysql_query('SELECT SUM(playtime_miliseconds) AS sum_playtime FROM track WHERE video_dataformat = "' . $video_dataformat . '"'));
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="list.php?video_dataformat=<?php echo $video_dataformat; ?>" target="_blank">Playtime <?php echo $video_dataformat;?>:</a></td>
	<td align="right"><?php echo FormattedTime($track['sum_playtime']);?></td>
	<td align="right"><font class="small">(<?php echo number_format($track['sum_playtime'] / $sum_total_playtime * 100, 1);?> %)</font></td>
	<td></td>
</tr>
<?php
	}
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>	
	<td><a href="list.php?command=all" target="_blank">Total playtime:</a></td>
	<td align="right"><?php echo FormattedTime($sum_total_playtime); ?></td>
	<td align="right"><font class="small">(100,0 %)</font></td>
	<td></td>
</tr>
<?php
$i = 0;
$no_image		= mysql_num_rows(mysql_query('SELECT album_id FROM bitmap WHERE filemtime = "' . filemtime($cfg['home_dir'] . '/images/image.gif') . '"'));
$no_cd_front	= mysql_num_rows(mysql_query('SELECT album_id FROM bitmap WHERE cd_front = ""'));
$no_cd_back		= mysql_num_rows(mysql_query('SELECT album_id FROM bitmap WHERE cd_back = ""'));
if ($no_image > 0 || $no_cd_front > 0 || $no_cd_back > 0)
	{
?>
<tr class="line"><td colspan="5"></td></tr>
<tr class="header">
	<td></td>
	<td colspan="3">No image availible:</td>
	<td></td>
</tr>
<tr class="line"><td colspan="5"></td></tr>
<?php if ($no_image > 0)
		{ ?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="list.php?command=no_image" target="_blank">image.jpg</a></td>
	<td align="right"><?php echo $no_image; ?></td>
	<td align="right"><font class="small">(<?php echo number_format($no_image / $album['albums'] * 100, 1);?> %)</font></td>
	<td></td>
</tr>
<?php	}
if ($no_cd_front > 0)
		{ ?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="list.php?command=no_cd_front" target="_blank">cd_front.jpg</a></td>
	<td align="right"><?php echo $no_cd_front; ?></td>
	<td align="right"><font class="small">(<?php echo number_format($no_cd_front / $album['albums'] * 100, 1);?> %)</font></td>
	<td></td>
</tr>
<?php	}
if ($no_cd_back > 0)
		{ ?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>	
	<td><a href="list.php?command=no_cd_back" target="_blank">cd_back.jpg</a></td>
	<td align="right"><?php echo $no_cd_back; ?></td>
	<td align="right"><font class="small">(<?php echo number_format($no_cd_back / $album['albums'] * 100, 1);?> %)</font></td>
	<td></td>
</tr>
<?php
		}
	}
?>
</table>
<?php
require_once('include/footer.inc.php');
?>
