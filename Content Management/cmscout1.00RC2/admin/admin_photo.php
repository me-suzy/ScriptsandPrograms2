<?php
/**************************************************************************
    FILENAME        :   admin_photo.php
    PURPOSE OF FILE :   Manages photos and photo albums
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

if( !empty($getmodules) )
{
	$module['User Content Management']['Photo Albums'] = "photo";
    $permision['Photo Albums'] =2;
	return;
}

if ($level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$action = $_GET['action'];
$id = $_GET['id'];

if ($action == 'delete') 
{
 	if ($level != 0 && $level != 1 && $level != 2)
    {
 		$patrol = $check['team'];
		$sqlq = $data->delete_query("album_track", "ID=$id AND patrol='$patrol'", "", "", false);
	} 
    else
    {
		$sqlq = $data->delete_query("album_track", "ID=$id", "", "", false);
	}
    
	if ($sqlq) 
    { 
		$sqlq = $data->delete_query("photos","album_id=$id", 'Albums',  "Deleted album");		
		if ($sqlq) 
        {
            echo "<script> alert('The photo album has been deleted'); window.location = '$pagename';</script>\n";
            exit;    
        }
	}
}
elseif ($action == "deletephoto") 
{
	$pid = $_GET['pid'];
	$sqlq = $data->delete_query("photos", "ID=$pid AND album_id='$id'", "Albums", "Photo for album $aid deleted");
	$data->update_query("album_track", "numphotos = numphotos - 1", "ID=$id", "", "", false);
	$act = "view";
    if($sqlq)
    {
        echo "<script> alert('The photo has been deleted'); window.location = '$pagename&action=view&id=$id';</script>\n";
        exit;   
    } 
}
elseif ($action == 'publishart') 
{
	$ext = $_GET['photo'];
    if ($ext == "yes")
    {
        $sqlq = $data->update_query("photos", "allowed = 1", "album_id=$id", "", "", false);        
    }
    $sqlq = $data->update_query("album_track", "allowed = 1", "ID=$id", "Albums", "Published $id");
    header("Location: $pagename");
}
elseif ($action == 'unpublishart') {
	$sqlq = $data->update_query("album_track", "allowed = 0", "ID=$id", "Albums", "Unpublished $id");
    header("Location: $pagename");
}
elseif ($action == 'publishphoto') 
{
    $pid = $_GET['pid'];
	$sqlq = $data->update_query("photos", "allowed = 1", "ID=$pid", "Photos", "Published $id");
    header("Location: $pagename&action=view&id=$id");
}
elseif ($action == 'unpublishphoto') 
{
    $pid = $_GET['pid'];
	$sqlq = $data->update_query("photos", "allowed = 0", "ID=$pid", "Photos", "Unpublished $id");
    header("Location: $pagename&action=view&id=$id");
}

if ($level != 0 && $level != 1 && $level != 2) 
{
 	$patrol = $check['team'];
	$album = $data->select_query("album_track", "WHERE patrol='$patrol' ORDER BY album_name ASC");
} 
else
{
	$album = $data->select_query("album_track", "ORDER BY album_name ASC");
}

if ($action == "view") 
{
	$id = $_GET['id'];
	$query = $data->select_query("album_track", "WHERE id = $id");
	$albuminfo = $data->fetch_array($query);
	$photo_query = $data->select_query("photos", "WHERE album_id='".$albuminfo['ID']."'");
	$numphotos = $data->num_rows($photo_query);
    $photo = array();
	$photo[] = $data->fetch_array($photo_query);
	while ($photo[] = $data->fetch_array($photo_query));
	$tpl->assign("photos", $photo);
    $tpl->assign("numphotos", $numphotos);
	$tpl->assign("albuminfo", $albuminfo);
	$tpl->assign('id', $id);
	$tpl->assign("photopath", "../" . $config["photopath"] . "/");
	$tpl->assign('action', $action);
} 
else 
{
	$row_album = array();
	$row_album[] = $data->fetch_array($album);
	$totalRows_album = $data->num_rows($album);
	while ($row_album[] = $data->fetch_array($album)); 
	$tpl->assign('albums', $row_album);
	$tpl->assign('numalbums', $totalRows_album);
}
$filetouse = 'admin_photo.tpl';
?>