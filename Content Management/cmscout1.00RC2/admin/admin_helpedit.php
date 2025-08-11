<?php
/**************************************************************************
    FILENAME        :   admin_spec_content.php
    PURPOSE OF FILE :   Static Content Manager
    LAST UPDATED    :   04 October 2005
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
	$module['Content Management']['Help Content'] = "helpedit";
    $permision['Help Content'] = 1;
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
    $content = safesql($content, "text");
    if ($content == NULL || $content == "" || $content == "NULL") $content = safesql("Nothing here", "text");
	$Update = $data->update_query("help", " help=$content", "id='$id'", "Help", "Updated $name");
    if ($Update)
    {   
        echo "<script> alert('Help Content updated'); window.location = '{$pagename}';</script>\n";
        exit;   
    } 
	$action = "";
} 

// Show specific content
if ($id != "")
{
	// Show selected content
	$Show = $data->select_query("help", "WHERE id='$id'");
	$ShowRow = $data->fetch_array($Show);
	$Showcontent = $ShowRow["help"];
	$name = $ShowRow['title'];
    $tpl->assign("editor", true);
}

// Show all news
$result = $data->select_query("help");

$content = array();
$numcontent = $data->num_rows($result);
while ($content[] = $data->fetch_array($result));

//$tempcss = "";
//$tpl->assign('tempcss', $tempcss);
$tpl->assign('Showcontent', $Showcontent);
$tpl->assign('name', $name);
$tpl->assign('action', $action);
$tpl->assign('numcontent', $numcontent);
$tpl->assign('content', $content);
$tpl->assign('editFormAction',$editFormAction);
$filetouse = "admin_helpedit.tpl";
?>