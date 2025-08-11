<?php
/**************************************************************************
    FILENAME        :   admin_subcontent.php
    PURPOSE OF FILE :   Manages sub site content
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

if ($level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$sitename = $_GET['site'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$Submit = $_POST['Submit'];
$id = $_GET['id'];
$action = $_GET['action'];

// Edit content
if ($Submit == "Update" && $action == "edit")
{
    $trans= array("%7B" => "{", "%7D" => "}");
	$content = strtr($_POST['editor'], $trans);
    $content = safesql($content, "text", false);
    if ($content == NULL || $content == "" ||$content == "NULL") $content = "Nothing here";
	$Update = $data->update_query("subcontent", "content=$content", "id='$id'", "Content", "Updated $name");    
    if ($Update)
    {
        $htsite = htmlentities($sitename);
        echo "<script> alert('Content updated'); window.location = 'admin.php?page=subcontent&site=$htsite';</script>\n";
        exit;   
    }
	$action = "";
} 
elseif ($Submit == "Submit" && $action == "new") 
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
    if ($content == NULL || $content == "" ||$content == "NULL") $content = safesql("Nothing here", "text");
	$Update = $data->insert_query("subcontent", "'', $name, $content, '$sitename'", "Content", "Updated $name");
    if($Update)
    {
        echo "<script> alert('Content added'); window.location = 'admin.php?page=subcontent&site=$sitename';</script>\n";
        exit;   
    }
	$action = "";
}

// Show specific content
if ($id != "")
{
	// Show selected content
	$Show = $data->select_query("subcontent", "WHERE id='$id' AND site='$sitename'");
	$item = $data->fetch_array($Show);
}

if ($action=="edit") 
{
	$item['content'] = $item['content']; 
}
if ($action=="delete") 
{
	$sql = $data->delete_query("sitename", "id='$id'", "Content Pages", "$uname deleted $id page");
    if ($sql)
    {
        echo "<script> alert('Content deleted'); window.location = 'admin.php?page=subcontent&site=$sitename';</script>\n";
        exit;   
    }
    $action = "";
}
// Show all news
$result = $data->select_query("subcontent", "WHERE site='$sitename'");

$content = array();
$content[] = $data->fetch_array($result);
$numcontent = $data->num_rows($result);
while ($content[] = $data->fetch_array($result));

$tpl->assign("item", $item);
$tpl->assign("sitename", $sitename);
$tpl->assign('name', $name);
$tpl->assign('action', $action);
$tpl->assign('numcontent', $numcontent);
$tpl->assign('content', $content);
$tpl->assign("editor", true);
$tpl->assign('editFormAction',$editFormAction);
$filetouse = "admin_subcontent.tpl";
?>