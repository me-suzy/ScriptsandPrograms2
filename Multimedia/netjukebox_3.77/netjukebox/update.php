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
//  | update.php                                                                |
//  +---------------------------------------------------------------------------+
ini_set('max_execution_time', '3600');
require_once('include/initialize.inc.php');
authenticate('access_config');
require_once('getid3/getid3.php');

$command 	= GetPost('command');
$flag		= GetPost('flag');
if ($command == 'update')		{$cfg['fast_update'] = false; update();}
if ($command == 'FastUpdate') 	{$cfg['fast_update'] = true;  update();}
if ($command == 'ImageUpdate')	image_update($flag);
if ($command == 'SaveImage')	save_image($flag);
exit();



//  +---------------------------------------------------------------------------+
//  | Update                                                                    |
//  +---------------------------------------------------------------------------+
function update()
{
global $cfg;
require_once('include/header.inc.php');

//FormattedNavigator
$name	= array('Configuration');
$url	= array('config.php');
if ($cfg['fast_update'])	$name[] = 'Fast update';
else						$name[] = 'Update';
FormattedNavigator($url, $name);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td width="150">Update</td>
	<td width="*">Progress</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="4"></td></tr>
<tr class="odd">
	<td></td>
	<td>Structure &amp; Bitmap</td>
	<td><div id="structure"></div></td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td>File Info</td>
	<td><div id="fileinfo"></div></td>
	<td></td>
</tr>
</table>
<?php
$cfg['footer'] = 'dynamic';
require('include/footer.inc.php');
echo '<script type="text/javascript">document.getElementById(\'structure\').innerHTML=\'<img src="' . $cfg['img'] . '/animated_progress.gif" alt="" width="19" height="13" border="0">\';</script>' . "\n";
flush();

mysql_query('UPDATE album SET updated = "0"');
mysql_query('UPDATE track SET updated = "0"');
mysql_query('UPDATE bitmap SET updated = "0"');

initGetID3();
$getID3->option_tag_id3v2 = true; // Read and process ID3v2 tags 

RecursiveScan($cfg['media_dir']);

if (!$cfg['fast_update'])
	{
	mysql_query('DELETE FROM album WHERE NOT updated');
	mysql_query('DELETE FROM track WHERE NOT updated');
	mysql_query('DELETE FROM bitmap WHERE NOT updated');
	}
echo '<script type="text/javascript">document.getElementById(\'structure\').innerHTML=\'<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">\';</script>' . "\n";
flush();

FileInfo();
OptimezeMysql();

echo '<script type="text/javascript">document.getElementById(\'fileinfo\').innerHTML=\'<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">\';</script>' . "\n";

$cfg['footer'] = 'close';
require('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Image Update                                                              |
//  +---------------------------------------------------------------------------+
function image_update($flag)
{
global $cfg;
// flag 0 = Local / No image
// flag 1 = Internet image
// flag 2 = Skipped
// flag 3 = Skipped not checked in this run

if ($flag == 3)
	{
	mysql_query('UPDATE bitmap SET flag = "3" WHERE flag = "2"');
	$flag = 2;
	}
if ($flag == 2)
	{
	$query = mysql_query('SELECT album.artist, album.album, album.album_id
						FROM album, bitmap
						WHERE bitmap.flag = "3"
						AND bitmap.album_id = album.album_id
						ORDER BY album.artist_alphabetic, album.album');
	}
if ($flag == 1)
	{
	$query = mysql_query('SELECT album.artist, album.album, album.album_id
						FROM album, bitmap
						WHERE bitmap.filemtime = "' . filemtime($cfg['home_dir'] . '/images/image.gif') . '"
						AND bitmap.flag = "0"
						AND bitmap.album_id = album.album_id
						ORDER BY album.artist_alphabetic, album.album');
	}

$album = mysql_fetch_array($query);
if ($album['album_id'] == '')
	{
	OptimezeMysql();
	echo '<meta http-equiv="refresh" content="0;URL=config.php">';
	exit();
	}

$artist_strip	= preg_replace("/(.*?) (\(.*\)|\[.*?\]|{.*})/", "$1", $album['artist']);
$album_strip	= preg_replace("/(.*?) (\(.*\)|\[.*?\]|{.*})/", "$1", $album['album']);
if (post('artist') != '' || post('album') != '')
	{
	$artist_strip	= post('artist');
	$album_strip	= post('album');
	}


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

$colombs = floor((cookie('netjukebox_width') - 20) / ($size + 10));
$max_images = 10;

$genre		= post('genre');
$locale		= post('locale');
if (!in_array($genre, array('p', 'k')))				$genre	= 'p';
if (!in_array($locale, array('us', 'uk', 'de')))	$locale	= 'us';

require_once('include/header.inc.php');
//FormattedNavigator
$name	= array('Configuration');
$url	= array('config.php');
$name[] = 'Image update';
FormattedNavigator($url, $name);
?>
<form action="update.php" method="post" target="main">
		<input type="hidden" name="command" value="ImageUpdate">
		<input type="hidden" name="flag" value="<?php echo $flag; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td colspan="<?php echo $colombs; ?>">
	<!-- ---- begin table header ---- -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="header">
		<td class="spacer"></td>
		<td><?php echo htmlentities($album['artist']) . ' - ' . htmlentities($album['album']); ?></td>
		<td width="22" align="right"><a href="update.php?command=ImageUpdate&amp;flag=3&amp;size=50"><img src="<?php echo $cfg['img']; ?>/small_image50_<?php if ($size == '50') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
		<td width="22" align="right"><a href="update.php?command=ImageUpdate&amp;flag=3&amp;size=100"><img src="<?php echo $cfg['img']; ?>/small_image100_<?php if ($size == '100') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
		<td width="22" align="right"><a href="update.php?command=ImageUpdate&amp;flag=3&amp;size=200"><img src="<?php echo $cfg['img']; ?>/small_image200_<?php if ($size == '200') echo 'on'; else echo 'off'; ?>.gif" alt="" width="21" height="21" border="0"></a></td>
	</tr>
	</table>
	<!-- ---- end table header ---- -->
	</td>
</tr>
<tr class="line"><td colspan="<?php echo $colombs; ?>"></td></tr>
<?php
for($i=0; $i < ceil(($max_images + 1) / $colombs); $i++) // plus one for "no image available"
	{
?>
<tr class="<?php echo ($i & 1) ? 'even' : 'odd'; ?>">
<?
	for($j=1; $j <= $colombs; $j++)
		{
?>
	<td height="<?php echo $size + 20; ?>" align="center">
	<div id="image<?php echo $i * $colombs + $j; ?>"><img src="images/dummy.gif" alt="" width="<?php echo $size; ?>" height="<?php echo $size; ?>" border="0"></div>
	</td>
<?php
		}
?>
</tr>
<?php
	}
?>
<tr class="line"><td colspan="<?php echo $colombs; ?>"></td></tr>
<tr class="footer">
	<td colspan="<?php echo $colombs; ?>">
	<!-- ---- begin table footer ---- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr class="footer" style="height: 5px;"><td colspan="8"></td></tr>
	<tr class="footer">
		<td class="spacer"></td>
		<td align="right">Artist:</td>
		<td class="spacer"></td>
		<td><input type="text" name="artist" value="<?php echo htmlentities($artist_strip); ?>" class="forms" style="width: 175px;"></td>
		<td class="textspace"></td>
		<td align="right">Genre:</td>
		<td class="spacer"></td>
		<td>		
		<select name="genre">
			<option value="p"<?php if ($genre == 'p') echo ' selected'; ?>>Popular</option>
			<option value="k"<?php if ($genre == 'k') echo ' selected'; ?>>Clasical</option>
		</select>		
		</td>
	</tr>
	<tr class="footer" style="height: 3px;"><td colspan="8"></td></tr>
	<tr class="footer">
		<td></td>		
		<td align="right">Album:</td>
		<td></td>
		<td><input type="text" name="album" value="<?php echo htmlentities($album_strip); ?>" class="forms" style="width: 175px;"></td>
		<td></td>
		<td align="right">Locale:</td>
		<td></td>
		<td>		
		<select name="locale">
			<option value="us"<?php if ($locale == 'us') echo ' selected'; ?>>USA</option>
			<option value="uk"<?php if ($locale == 'uk') echo ' selected'; ?>>UK</option>
			<option value="de"<?php if ($locale == 'de') echo ' selected'; ?>>DE</option>
		</select>
		</td>
	</tr>
	<tr class="footer" style="height: 5px;">
		<td colspan="8"><input type="image" src="images/dummy.gif"></td>
	</tr>
	</table>
	<!-- ---- end table footer ---- -->
	</td>
</tr>
</table>
</form>
<?php
$cfg['footer'] = 'dynamic';
require('include/footer.inc.php');
flush();

$matches	= '';
$content = file_get_contents('http://www.slothradio.com/covers/?adv=1&artist=' . rawurlencode($artist_strip) . '&album=' . rawurlencode($album_strip) . '&genre=' . $genre . '&imgsize=x&locale=' . $locale);
preg_match_all('/<!-- RESULT ITEM START -->.*?<img src="(http:\/\/(images|images-eu)\.amazon\.com.*?)"/s', $content, $matches);

$i=0;
foreach($matches[1] as $image)
	{
	$i++;
	
	$url   = '<a href="update.php?command=SaveImage&amp;flag=' . $flag. '&amp;album_id=' . $album['album_id'] . '&amp;image=' . rawurlencode($image) . '"><img src="image.php?image=' . rawurlencode($image) . '&amp;size=' . $size . '" alt="" width="' . $size . '" height="' . $size . '" border="0"></a>';
	echo '<script type="text/javascript">document.getElementById(\'image' . $i . '\').innerHTML=\'' . $url . '\'; </script>' . "\n";
	flush();
	}

$i++;
$url   = '<a href="update.php?command=SaveImage&amp;flag=' . $flag . '&amp;album_id=' . $album['album_id'] . '&amp;image=NoImage"><img src="image.php?image=images/image.gif&amp;size=' . $size . '" alt="" width="' . $size . '" height="' . $size . '" border="0"></a>';
echo '<script type="text/javascript">document.getElementById(\'image' . $i . '\').innerHTML=\'' . $url . '\'; </script>' . "\n";

$cfg['footer'] = 'close';
require('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Save Image                                                                |
//  +---------------------------------------------------------------------------+
function save_image($flag)
{
global $cfg;
$image    = get('image');
$album_id = get('album_id');

if ($image == 'NoImage')
	{
	$image 		= $cfg['home_dir'] . '/images/image.gif';
	$filemtime	= filemtime($image);
	$set_flag	= 2;
	}
else
	{
	$filemtime	= time();
	$set_flag	= 1;
	}

$image200 = ResampleImage($image, '', 200);
$image100 = ResampleImage($image, '', 100);
$image50 = ResampleImage($image, '', 50);

mysql_query('UPDATE bitmap SET
			image50			= "' . mysql_real_escape_string($image50) . '",
			image100		= "' . mysql_real_escape_string($image100) . '",
			image200		= "' . mysql_real_escape_string($image200) . '",
			filemtime		= "' . $filemtime . '",
			flag			= "' . mysql_real_escape_string($set_flag) . '"
			WHERE album_id	= "' . mysql_real_escape_string($album_id) . '"');

image_update($flag);
}



//  +---------------------------------------------------------------------------+
//  | Resample Image                                                            |
//  +---------------------------------------------------------------------------+
Function ResampleImage($source_image, $source_data, $size)
{
$extension	= substr(strrchr($source_image, '.'), 1);
$extension	= strtolower($extension);

if		($source_image != '' && $extension == 'jpg')	$src_image = imageCreateFromJpeg($source_image);
elseif	($source_image != '' && $extension == 'png')	$src_image = imageCreateFromPng($source_image);
elseif	($source_image != '' && $extension == 'gif')	$src_image = imageCreateFromGif($source_image);
else	{
		$extension = 'jpg';
		$src_image = imageCreateFromString($source_data);
		}

if ($extension == 'jpg' && imageSX($src_image) == $size && imageSY($src_image) == $size)
	{
	if ($source_image != '')
		$data = file_get_contents($source_image);
	else
		$data = $source_data;
	}
else
	{
	$dst_image = imageCreateTrueColor($size, $size);
	imageCopyResampled($dst_image, $src_image, 0, 0, 0, 0, $size, $size, imageSX($src_image), imageSY($src_image));
	ob_start();
	ImageJpeg($dst_image, NULL, 90);
	$data = ob_get_contents();
	ob_end_clean();
	imageDestroy($dst_image);
	}

imageDestroy($src_image);
return $data;
}



//  +---------------------------------------------------------------------------+
//  | Optimize MySQL                                                            |
//  +---------------------------------------------------------------------------+
function OptimezeMysql()
{
$list	= '';
$query	= mysql_query('SHOW TABLES');
$table	= mysql_fetch_row($query);
$list	.= $table[0];
while ($table = mysql_fetch_row($query))
	{
	$list .= ', ';
	$list .= $table[0];
	}
mysql_query('OPTIMIZE TABLE ' . $list);
}



//  +---------------------------------------------------------------------------+
//  | Initialize getID3                                                         |
//  +---------------------------------------------------------------------------+
function initGetID3()
{
global $getID3;
if (!defined('GETID3_HELPERAPPSDIR'))
	{
	define('GETID3_HELPERAPPSDIR', 'no_helper_apps_needed');
	$getID3 = new getID3;
	}
$getID3->encoding				= 'ISO-8859-1';	// CASE SENSITIVE!
												// iconv() support is needed for encodings other than
												// ISO-8859-1, UTF-8, UTF-16LE, UTF16-BE, UTF-16
$getID3->encoding_id3v1			= 'ISO-8859-1';	// Should always be 'ISO-8859-1', but some tags may be written
												// in other encodings such as 'EUC-CN'
$getID3->option_tag_id3v1		= false; // Read and process ID3v1 tags 
$getID3->option_tag_id3v2		= false; // Read and process ID3v2 tags 
$getID3->option_tag_lyrics3		= false; // Read and process Lyrics3 tags 
$getID3->option_tag_apetag		= false; // Read and process APE tags 
$getID3->option_tags_process	= false; // Copy tags to root key 'tags' and encode to $this->encoding 
$getID3->option_tags_html		= false; // Copy tags to root key 'tags_html' properly translated from various encodings to HTML entities 
$getID3->option_extra_info		= false; // Calculate additional info such as bitrate, channelmode etc
$getID3->option_md5_data		= false; // Get MD5 sum of data part - slow
$getID3->option_md5_data_source	= false; // Use MD5 of source file if availble - only FLAC and OptimFROG
$getID3->option_sha1_data		= false; // Get SHA1 sum of data part - slow
$getID3->option_max_2gb_check	= true;  // Check whether file is larger than 2 Gb and thus not supported by PHP
}



//  +---------------------------------------------------------------------------+
//  | File identification                                                       |
//  +---------------------------------------------------------------------------+
function FileId($file)
{
$file_size = filesize($file);

$filehandle	= fopen($file, 'rb');
if ($file_size > 5120)
	{
	fseek($filehandle, round(0.5 * $file_size - 2560 - 1));
	$data = fread($filehandle, 5120);
	$data .= $file_size;
	}
else
	{
	$data = fread($filehandle, $file_size);
	}
fclose($filehandle);

$crc32 = dechex(crc32($data));
return str_pad($crc32, 8, '0', STR_PAD_LEFT);
}



//  +---------------------------------------------------------------------------+
//  | File Info                                                                 |
//  +---------------------------------------------------------------------------+
function FileInfo()
{
global $cfg;
global $getID3;
echo '<script type="text/javascript">document.getElementById(\'fileinfo\').innerHTML=\'<img src="' . $cfg['img'] . '/animated_progress.gif" alt="" width="19" height="13" border="0">\';</script>' . "\n";
flush();

initGetID3();
$getID3->option_extra_info = true; // Calculate additional info such as bitrate, channelmode etc

$query = mysql_query('SELECT relative_file, filemtime, track_id, album_id FROM track WHERE updated'); ///
while ($track = mysql_fetch_array($query))
	{
	$file = $cfg['media_dir'] . $track['relative_file'];
	$filemtime = filemtime($file);
	if ($filemtime != $track['filemtime'])
		{
		echo '<script type="text/javascript">document.getElementById(\'fileinfo\').innerHTML="' . htmlentities($file) . '";</script>';
		flush();
		
		$track_id				= $track['album_id'] . '_' . FileId($file);
		$file_size				= filesize($file);
		$playtime				= '';
		$playtime_seconds		= 0;
		$playtime_miliseconds	= 0;
		$bits_per_sample 		= 0;
		$channels				= 0;
		$sample_rate			= 0;
		$audio_bitrate			= 0;
		$audio_raw_decoded		= 0;
		$audio_bits_per_sample	= 0;
		$audio_sample_rate		= 0;
		$audio_channels			= 0;
		$audio_dataformat		= '';
		$audio_encoder 			= '';
		$audio_bitrate_mode		= '';
		$audio_profile			= '';
		$video_dataformat		= '';
		$video_codec			= '';
		$video_resolution_x		= 0;
		$video_resolution_y		= 0;
		$video_framerate		= 0;
		
		$getID3->analyze($file);
		
		if (isset($getID3->info['playtime_string']))			$playtime			= $getID3->info['playtime_string'];
		if (isset($getID3->info['playtime_seconds']))			$playtime_seconds	= $getID3->info['playtime_seconds'];
																$miliseconds 		= round($playtime_seconds * 1000);
		
		if (isset($getID3->info['audio']['dataformat']))
			{
			$audio_dataformat = $getID3->info['audio']['dataformat'];
			if (isset($getID3->info['audio']['encoder']))			$audio_encoder			= $getID3->info['audio']['encoder'];
			if ($audio_dataformat == 'mpc' && isset($getID3->info['mpc']['header']['profile']))			$audio_profile = $getID3->info['mpc']['header']['profile'];
			if ($audio_dataformat == 'aac' && isset($getID3->info['aac']['header']['profile_text']))	$audio_profile = $getID3->info['aac']['header']['profile_text'];
			if (isset($getID3->info['audio']['lossless']) && $getID3->info['audio']['lossless'])		$audio_profile = 'Lossless compression'; 
			if (isset($getID3->info['audio']['bitrate_mode']))		$audio_bitrate_mode	= $getID3->info['audio']['bitrate_mode'];
			if (isset($getID3->info['audio']['bitrate']))			$audio_bitrate		= $getID3->info['audio']['bitrate'];
			if (!$audio_profile)									$audio_profile		= $audio_bitrate_mode . ' ' . round($audio_bitrate / 1000, 1) . '  kbps';
			if (!$audio_encoder) 									$audio_encoder		= 'unknown encoder';
			
			$audio_bits_per_sample	= 16;
			$audio_sample_rate		= 44100;
			$audio_channels			= 2;
			if (isset($getID3->info['audio']['bits_per_sample']))	$audio_bits_per_sample	= $getID3->info['audio']['bits_per_sample'];
			if (isset($getID3->info['audio']['sample_rate']))		$audio_sample_rate		= $getID3->info['audio']['sample_rate'];
			if (isset($getID3->info['audio']['channels']))			$audio_channels			= $getID3->info['audio']['channels'];
			$audio_raw_decoded = round($audio_channels * $audio_sample_rate * $audio_bits_per_sample * $playtime_seconds / 8);
			}
		if (isset($getID3->info['video']['dataformat']))
			{
			$video_dataformat = $getID3->info['video']['dataformat'];
			if (isset($getID3->info['video']['codec']))				$video_codec		= $getID3->info['video']['codec'];
			if (isset($getID3->info['video']['resolution_x']))		$video_resolution_x	= $getID3->info['video']['resolution_x'];
			if (isset($getID3->info['video']['resolution_y']))		$video_resolution_y	= $getID3->info['video']['resolution_y'];
			if (isset($getID3->info['video']['dataformat']))		$video_framerate	= $getID3->info['video']['frame_rate'] . ' fps';
			}
		
		mysql_query('UPDATE track SET
					filemtime					= "' . $filemtime . '",
					playtime					= "' . $playtime . '",
					playtime_miliseconds		= "' . $miliseconds . '",
					file_size					= "' . $file_size . '",
					audio_bitrate				= "' . $audio_bitrate . '",
					audio_raw_decoded			= "' . $audio_raw_decoded . '",
					audio_bits_per_sample		= "' . $audio_bits_per_sample . '",
					audio_sample_rate			= "' . $audio_sample_rate . '",
					audio_channels				= "' . $audio_channels . '",
					audio_dataformat			= "' . $audio_dataformat . '",
					audio_encoder 				= "' . $audio_encoder . '",
					audio_profile				= "' . $audio_profile . '",
					video_dataformat			= "' . $video_dataformat . '",
					video_codec					= "' . $video_codec . '",
					video_resolution_x			= "' . $video_resolution_x . '",
					video_resolution_y			= "' . $video_resolution_y . '",
					video_framerate				= "' . $video_framerate . '",
					track_id					= "' . $track_id . '"
					WHERE relative_file 		= "' . $track['relative_file'] . '"');
		echo '<script type="text/javascript">document.getElementById(\'fileinfo\').innerHTML=\'<img src="' . $cfg['img'] . '/animated_progress.gif" alt="" width="19" height="13" border="0">\';</script>' . "\n";
		flush();
		}
	}
}



//  +---------------------------------------------------------------------------+
//  | File Structure                                                            |
//  +---------------------------------------------------------------------------+
Function FileStructure($dir, $file, $filename, $album_id)
{
global $cfg;
global $getID3;

If ($album_id == '')
	{
	$album_id = base_convert(uniqid('', false), 16, 36);
	$filehandle = fopen($dir . '/' . $album_id . '.id', 'w');
	fclose($filehandle);
	}

$query	= mysql_query('SELECT filemtime, flag FROM bitmap WHERE album_id = "' . $album_id . '"');
$bitmap	= mysql_fetch_array($query);

$no_image		= false;
$source_data	= '';
if     (file_exists($dir . '/image.jpg'))				$source_image = $dir . '/image.jpg';
elseif (file_exists($dir . '/image.png'))				$source_image = $dir . '/image.png';
elseif (file_exists($dir . '/image.gif'))				$source_image = $dir . '/image.gif';
elseif (file_exists($dir . '/folder.jpg'))				$source_image = $dir . '/folder.jpg';
elseif (file_exists($dir . '/folder.png'))				$source_image = $dir . '/folder.png';
elseif (file_exists($dir . '/folder.gif'))				$source_image = $dir . '/folder.gif';
elseif (file_exists($dir . '/cd_front.jpg'))			$source_image = $dir . '/cd_front.jpg';
elseif (file_exists($dir . '/cd_front.png'))			$source_image = $dir . '/cd_front.png';
elseif (file_exists($dir . '/cd_front.gif'))			$source_image = $dir . '/cd_front.gif';
elseif (filemtime($file[0]) == $bitmap['filemtime'])	$source_image = $file[0];
else
	{
	$getID3->analyze($file[0]);
	if (isset($getID3->info['id3v2']['APIC'][0]['data']) && isset($getID3->info['id3v2']['APIC'][0]['image_mime']) && ($getID3->info['id3v2']['APIC'][0]['image_mime'] == 'image/jpeg'))
		{
		$source_image	= $file[0];
		$source_data	= $getID3->info['id3v2']['APIC'][0]['data'];
		}
	elseif (isset($getID3->info['id3v2']['PIC'][0]['data']) && isset($getID3->info['id3v2']['PIC'][0]['image_mime']) && ($getID3->info['id3v2']['PIC'][0]['image_mime'] == 'image/jpeg'))
		{
		$source_image	= $file[0];
		$source_data	= $getID3->info['id3v2']['PIC'][0]['data'];
		}
	else
		{
		$source_image	= $cfg['home_dir'] . '/images/image.gif';
		$no_image		= true;
		}
	}

$filemtime = filemtime($source_image);
if ($bitmap['filemtime'] != $filemtime && !($bitmap['flag'] > 0 && $no_image))
		{
		$image200 = ResampleImage($source_image, $source_data, 200);
		$image100 = ResampleImage($source_image, $source_data, 100);
		$image50 = ResampleImage($source_image, $source_data, 50);
		
		if ($bitmap['filemtime'] == '')
			mysql_query('INSERT INTO bitmap (image50, image100, image200, filemtime, album_id)
						VALUES ("' . mysql_real_escape_string($image50) . '",
						"' . mysql_real_escape_string($image100) . '",
						"' . mysql_real_escape_string($image200) . '",
						"' . $filemtime . '",
						"' . $album_id . '")');
		else
			mysql_query('UPDATE bitmap SET
						image50			= "' . mysql_real_escape_string($image50) . '",
						image100		= "' . mysql_real_escape_string($image100) . '",
						image200		= "' . mysql_real_escape_string($image200) . '",
						filemtime		= "' . $filemtime . '",
						flag			= "0"
						WHERE album_id	= "' . $album_id . '"');
		}

if		(file_exists($dir . '/cd_front.jpg')) $cd_front = $dir . '/cd_front.jpg';
elseif	(file_exists($dir . '/cd_front.png')) $cd_front = $dir . '/cd_front.png';
elseif	(file_exists($dir . '/cd_front.gif')) $cd_front = $dir . '/cd_front.gif';
else	$cd_front = '';

if		(file_exists($dir . '/cd_back.jpg')) $cd_back = $dir . '/cd_back.jpg';
elseif	(file_exists($dir . '/cd_back.png')) $cd_back = $dir . '/cd_back.png';
elseif	(file_exists($dir . '/cd_back.gif')) $cd_back = $dir . '/cd_back.gif';
else	$cd_back = '';

mysql_query('UPDATE bitmap SET
			cd_front		= "' . $cd_front . '",
			cd_back			= "' . $cd_back . '",
			updated			= "1"
			WHERE album_id	= "' . $album_id . '"');


$temp				= DecodeEscapeCharacters($dir);
$temp   			= explode('/', $temp);
$artist_alphabetic 	= $temp[count($temp) - 2];
$album				= $temp[count($temp) - 1];

$year	= 'NULL';
$month	= 'NULL';
$temp = explode(' - ', $album);
@array_walk($temp, 'trim'); // removes traling and leading white spaces
if ((count($temp) == 2) && ($temp[0] > 1901) && ($temp[0] < 2155) && is_numeric($temp[0]))
	{
	$year  = $temp[0];
	$album = $temp[1];
	}
elseif ((count($temp) == 2) && ($temp[0] > 190100) && ($temp[0] < 215512) && is_numeric($temp[0]))
	{
	$year  = substr($temp[0], 0, 4);
	$month = substr($temp[0], -2);
	$album = $temp[1];
	}


$temp = explode(', ', $artist_alphabetic);
@array_walk($temp, 'trim'); // removes traling and leading white spaces
if (count($temp) == 2)
	{
	$preposition = array('de', 'het', '\'t', 'een', 'eene', '\'n',					// Dutch
	'a', 'an', 'the',																// English
	'le', 'la', 'l\'', 'les', 'un', 'une',											// French
	'der', 'die', 'das', 'ein', 'eine',												// German
	'hinn', 'hin', 'hi', 'hinir', 'hinar',											// Icelandic
	'il', 'la', 'lo', 'i', 'gli', 'gl\'', 'le', 'l\'', 'un', 'uno', 'una', 'un\'',	// Italian
	'den', 'det', 'de', 'dei', 'ein', 'ei', 'eit', 'en', 'et',						// Norwegian
	'o', 'a', 'os', 'as', 'um', 'uma', 'uns', 'umas',								// Portuguese
	'el', 'la', 'lo', 'los', 'las', 'uno', 'una', 'unos', 'unas',					// Spanish
	'den', 'det', 'de', 'en', 'ett');												// Swedish
	if (in_array(strtolower($temp[1]), $preposition))
		$artist = $temp[1] . ' ' . strtolower($temp[0]);
	else
		$artist = $temp[1] . ' ' . $temp[0];
	}
else 
	$artist = $artist_alphabetic;


if (!$cfg['fast_update'])
	{
	echo '<script type="text/javascript">document.getElementById(\'structure\').innerHTML="' . htmlentities($artist_alphabetic) . ' - ' . htmlentities($album) . '";</script>' . "\n";
	flush();
	}

$query = mysql_query('SELECT album_id FROM album WHERE album_id = "' . $album_id . '"');
if (mysql_fetch_row($query) != '')
	{
	mysql_query('UPDATE album SET
				artist_alphabetic	= "' . $artist_alphabetic . '",
				artist				= "' . $artist . '",
				album				= "' . $album . '",
				year				= ' . $year . ',
				month				= ' . $month . ',
				updated				= "1"
				WHERE album_id		= "' . $album_id . '"');
	}
else
	{
	$time = time();
	mysql_query('INSERT INTO album (artist_alphabetic, artist, album, year, month, album_add_time, album_id, updated)
		VALUES ("' . $artist_alphabetic . '", "' . $artist . '", "' . $album . '", ' . $year . ', ' . $month. ', "' . $time . '", "' . $album_id . '", "1")');
	}


for($i=0; $i < count($file); $i++)
	{
	$cd = 1;
	$temp = DecodeEscapeCharacters($filename[$i]);
	$temp = explode(' - ', $temp);
	@array_walk($temp, 'trim'); // removes traling and leading white spaces
	if (($temp[0] > 0) && ($temp[0] < 999) && is_numeric($temp[0]))
		{
		if ($temp[0] > 99)
			$cd = substr($temp[0], 0, 1);
		if (count($temp) == 3)
			{
			$track_artist	= $temp[1];
			$title			= $temp[2];
			}
		elseif  (count($temp) == 2)
			{
			$track_artist 	= $artist;
			$title 			= $temp[1];
			}
		else
			{
			$track_artist 	= '*** UNKNOWN FILENAME FORMAT ***';
			$title 			= '(' . $filename[$i] . ')';
			}
		}
	elseif (count($temp) == 2)
		{
		$track_artist 	= $temp[0];
		$title			= $temp[1];
		}
	elseif (count($temp) == 1)
		{
		$track_artist 	= $artist;
		$title 			= $filename[$i];
		}
	else
		{
		$track_artist 	= '*** UNKNOWN FILENAME FORMAT ***';
		$title 			= '(' . $filename[$i] . ')';
		}

	$featuring  = '';
	$temp = explode(' Ft. ', $title);
	@array_walk($temp, 'trim'); // removes traling and leading white spaces
	if (count($temp) == 2)
		{
		$title 		= $temp[0];
		$featuring	= $temp[1];
		}
	
	$relative_file = substr($file[$i], strlen($cfg['media_dir']));
	
	$query = mysql_query('SELECT album_id FROM track WHERE album_id = "' . $album_id . '" AND BINARY relative_file = "' . $relative_file . '"');
	if (mysql_fetch_row($query) != '')
		{
		mysql_query('UPDATE track SET
					artist			= "' . $track_artist . '",
					featuring		= "' . $featuring . '",
					title			= "' . $title . '",
					cd				= "' . $cd . '",
					updated			= "1"
					WHERE album_id	= "' . $album_id . '" AND BINARY relative_file = "' . $relative_file . '"');
		}
	else
		mysql_query('INSERT INTO track (artist, featuring, title, relative_file, cd, album_id, updated)
					VALUES ("' . $track_artist . '", "' . $featuring . '", "' . $title . '", "' . $relative_file . '", "' . $cd . '", "' . $album_id . '", "1")');
	}
mysql_query("UPDATE album SET
			cds				= '$cd'
			WHERE album_id	= '$album_id'");
}



//  +---------------------------------------------------------------------------+
//  | Recursive Scan                                                            |
//  +---------------------------------------------------------------------------+
function RecursiveScan($dir)
{
global $cfg;
$album_id	= '';
$file		= array();
$filename	= array();
$subdir 	= array();

$handle = @opendir($dir) or message('error', '<strong>Can\'t open directory:</strong><br>' . $dir . '<ul class="compact"><li>Check media_dir value in config.inc.php</li><li>Check file/directory permission</li></ul>');
while(($entry = @readdir($handle)) !== false)
	{
	if (!in_array($entry, array('.', '..', 'System Volume Information', 'RECYCLER')))
		{
		if (is_dir($dir . '/' . $entry))
			$subdir[] = $dir . '/' . $entry;
		else
			{
			foreach($cfg['media_extension'] as $media_extension)
				{
				$extension_lenght = strlen($media_extension) + 1;
				if (strtolower(substr($entry, -$extension_lenght)) == strtolower('.' . $media_extension))
					{
					$file[] 	= $dir . '/' . $entry;
					$filename[] = substr($entry, 0, -$extension_lenght);
					break;
					}
				}
			if (strtolower(substr($entry, -3)) == '.id') $album_id = substr($entry, 0, -3);
			}
		}
	}
closedir($handle);

if (count($file) > 0 && (!$cfg['fast_update'] || ($cfg['fast_update'] && !$album_id)))
	FileStructure($dir, $file, $filename, $album_id);

foreach($subdir as $dir)
	RecursiveScan($dir);
}
?>
