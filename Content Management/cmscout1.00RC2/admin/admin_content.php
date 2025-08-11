<?php
/**************************************************************************
    FILENAME        :   admin_spec_content.php
    PURPOSE OF FILE :   Static Content Manager
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
	$module['Content Management']['Static Content'] = "content";
    $permision['Static Content'] = 1;
	return;
}

if ($check['level'] != 1 && $check['level'] != 0)
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$Submit = $_POST['Submit'];
$id = $_GET['id'];
$action = $_GET['action'];

// Edit content
if ($Submit == "Update")
{
    $trans= array("%7B" => "{", "%7D" => "}");
	$content = strtr($_POST['editor'], $trans);
    $content = safesql($content, "text", false);
    if ($content == NULL || $content == "" || $content == "NULL") $content = safesql("Nothing here", "text");
	$Update = $data->update_query("static_content", " content=$content", "id='$id'", "Content", "Updated $name");
    if ($Update)
    {   
        echo "<script> alert('Content updated'); window.location = '{$pagename}';</script>\n";
        exit;   
    } 
	$action = "";
} 
elseif ($Submit == "Submit") 
{
    if ($_POST['name'] == '')
    {
        error_message("You need to enter a name for the content item");
        exit;
    }
    $name = safesql($_POST['name'], "text");
    $trans= array("%7B" => "{", "%7D" => "}");
	$content = strtr($_POST['editor'], $trans);
    $content = safesql($content, "text", false);
    if ($content == NULL || $content == "" || $content == "NULL") $content = safesql("Nothing here", "text");
	$Update = $data->insert_query("static_content", "'', $name, $content", "id='$id'", "Content", "Added $name");
    $sql = $data->insert_query("allowedpages", "$name", "", "", false);
    if ($Update && $sql)
    {   
        echo "<script> alert('Content added'); window.location = '{$pagename}';</script>\n";
        exit;   
    }
	$action = "";
}

// Show specific content
if ($id != "")
{
	// Show selected content
	$Show = $data->select_query("static_content", "WHERE id='$id'");
	$ShowRow = $data->fetch_array($Show);
	$Showcontent = $ShowRow["content"];
	$name = $ShowRow['name'];
    $tpl->assign("editor", true);
}

if ($action=="delete") 
{
	$bla = $data->fetch_array($data->select_query("static_content", "WHERE id=$id"));
    $delete = $data->delete_query("static_content", "id='$id'", "Content Pages", "$uname deleted $id page");
    $sql = $data->delete_query("allowedpages", "page='{$bla['name']}'", "", "", false);
    if ($delete && $sql)
    {   
        echo "<script> alert('Content Deleted'); window.location = '{$pagename}';</script>\n";
        exit;   
    }  
    $action = "";
}
elseif ($action=="new")
{
    $tpl->assign("editor", true);
}
else
{
    $result = $data->select_query("static_content");
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
}

$tpl->assign('Showcontent', $Showcontent);
$tpl->assign('name', $name);
$tpl->assign('action', $action);
$tpl->assign('numcontent', $numcontent);
$tpl->assign('content', $content);
$tpl->assign('editFormAction',$editFormAction);
$filetouse = "admin_content.tpl";
?>