<?php
// +----------------------------------------------------------------------+
// |                        AJRgal version 4.1                            |
// +----------------------------------------------------------------------+
// |     This program is free software; you can redistribute it and/or    |
// |      modify it under the terms of the GNU General Public License     |
// |    as published by the Free Software Foundation; either version 2    |
// |        of the License, or (at your option) any later version.        |
// |                                                                      |
// |   This program is distributed in the hope that it will be useful,    |
// |   but WITHOUT ANY WARRANTY; without even the implied warranty of     |
// |    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     |
// |          GNU General Public License for more details.                |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// |    along with this program; if not, write to the Free Software       |
// |     Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA        |
// |                          02111-1307, USA.                            |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author:   Andrew John Reading - A.J.Reading <andrew@cbitsonline.com> |
// | Website:  http://www.cbitsonline.com                                 |
// +----------------------------------------------------------------------+
// | File:             show.php                                           |
// | Description:      Resizes images to thumbnails and displays them     |
// | Last Update:      22/11/2005                                         |
// +----------------------------------------------------------------------+
ini_set('error_reporting', E_ALL ^ E_NOTICE);
include('includes/config.php');

function Fixed($filename, $desired_width, $desired_height, $position)
{
    // Get file size and mime type
    $size = GetImageSize($filename);
    $width = $size[0];
    $height = $size[1];

    if($desired_width/$desired_height > $width/$height)
    {
        $new_width = $desired_width;
        $new_height = $height * ($desired_width / $width);
    }
    else
    {
        $new_width = $width * ($desired_height / $height);
        $new_height = $desired_height;
    }

    // Resize
    $image_p = ImageCreateTrueColor($new_width, $new_height);
    $image_f = ImageCreateTrueColor($desired_width, $desired_height);
    $image = ImageCreateFromJpeg($filename);
    ImageCopyResampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Adjust position
    switch($position)
    {
        case("topleft"):
            $x = 0;
            $y = 0;
            break;

        case("topright"):
            $x = $new_width - $desired_width ;
            $y = $bordersize;
            break;

        case("bottomleft"):
            $x = 1;
            $y = $new_height - $desired_height;
            break;

        case("bottomright"):
            $x = $new_width - $desired_width;
            $y = $new_height - $desired_height;
            break;

        case("center"):
            $x = ($new_width - $desired_width) / 2;
            $y = ($new_height - $desired_height) / 2;
            break;
     }

    // Resample with 1px border
    ImageCopyResampled($image_f, $image_p, 0, 0, $x, $y, $desired_width, $desired_height, $desired_width, $desired_height);

    if($size['mime'] == 'image/jpeg')
    {
        ImageJpeg($image_f);
    }
    elseif($size['mime'] == 'image/gif')
    {
        ImageGif($image_f);
    }
    elseif($size['mime'] == 'image/png')
    {
        ImagePng($image_f);
    }
    
    return $size['mime'];
}

function Proportional($filename, $max_width, $max_height)
{
    // Get file size and mime type
    $size = GetImageSize($filename);
    $width = $size[0];
    $height = $size[1];
    
    // Calculate new sizes
    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if( ($width <= $max_width) && ($height <= $max_height) )
    {
        $tn_width = $width;
        $tn_height = $height;
    }
    elseif (($x_ratio * $height) < $max_height)
    {
        $tn_height = ceil($x_ratio * $height);
        $tn_width = $max_width;
    }
    else
    {
        $tn_width = ceil($y_ratio * $width);
        $tn_height = $max_height;
    }
    // Allow more memory for larger images
    ini_set('memory_limit', '32M');
     
    if($size['mime'] == 'image/jpeg')
    {
        $src = ImageCreateFromJpeg($filename);
        $dst = ImageCreateTrueColor($tn_width, $tn_height);
        ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        ImageJpeg($dst);
    }
    elseif($size['mime'] == 'image/gif')
    {
        $src = ImageCreateFromGif($filename);
        $dst = ImageCreateTrueColor($tn_width, $tn_height);
        ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        ImageGif($dst);
    }
    elseif($size['mime'] == 'image/png')
    {
        $src = ImageCreateFromPng($filename);
        $dst = ImageCreateTrueColor($tn_width, $tn_height);
        ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        ImagePng($dst);
    }

    ImageDestroy($src);
    ImageDestroy($dst);
    return $size['mime'];
}

if($display_method == 1)
{
    $mime = Proportional($_GET['file'], $max_width, $max_height);
}
elseif($display_method == 2)
{
    $mime = Fixed($_GET['file'], $max_width, $max_height, $fixed_pos);
}

if($mime == 'image/jpeg')
{
    Header("Content-type: image/jpeg");
}
elseif($mime == 'image/gif')
{
    Header("Content-type: image/gif");
}
elseif($mime == 'image/png')
{
    Header("Content-type: image/png");
}

?>
