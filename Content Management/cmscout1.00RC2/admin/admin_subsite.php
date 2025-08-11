<?php
/**************************************************************************
    FILENAME        :   admin_patrol.php
    PURPOSE OF FILE :   Displays patrols and gives access to patrol content manager and menu manager
    LAST UPDATED    :   22 November 2005
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
	$module['Content Management']['Sub Sites'] = "subsite";
    $permision['Sub Sites'] = 1;
	return;
}

if ($level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$action = $_GET['action'];

$sql = $data->select_query("subsites");

if ($action == "") 
{
	$numsites = $data->num_rows($sql);
	$sites = array();
	$sites[] = $data->fetch_array($sql);
	while  ($sites[] = $data->fetch_array($sql));
}
elseif ($action == "edit") 
{
	$id = $_GET['id'];
    $sql = $data->select_query("subsites", "WHERE id = $id");
	$stuff = $data->fetch_array($sql);
	$tpl->assign('site', $stuff);
	$submit = $_POST['Submit'];
	$oldname = safesql($stuff['name'], "text");
    
	if ($submit == 'Submit') 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to enter a name for the sub-site");
            exit;
        }
        $teamname = safesql($_POST['name'], "text");
		$sql3 = $data->update_query("subsites", "name=$teamname", "id = $id", "", "", false);
        $data->update_query("subcontent", "site=$teamname", "site=$oldname", "", "", false);
        $data->update_query("submenu", "site=$teamname", "site=$oldname", "", "", false);
        $data->update_query("menu_items", "item=$teamname", "item=$oldname AND type=4", "", "", false);
    }
    if ($sql3)
    {
        echo "<script> alert('Sub Site updated'); window.location = '$pagename';</script>\n";
        exit; 
    }
} 
elseif ($action == "Add") 
{
	$submit = $_POST['Submit'];
	if ($submit == 'Submit') 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to enter a name for the sub-site");
            exit;
        }
        $teamname = safesql($_POST['name'], "text");
		$sql3 = $data->insert_query("subsites", "'', $teamname", "", "", false);
        $data->insert_query("subcontent", "'', 'frontpage', 'There is not any information here yet.', $teamname", "", "", false);
        $data->insert_query("submenu", "'', 'Home Page', NULL, 'frontpage', $teamname, 1, 'top'", "", "", false);
        if ($sql3)
        {
            echo "<script> alert('Sub Site Added'); window.location = '$pagename';</script>\n";
            exit; 
        }
	}
} 
elseif ($action == "delete") 
{
	$id = $_GET['id'];
	$sql = $data->select_query("subsites", "WHERE id = $id");
	$stuff = $data->fetch_array($sql);
	$oldname = safesql($stuff['teamname'], "text");
	$sql3 = $data->delete_query("subsites", "id=$id", "", "", false);
    $data->delete_query("subcontent", "site=$oldname", "", "", false);
    $data->delete_query("submenu", "site=$oldname", "", "", false);
    if ($sql3)
    {
        echo "<script> alert('Sub Site deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$tpl->assign('editFormAction', $editFormAction);

$tpl->assign('sites', $sites);
$tpl->assign('action', $action);
$tpl->assign('numsites', $numsites);
$filetouse = "admin_subsite.tpl";
?>