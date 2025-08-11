<?php
/**************************************************************************
    FILENAME        :   admin_photo_edit.php
    PURPOSE OF FILE :   Allows editing of photos
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
	return;
}

if ($level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$albumid = $_GET['id'];
$photoid = $_GET['pid'];
$photo_query = $data->select_query("photos", "WHERE album_id='$albumid' AND ID='$photoid'");
$photo = $data->fetch_array($photo_query);
$noshow = false;

$where = '../' . $config['photopath'] . '/';
$sub = $_POST['Submit'];
if ($sub == "Submit") 
{
    if ($_FILES['filename']['name'] != '') 
    {
        if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
        {
            $filestuff = uploadpic($_FILES['filename'], 350, 350);
            $filename = safesql($filestuff['filename'], "text");;
            $desc = safesql($_POST['caption'], "text");
            $sql2 = $data->update_query("photos", "filename='$filename'", "ID=$photoid", "", "", false);
            $data->update_query("album_track", "numphotos = numphotos + 1", "ID='$id'", "", "", false);
		} 
        else
		{
            error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}");
		}
	} 
    
	$desc = $_POST['caption'];
	if ($desc != '') {
		$sql1 = $data->update_query("photos", "caption='$desc'", "ID=$photoid", "", "", false);	
	}
    if(($sql1 && $desc != '') || ($sql2 && $_FILES['filename']['name'] != ''))
    {
        echo "<script> alert('The photo/caption has been updated'); window.close();</script>";
        exit;   
    } 
	$noshow = true;
}
$tpl->assign("noshow", $noshow);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign("photo", $photo);
$filetouse = "admin_photo_edit.tpl";
?>