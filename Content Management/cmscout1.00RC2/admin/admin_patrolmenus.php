<?php
/**************************************************************************
    FILENAME        :   admin_patrolmenus.php
    PURPOSE OF FILE :   Manages patrolmenus
    LAST UPDATED    :   21 November 2005
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

if ($level != 4 && $level != 3 && $level != 2 && $level != 1 && $level != 0) 
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
$patrolname = $_GET['patrol'];
if ($action == "delete") 
{
	$delete = $data->delete_query("patrolmenu", "id = '$id'", "Menus", "Deleted item $rid from $id");
	$action = "";		
    if ($delete)
    {   
        echo "<script> alert('Item Deleted'); window.location = '$pagename&patrol=$patrolname';</script>\n";
        exit;   
    }  
}


if ($submit == "Submit") 
{
	if ($action == "new") 
    {
		$name = safesql($_POST['name'], "text");
		$item = $_POST['items'];
        if ($item == "url")
        {
            if ($_POST['url'] == '')
            {
                error_message("You need to enter a url if the item is a url item.");
                exit;
            }
            $url = safesql($_POST['url'], "text");
        }
        else
        {
            $url = safesql(NULL, "text");
        }
        
        if ($_POST['items'] == '')
        {
            error_message("You need to enter a name for the menu item.");
            exit;
        }
        
        $item = safesql($item, "text");
        $side = safesql($_POST['location'], "text");
		$pos = 1;
		do 
		{
			$temp = $data->select_query("patrolmenu", "WHERE side=$side AND pos = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 
        
        $patrol = safesql($patrolname, "text");
		$update = $data->insert_query("patrolmenu", "'', $name, $url, $item, $patrol, $pos, $side", "Menus", "Added menu item $name");
        if ($update)
        {
            echo "<script> alert('Your menu item has been added'); window.location = '$pagename&patrol=$patrolname';</script>\n";
            exit;     
        }            
	} elseif ($action == "edit") {
		$name = safesql($_POST['name'], "text");
		$item = $_POST['items'];
        if ($item == "url")
        {
            if ($_POST['url'] == '')
            {
                error_message("You need to enter a url if the item is a url item.");
                exit;
            }
            $url = safesql($_POST['url'], "text");
        }
        else
        {
            $url = safesql(NULL, "text");
        }
        
        if ($_POST['items'] == '')
        {
            error_message("You need to enter a name for the menu item.");
            exit;
        }
        
        $item = safesql($item, "text");
        $side = safesql($_POST['location'], "text");
		$pos = 1;
		do 
		{
			$temp = $data->select_query("patrolmenu", "WHERE side=$side AND pos = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 
        
        $patrol = safesql($patrolname, "text");
		$update = $data->update_query("patrolmenu", "name = $name, url = $url, item = $item, side=$side", "id=$id", "Menus", "Added menu item $name");
		$action = "";
        if($update)
        {
            echo "<script> alert('Your menu item has been updated'); window.location = '$pagename&patrol=$patrolname';</script>\n";
            exit;   
        }            
	}
}

if (($action =="") || ($action == "view")) 
{

    $sql = $data->select_query("patrolmenu", "WHERE patrol='$patrolname' AND side='side' ORDER BY pos ASC");
	$numside = $data->num_rows($sql);
	$side = array();
    while ($temp = $data->fetch_array($sql))
    {
        if ($temp['item'] != "url") 
        {
            $itemsql = $data->select_query("functions", "WHERE name='{$temp['item']}'");
            if ($data->num_rows($itemsql) == 1)
            {
                $item = $data->fetch_array($itemsql);
                if($item['type'] == 4)
                {
                    $temp['action'] = "Dynamic Content: " . $temp['item'];
                }
            }
            else
            {
                $itemsql = $data->select_query("patrolcontent", "WHERE name='{$temp['item']}'");
                if ($data->num_rows($itemsql) == 1)
                {
                    $temp['action'] = "Static Content: " . $temp['item'];
                }
                else
                {
                    $temp['action'] = "Item does not exist anymore";
                }
            }
		} 
        else 
        {
			$temp['action'] = "URL: " . $temp['url'];
		}
		$side[] = $temp;
	}

    $sql = $data->select_query("patrolmenu", "WHERE patrol='$patrolname' AND side='top' ORDER BY pos ASC");
	$numtop = $data->num_rows($sql);
	$top = array();
    while ($temp = $data->fetch_array($sql))
    {
		if ($temp['item'] != "url") 
        {
            $itemsql = $data->select_query("functions", "WHERE name='{$temp['item']}'");
            if ($data->num_rows($itemsql) == 1)
            {
                $item = $data->fetch_array($itemsql);
                if($item['type'] == 4)
                {
                    $temp['action'] = "Dynamic Content: " . $temp['item'];
                }
            }
            else
            {
                $itemsql = $data->select_query("patrolcontent", "WHERE name='{$temp['item']}'");
                if ($data->num_rows($itemsql) == 1)
                {
                    $temp['action'] = "Static Content: " . $temp['item'];
                }
                else
                {
                    $temp['action'] = "Item does not exist anymore";
                }
            }
		} 
        else 
        {
			$temp['action'] = "URL: " . $temp['url'];
		}
		$top[] = $temp;
	}

	$sql = $data->select_query("patrolmenu", "WHERE patrol='$patrolname' AND side='bottom' ORDER BY pos ASC");
	$numbottom = $data->num_rows($sql);
	$bottom = array();
    while ($temp = $data->fetch_array($sql))
    {
		if ($temp['item'] != "url") 
        {
            $itemsql = $data->select_query("functions", "WHERE name='{$temp['item']}'");
            if ($data->num_rows($itemsql) == 1)
            {
                $item = $data->fetch_array($itemsql);
                if($item['type'] == 4)
                {
                    $temp['action'] = "Dynamic Content: " . $temp['item'];
                }
            }
            else
            {
                $itemsql = $data->select_query("patrolcontent", "WHERE name='{$temp['item']}'");
                if ($data->num_rows($itemsql) == 1)
                {
                    $temp['action'] = "Static Content: " . $temp['item'];
                }
                else
                {
                    $temp['action'] = "Item does not exist anymore";
                }
            }
		} 
        else 
        {
			$temp['action'] = "URL: " . $temp['url'];
		}
		$bottom[] = $temp;
	}

    $tpl->assign("numside", $numside);
    $tpl->assign("side", $side);
    $tpl->assign("numtop", $numtop);
    $tpl->assign("top", $top);
    $tpl->assign("numbottom", $numbottom);
    $tpl->assign("bottom", $bottom);
} 
elseif (($action == "new") || ($action == "edit")) 
{
	$sql = $data->select_query("functions", "WHERE type = 4");
	$numfunc = $data->num_rows($sql);
	$func = array();
	while ($func[] = $data->fetch_array($sql));
    
	$sql = $data->select_query("patrolcontent", "WHERE patrol = '$patrolname'");
	$numpages = $data->num_rows($sql);
	$pages = array();
	while ($pages[] = $data->fetch_array($sql));
    
	$tpl->assign('func', $func);
	$tpl->assign('numfunc', $numfunc);
	$tpl->assign('page', $pages);
	$tpl->assign('numpages', $numpages);
    
	if ($action == "edit") 
    {
		$sql = $data->select_query("patrolmenu", "WHERE id='$id'");
		$item = $data->fetch_array($sql);
		$tpl->assign('item', $item);
	}
} 
elseif($action == "moveup")
{
    $sql = $data->select_query("patrolmenu", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 -1;
    $sql = $data->select_query("patrolmenu", "WHERE side='{$row['side']}' AND pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    if ($pos2 == 0 || $pos1 == 0)
    {
        header("Location: $server"."?page=patrolmenus&patrol=$patrolname");
    }
    $data->update_query("patrolmenu", "pos=$pos2", "id='{$row['id']}'", "", "", false);
    $data->update_query("patrolmenu", "pos=$pos1", "id='{$row2['id']}'", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=patrolmenus&patrol=$patrolname");
}
elseif($action == "movedown")
{
    $sql = $data->select_query("patrolmenu", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 +1;
    $sql = $data->select_query("patrolmenu", "WHERE side='{$row['side']}' AND pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("patrolmenu", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("patrolmenu", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=patrolmenus&patrol=$patrolname");
}

$tpl->assign("patrolname", $patrolname);
$tpl->assign('cid', $cid);
$tpl->assign('id', $id);
$tpl->assign('action', $action);
$tpl->assign('editFormAction', $editFormAction);
$filetouse = "admin_patrolmenus.tpl";
?>