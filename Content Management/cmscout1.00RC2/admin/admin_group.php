<?php
/**************************************************************************
    FILENAME        :   admin_group.php
    PURPOSE OF FILE :   Manage groups and patrols
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
	$module['User Management']['Groups'] = "group";
    $permision['Groups'] = 1;
	return;
}

if ($check['level'] != 1 && $check['level'] != 0)
{
 error_message("Sorry, you can't access this section");
}		

$listusers = $data->select_query("authuser");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
$id = $_GET['id'];
if ($action == "edit") 
{
	$sql = $data->select_query("authteam", "WHERE id = $id");
	$stuff = $data->fetch_array($sql);
	$tpl->assign('group', $stuff);
	$submit = $_POST['Submit'];
	$oldname = safesql($stuff['teamname'], "text");
	if ($submit == 'Submit') 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the group");
            exit;
        }
        $teamname = safesql($_POST['name'], "text");
		$ispatrol =	safesql($_POST['patrol'], "int");
        $getpoints = safesql($_POST['points'], "int");
		$register =	safesql($_POST['register'], "int");
        
        $subsite =	$_POST['subsite'];
		$sql3 = $data->update_query("authteam", "teamname=$teamname, ispatrol=$ispatrol, getpoints=$getpoints, register=$register", "id = $id", "", "", false);
		$sql = $data->update_query("authuser", "team=$teamname", "team=$oldname", "", "", false);
        if ($ispatrol == 1 || $subsite == 1) 
        {
			$sql = $data->select_query("patrolcontent", "WHERE patrol = $teamname");
			if ($data->num_rows($sql) == 0)
            {
                $data->insert_query("patrolcontent", "'', 'frontpage', 'The patrol leader has not put any information here yet.', $teamname, 1", "", "", false);
			}
            else
            {
                $data->update_query("patrolcontent", "patrol=$teamname", "patrol=$oldname", "", "", false);
            }
			$sql = $data->select_query("patrolmenu", "WHERE patrol = $teamname");
			if ($data->num_rows($sql) == 0) 
            {
                $data->insert_query("patrolmenu", "'', 'Articles', NULL, 'Patrol Articles', $teamname, 1, 'top'", "", "", false);
                $data->insert_query("patrolmenu", "'', 'Photos',  NULL, 'Patrol Photos', $teamname, 2, 'top'", "", "", false);
                $data->insert_query("patrolmenu", "'', 'Home Page', NULL, 'Patrol Home Page', $teamname, 1, 'bottom'", "", "", false);
			}
            else
            {
                $data->update_query("patrolmenu", "patrol=$teamname", "patrol=$oldname", "", "", false);
            }
		}
        else
        {
                $data->delete_query("patrolcontent", "patrol=$teamname", "", "", false);
                $data->delete_query("patrolmenu", "patrol=$teamname", "", "", false);
        }
        if($getpoints == 1 && $ispatrol == 1)
        {
            $sql = $data->select_query("patrolpoints", "WHERE Patrolname = $teamname");
			if ($data->num_rows($sql) == 0) 
            {
				$sql = $data->insert_query("patrolpoints", "'', $teamname, '0'", "", "", false);
			}
        }
        else
        {
                $data->delete_query("patrolpoints", "Patrolname=$teamname", "", "", false);
        }

        if ($sql3)
        {
            echo "<script> alert('Group updated'); window.location = '$pagename';</script>\n";
            exit; 
        }
		$action = '';
	}
} 
elseif ($action == "Add") 
{
	$submit = $_POST['Submit'];
	if ($submit == 'Submit') 
    {
		
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the group");
            exit;
        }
        $teamname = safesql($_POST['name'], "text");
		$ispatrol =	safesql($_POST['patrol'], "int");
        $getpoints = safesql($_POST['points'], "int");
        $register =	safesql($_POST['register'], "int");

		$sql3 = $data->insert_query("authteam", "'', $teamname, $ispatrol, $getpoints, $register", "", "", false);
		if ($ispatrol == 1 || $subsite == 1) 
        {
			$sql = $data->select_query("patrolcontent", "WHERE patrol = $teamname");
			if ($data->num_rows($sql) == 0)
            {
                $data->insert_query("patrolcontent", "'', 'frontpage', 'The patrol leader has not put any information here yet.', $teamname, 1", "", "", false);
			}
			$sql = $data->select_query("patrolmenu", "WHERE patrol = $teamname");
			if ($data->num_rows($sql) == 0) 
            {
                $data->insert_query("patrolmenu", "'', 'Articles', NULL, 'Patrol Articles', $teamname, 1, 'top'", "", "", false);
                $data->insert_query("patrolmenu", "'', 'Photos',  NULL, 'Patrol Photos', $teamname, 2, 'top'", "", "", false);
                $data->insert_query("patrolmenu", "'', 'Home Page', NULL, 'Patrol Home Page', $teamname, 1, 'bottom'", "", "", false);
			}
		}
        if($getpoints == 1 && $ispatrol == 1)
        {
            $sql = $data->select_query("patrolpoints", "WHERE Patrolname = $teamname");
			if ($data->num_rows($sql) == 0) 
            {
				$sql = $data->insert_query("patrolpoints", "'', $teamname, '0'", "", "", false);
			}
        }
        if ($sql3)
        {
            echo "<script> alert('Group Added'); window.location = '$pagename';</script>\n";
            exit; 
        }
	}
} 
elseif ($action == "delete") 
{
	$sql = $data->select_query("authteam", "WHERE id = $id");
	$stuff = $data->fetch_array($sql);
	$oldname = safesql($stuff['teamname'], "text");
	$sql3 = $data->delete_query("authteam", "id=$id", "", "", false);
	if ($stuff['ispatrol'] != 0) 
    {
            $data->delete_query("patrolcontent", "patrol=$oldname", "", "", false);
            $data->delete_query("patrolmenu", "patrol=$oldname", "", "", false);
	}
    if ($stuff['getpoints'] != 0)
    {
	    $sql = $data->select_query("patrolpoints", "WHERE Patrolname = $oldname");
    }
    if ($sql3)
    {
        echo "<script> alert('Group deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
}
$message = "";

$result = $data->select_query("authteam", "ORDER BY id");
$row_teaminfo = array();
$numteams = $data->num_rows($result);
while ($row_teaminfo[] = $data->fetch_array($result));

$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('action', $action);
$tpl->assign('teamname', $teamname);
$tpl->assign('numgroups', $numteams);
$tpl->assign('groups',$row_teaminfo);

$filetouse = "admin_group.tpl";
?>