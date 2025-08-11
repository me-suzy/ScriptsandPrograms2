<?pp
/**************************************************************************
    FILENAME        :   thumbnail.php
    PURPOSE OF FILE :   Displays a thumbnail image
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
include("common.php");
$id = $_GET['pic'];
$uploaddir=$config['photopath']."/";
if (isset($_GET['where'])) $where = $_GET['where']; else $where = "";

if($where == "")
{
    $sql = $data->select_query("photos", "WHERE ID=$id AND allowed = 1");
    $pics = $data->fetch_array($sql);
    $file = $pics['filename'];
}
elseif ($where == "article")
{
    $sql = $data->select_query("patrol_articles", "WHERE ID=$id AND allowed=1");
    $article = $data->fetch_array($sql);
    $file = $article['pic'];
}

$image = imagecreatefromjpeg($uploaddir.$file);
$width = 150;
$height = 150;

list($width_orig, $height_orig) = getimagesize($uploaddir.$file);

if ($width && ($width_orig < $height_orig)) 
{
    $width = ($height / $height_orig) * $width_orig;
}
else 
{
    $height = ($width / $width_orig) * $height_orig;
}

$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromjpeg($uploaddir.$file);

imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

header('Content-type: image/jpeg');
imagejpeg($image_p, null, 100);
exit();
?>