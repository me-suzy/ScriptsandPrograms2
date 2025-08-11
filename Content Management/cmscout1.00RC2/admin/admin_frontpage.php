<?php
/**************************************************************************
    FILENAME        :   admin_frontpage.php
    PURPOSE OF FILE :   Manage frontpage items
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
	$module['Content Management']['Frontpage Manager'] = "frontpage";
    $permision['Frontpage Manager'] =1;

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
	$sql = $data->delete_query("frontpage", "id = '$id'", "Frontpage Items", "$id removed from frontpage");
	$action = "";
    if ($sql)
    {
        echo "<script> alert('Item removed'); window.location = '$pagename';</script>\n";
        exit; 
    }
}

if ($submit == "Submit") 
{
	if ($action == "new") 
    {
		$func = $_POST['functions'];
		$pages = $_POST['pages'];
		$pos = 1;
		do 
		{
			$temp = $data->select_query("frontpage", "WHERE pos = '$pos'");
			if ($data->num_rows($temp) != 0) {$pos++;}
		} while ($data->num_rows($temp) != 0); 	
		$sql = $data->insert_query("frontpage", "'', '$pages', '$func', '$pos'", " Frontpage Items", "Added $pages/$func to frontpage");
        if ($sql)
        {
            echo "<script> alert('Item added'); window.location = '$pagename';</script>\n";
            exit; 
        }
        $action = "";
	}
    elseif ($action == "edit") 
    {
		$func = $_POST['functions'];
		$pages = $_POST['pages'];
		$sql = $data->update_query("frontpage", "page = '$pages', function = '$func'", "id=$id", "Frontpage Items", "Changed $id");
        if ($sql)
        {
            echo "<script> alert('Item updated'); window.location = '$pagename';</script>\n";
            exit; 
        }
		$action = "";
	}
}

if (($action =="") || ($action == "view"))
{
	$sql = $data->select_query("frontpage", "ORDER BY pos ASC");
	$numfront = $data->num_rows($sql);
	$frontpages = array();
	$frontpages[] = $data->fetch_array($sql);
	while ($frontpages[] = $data->fetch_array($sql));
	$tpl->assign('frontpages', $frontpages);
	$tpl->assign('numfront', $numfront);
} 
elseif (($action == "new") || ($action == "edit")) 
{
    if ($action == "edit") 
    {
		$sql = $data->select_query("frontpage", "WHERE id='$id'");
		$item = $data->fetch_array($sql);
		$tpl->assign('item', $item);
	}
    
    $sql = $data->select_query("functions", "WHERE type=2");
	$numfunc = 0;
	$func = array();
	while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("frontpage", "WHERE function='{$temp['name']}'");
        if ($data->num_rows($sql2) == 0 || $item['function'] == $temp['name'])
        {
            $func[] = $temp;
            $numfunc++;
        }
    }
    
	$sql = $data->select_query("static_content");
	$numpages = 0;
	$pages = array();
	while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("frontpage", "WHERE page='{$temp['name']}'");
        if ($data->num_rows($sql2) == 0 || $item['page'] == $temp['name'])
        {
            $pages[] = $temp;
            $numpages++;
        }
    }
    
    
	$tpl->assign('func', $func);
	$tpl->assign('numfunc', $numfunc);
	$tpl->assign('page', $pages);
	$tpl->assign('numpages', $numpages);
}
elseif($action == "moveup")
{
    $sql = $data->select_query("frontpage", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 -1;
    $sql = $data->select_query("frontpage", "WHERE pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("frontpage", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("frontpage", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=frontpage");
}
elseif($action == "movedown")
{
    $sql = $data->select_query("frontpage", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 +1;
    $sql = $data->select_query("frontpage", "WHERE pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("frontpage", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("frontpage", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=frontpage");
}

$tpl->assign('id', $id);
$tpl->assign('action', $action);
$tpl->assign('editFormAction', $editFormAction);
$filetouse = "admin_frontpage.tpl";
?>