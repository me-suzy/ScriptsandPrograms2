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
//  | image.php                                                                 |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');

$album_id 	= get('album_id');
$image	 	= get('image');
$size		= get('size');

if		($album_id)	image($album_id, $size);
elseif	($image)	ResampleImage($image, $size);
exit();



//  +---------------------------------------------------------------------------+
//  | Image                                                                     |
//  +---------------------------------------------------------------------------+
function image($album_id, $size)
{
$query  = mysql_query('SELECT image' . (int) $size . ' AS image, filemtime FROM bitmap WHERE album_id = "' . mysql_real_escape_string($album_id) . '"');
$bitmap = mysql_fetch_array($query);
$etag	= $bitmap['filemtime'];

header('Cache-Control: max-age=120, must-revalidate');

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag)
	{
	header('HTTP/1.1 304 Not changed');
	exit();
	}

header('ETag: ' . $etag);
header('Content-length: ' . strlen($bitmap['image']));
header('Content-type: image/jpeg');
echo $bitmap['image'];
}



//  +---------------------------------------------------------------------------+
//  | Resample Image                                                            |
//  +---------------------------------------------------------------------------+
function ResampleImage($image, $size)
{
authenticate('access_config', true, false);
header('Content-type: image/jpeg');

$extension = substr(strrchr($image, '.'), 1);
$extension = strtolower($extension);
if		($extension == 'jpg')	$src_image = @imageCreateFromJpeg($image)	or exit();
elseif	($extension == 'png')	$src_image = @imageCreateFromPng($image)	or exit();
elseif	($extension == 'gif')	$src_image = @imageCreateFromGif($image)	or exit();
else	exit();

if ($extension == 'jpg' && imageSX($src_image) == $size && imageSY($src_image) == $size)
	{
	@readfile($image);
	}
else
	{
	$dst_image = imageCreateTrueColor($size, $size);
	imageCopyResampled($dst_image, $src_image, 0, 0, 0, 0, $size, $size, imageSX($src_image), imageSY($src_image));
	ImageJpeg($dst_image, NULL, 90);
	imageDestroy($dst_image);
	}
imageDestroy($src_image);
}
?>

