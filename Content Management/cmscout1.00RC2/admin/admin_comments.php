<?php
/**************************************************************************
    FILENAME        :   admin_comments.php
    PURPOSE OF FILE :   Manage article comments
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
	$module['User Content Management']['Comments'] = "comments";
    $permision['Comments'] = 2;
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
	$sqlq = $data->delete_query("comments", "id=$id", "Comments", "Deleted Comment");
	if ($sqlq) 
    { 
        echo "<script> alert('Comment Deleted'); window.location = '$pagename';</script>\n";
        exit; 
	}
}
elseif ($action == 'publish') 
{
	$sqlq = $data->update_query("comments", "allowed = 1", "id=$id", "Comments", "Published $id");
    header("Location: $pagename");
}
elseif ($action == 'unpublish') 
{
	$sqlq = $data->update_query("comments", "allowed = 0", "id=$id", "Comments", "Unpublished $id");
    header("Location: $pagename");
}


$note = $data->select_query("comments");

if ($action == "") 
{
	$totalRows_note = $data->num_rows($note);
	$row_note = array();
	$row_note[] = $data->fetch_array($note);
	while ($row_note[] = $data->fetch_array($note));
}
$tpl->assign('comments', $row_note);
$tpl->assign('number', $totalRows_note);
$tpl->assign('action', $action);
$tpl->assign("level", $check['level']);
$filetouse = 'admin_comments.tpl';
?>