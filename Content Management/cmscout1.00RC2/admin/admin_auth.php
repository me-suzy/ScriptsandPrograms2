<?php
/**************************************************************************
    FILENAME        :   admin_auth.php
    PURPOSE OF FILE :   Manage access authorizations
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
	$module['Configuration']['Authorization'] = "auth";
    $permision['Authorization'] = 1;
	return;
}

if (($check['level'] != 0) && ($check['level'] != 1))
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$id = $_GET['id'];
$action = $_GET['action'];
$submit = $_POST['Submit'];

if ($action == "delete") 
{
	$sql = $data->delete_query("auth", "id = $id", "Authorization", "Deleted auth setting");
    $action = "";
    if ($sql)
    {
        echo "<script> alert('Authorization removed. All users now have access to this page'); window.location = '$pagename';</script>\n";
        exit; 
    }
}

if ($submit == "Submit") 
{
	if ($action == "new") 
    {
        $sql = $data->select_query("authteam");
        $numgroups = $data->num_rows($sql);
        $groups = $data->fetch_array($sql);
        do 
        { 
            $auths[$groups['teamname']] = $_POST[str_replace(' ', '', $groups['teamname'])] == 1 ? 1 : 0;
        } while ($groups = $data->fetch_array($sql));
        $auths['Guest'] = $_POST['guest'] == 1 ? 1 : 0;
        $auths = serialize($auths);
        $name = $_POST['name'];
        $data->insert_query("auth", "'', '$name', '$auths'", "Authorization", "Added Page $name");
        if ($sql)
        {
            echo "<script> alert('Add authorization'); window.location = '$pagename';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "edit") 
    {
        $sql = $data->select_query("authteam");
        $numgroups = $data->num_rows($sql);
        $groups = $data->fetch_array($sql);
        do { 
            $auths[$groups['teamname']] = $_POST[str_replace(' ', '', $groups['teamname'])] == 1 ? 1 : 0;
        } while ($groups = $data->fetch_array($sql));
        $auths['Guest'] = $_POST['guest'] == 1 ? 1 : 0;
        $auths = serialize($auths);
        $name = $_POST['name'];
        $data->update_query("auth", "page = '$name', level = '$auths'", "id = $id", "Authorization", "changed Page $name");
        $action = "";
        if ($sql)
        {
            echo "<script> alert('Updated authorization'); window.location = '$pagename';</script>\n";
            exit; 
        }
	}
}

if (($action =="") || ($action == "view")) 
{
	$sql = $data->select_query("auth");
	$numauth = $data->num_rows($sql);
	$auths = array();
	while ($temp = $data->fetch_array($sql))
    {
        $check = $data->select_query("functions", "WHERE code='{$temp['page']}'");
        if ($data->num_rows($check) > 0)
        {
            $stuff = $data->fetch_array($check);
            $temp['page'] = $stuff['name'];
        }
        $auths[] = $temp;
    }
	$tpl->assign('auths', $auths);
	$tpl->assign('numauths', $numauth);
} 
elseif (($action == "new") || ($action == "edit")) 
{
	if ($action == "edit")
    {
        $sql = $data->select_query("auth", "WHERE id='$id'");
		$temp = $data->fetch_array($sql);
        $extrasql = "AND page != '{$temp['page']}'";
    }
    else
    {
        $extrasql = "";
    }
    $sql = $data->select_query("functions", "WHERE type=2 OR type=3");
	$func = array();
	while ($temp = $data->fetch_array($sql))
    {
        if($data->num_rows($data->select_query("auth", "WHERE page='{$temp['code']}' $extrasql")) == 0)
            $func[] = $temp;
    }
    $numfunc = count($func);
    
	$sql = $data->select_query("static_content");
	$pages = array();
	while ($temp = $data->fetch_array($sql))
    {
        if($data->num_rows($data->select_query("auth", "WHERE page='{$temp['name']}' $extrasql")) == 0)
            $pages[] = $temp;
    }
    $numpages = count($pages);
	
    $sql = $data->select_query("authteam");
	$numgroups = $data->num_rows($sql);
	$groups = array();
	$groups[] = $data->fetch_array($sql);
	while ($groups[] = $data->fetch_array($sql));

    $tpl->assign('func', $func);
	$tpl->assign('numfunc', $numfunc);
	$tpl->assign('page', $pages);
	$tpl->assign('numpages', $numpages);
	$tpl->assign('group', $groups);
	$tpl->assign('numgroups', $numgroups);
	if ($action == "edit") 
    {
		$sql = $data->select_query("auth", "WHERE id='$id'");
		$temp = $data->fetch_array($sql);
        $item['page'] = $temp['page'];
        $authtemp = unserialize($temp['level']);
        $numauths = count($auths);
        $auths = array();
        while(list($group, $auth) = each($authtemp) )
        {
            $notfound = true;
            for($i=0;$i<count($groups);$i++)
            {
                if($groups[$i]['teamname'] == $group)
                {
                    $auths[] = $auth;
                    $notfound = false;
                }
            }
            if ($notfound && $group != 'guest')
            {
                $auths[] = '1';
            }
        }
        $tpl->assign('guest', $authtemp['Guest']);
        $tpl->assign('auths', $auths);
		$tpl->assign('item', $item);
	}
}

$tpl->assign('id', $id);
$tpl->assign('action', $action);
$tpl->assign('editFormAction', $editFormAction);
$filetouse = "admin_auth.tpl";
?>