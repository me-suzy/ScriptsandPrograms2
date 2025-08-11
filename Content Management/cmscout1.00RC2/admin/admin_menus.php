<?php
/**************************************************************************
    FILENAME        :   admin_menus.php
    PURPOSE OF FILE :   Manages menus
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
	$module['Configuration']['Menu Manager'] = "menus";
    $permision['Menu Manager'] =1;

	return;
}

if (($check['level'] != 0) && ($check['level'] != 1))
{
	error_message("Access denied");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$id = isset($_GET['id']) ? $_GET['id'] : "";
$cid = isset($_GET['cid']) ? $_GET['cid'] : "";
$action = isset($_GET['action']) ? $_GET['action'] : "";
$submit = isset($_POST['Submit']) ? $_POST['Submit'] : "";

if ($action == "delcat") 
{
	$sql = $data->delete_query("menu_items", "cat = '$id'", "Menus", "", false);
	$data->delete_query("menu_cats", "id = '$id'", "Menus", "Deleted category $id");
	$action = "view";
    if ($sql)
    {
        echo "<script> alert('Category deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
} 
elseif ($action == "delitem") 
{
	$rid = $_GET['rid'];
	$sql = $data->delete_query("menu_items", "id = '$rid'", "Menus", "Deleted item $rid from $id");
	$data->update_query("menu_cats", "numitems = numitems - 1", "id = '$id'", "", "", false);
	$action = "catview";		
    if ($sql)
    {
        echo "<script> alert('Link removed'); window.location = '$pagename&id=$id&action=catview';</script>\n";
        exit; 
    }
}

if ($submit == "Submit") 
{
	if ($action == "newitem") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the item");
            exit;
        }
        $name = safesql($_POST['name'], "text");
		$item = explode(".", $_POST['items']);
        if ($item[0] == "url")
        {
            $trans= array("&" => "&amp;");
	        $url = strtr($_POST['url'], $trans);
            $url = safesql($url, "text");
            $type=5;
        }
        else
        {
            $url = safesql(NULL, "text");
            switch ($item[1])
            {
                case "box":
                 $type = 3;
                 break;
                case "dyn":
                 $type = 2;
                 break;
                case "stat":
                 $type = 1;
                 break;
                case "sub":
                 $type = 4;
                 break;
            }
        }
        

        
        $item = safesql($item[0], "text");
		$pos = 1;
		do 
		{
			$temp = $data->select_query("menu_items", "WHERE cat = '$id' AND pos = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 
        
		$sql = $data->insert_query("menu_items", "'', $name,  $id, $url, $item, $pos, $type", "Menus", "Added menu item $name");
		$data->update_query("menu_cats", "numitems = numitems + 1", "id=$id", "", "", false);
		$action = "catview";
        if ($sql)
        {
            echo "<script> alert('Link added'); window.location = '$pagename&id=$id&action=catview';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "edititem") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the item");
            exit;
        }
        $name = safesql($_POST['name'], "text");
		$item = explode(".", $_POST['items']);
        if ($item[0] == "url")
        {
            $trans= array("&" => "&amp;");
	        $url = strtr($_POST['url'], $trans);
            $url = safesql($url, "text");
            $type=5;
        }
        else
        {
            $url = safesql(NULL, "text");
            switch ($item[1])
            {
                case "box":
                 $type = 3;
                 break;
                case "dyn":
                 $type = 2;
                 break;
                case "stat":
                 $type = 1;
                 break;
                case "sub":
                 $type = 4;
                 break;
            }
        }
        
        
        $item = safesql($item[0], "text");
		$sql = $data->update_query("menu_items", "name = $name, url = $url, item = $item, type=$type", "id=$id", "Menus", "Edited menu item $name");
		$action = "catview";
		$id = $_GET['cid'];
        if ($sql)
        {
            echo "<script> alert('Link updated'); window.location = '$pagename&id=$id&action=catview';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "newcat") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the item");
            exit;
        }
        $name = safesql($_POST['name'], "text");
		$side = safesql($_POST['location'], "text");
        $show = $_POST['show'];
        $showperm = $_POST['showperm'];
		$pos = 1;
		do 
		{
			$temp = $data->select_query("menu_cats", "WHERE position = '$pos' AND side=$side");
			if ($data->num_rows($temp) != 0) $pos++;
		} while ($data->num_rows($temp) != 0); 
		$sql = $data->insert_query("menu_cats", "'', $name, '0', '$pos', $side, '$show', '$showperm'", "Menus", "Added menu category $name");
		$action = "view";
        if ($sql)
        {
            echo "<script> alert('Category added'); window.location = '$pagename';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "editcat") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the item");
            exit;
        }
        $sql = $data->select_query("menu_cats", "WHERE id=$id");
        $oldcat = $data->fetch_array($sql);
		$name = safesql($_POST['name'], "text");
		$side = $_POST['location'];
        $pos = $oldcat['position'];
        if ($side != $oldcat['side'])
        {
            $pos = 1;
            do 
            {
                $temp = $data->select_query("menu_cats", "WHERE position = '$pos' AND side='$side'");
                if ($data->num_rows($temp) != 0) $pos++;
            } while ($data->num_rows($temp) != 0); 
        }
        echo $pos;
        $showperm = $_POST['showperm'];
        $show = $_POST['show'];
		$sql = $data->update_query("menu_cats", "name =$name, position=$pos, side = '$side', showhead='$show', showwhen = '$showperm'", "id=$id", "Menus", "Edited menu category $name");
		$action = "view";
        if ($sql)
        {
            echo "<script> alert('Category updated'); window.location = '$pagename';</script>\n";
            exit; 
        }
	}
}

if (($action =="") || ($action == "view")) 
{
	$sql = $data->select_query("menu_cats", "WHERE side='left' ORDER BY position ASC");
	$numleft = $data->num_rows($sql);
	$left = array();
	$left[] = $data->fetch_array($sql);
	while ($left[] = $data->fetch_array($sql));

    $sql = $data->select_query("menu_cats", "WHERE side='right' ORDER BY position ASC");
	$numright = $data->num_rows($sql);
	$right = array();
	$right[] = $data->fetch_array($sql);
	while ($right[] = $data->fetch_array($sql));

	$sql = $data->select_query("menu_cats", "WHERE side='top' ORDER BY position ASC");
	$numtop = $data->num_rows($sql);
	$top = array();
	$top[] = $data->fetch_array($sql);
	while ($top[] = $data->fetch_array($sql));

    $tpl->assign("numleft", $numleft);
    $tpl->assign("left", $left);
    $tpl->assign("numright", $numright);
    $tpl->assign("right", $right);
    $tpl->assign("numtop", $numtop);
    $tpl->assign("top", $top);
} 
elseif (($action == "newitem") || ($action == "edititem")) 
{
	$sql = $data->select_query("functions", "WHERE type != 3 AND type != 4 ORDER BY type ASC");
	$numfunc = $data->num_rows($sql);
	$func = array();
	$func[] = $data->fetch_array($sql);
	while ($func[] = $data->fetch_array($sql));
    
	$sql = $data->select_query("static_content");
	$numpages = $data->num_rows($sql);
	$pages = array();
	$pages[] = $data->fetch_array($sql);
	while ($pages[] = $data->fetch_array($sql));
    
	$sql = $data->select_query("subsites");
	$numsub = $data->num_rows($sql);
	$subsite = array();
	$subsite[] = $data->fetch_array($sql);
	while ($subsite[] = $data->fetch_array($sql));

    if ($action == "edititem")
    {
        $sql = $data->select_query("menu_cats", "WHERE id = $cid");
    }
    else
    {
        $sql = $data->select_query("menu_cats", "WHERE id = $id");
    }
    $bit = $data->fetch_array($sql);
    
    $tpl->assign("catname", $bit['name']);
	$tpl->assign('func', $func);
	$tpl->assign('numfunc', $numfunc);
	$tpl->assign('page', $pages);
	$tpl->assign('numpages', $numpages);
	$tpl->assign('subsite', $subsite);
	$tpl->assign('numsub', $numsub);
	if ($action == "edititem") {
		$sql = $data->select_query("menu_items", "WHERE id='$id'");
		$item = $data->fetch_array($sql);
		$tpl->assign('item', $item);
	}
} 
elseif ($action == "catview") 
{
	$sql = $data->select_query("menu_items", "WHERE cat=$id ORDER BY pos ASC");
	$sql2 = $data->select_query("menu_cats", "WHERE id=$id");
	$menu = $data->fetch_array($sql2);
	$numitems = $data->num_rows($sql);
	$menuitems = array();
	$actions = array();
	while ($temp = $data->fetch_array($sql))
    {
		if ($temp['item'] != "url") 
        {
            $itemsql = $data->select_query("functions", "WHERE name='{$temp['item']}'");
            $itemsql2 = $data->select_query("static_content", "WHERE name='{$temp['item']}'");
            $itemsql3 = $data->select_query("subsites", "WHERE name='{$temp['item']}'");
            if ($data->num_rows($itemsql) == 1)
            {
                $item = $data->fetch_array($itemsql);
                if($item['type'] == 2)
                {
                    $actions[] = "Dynamic Content: " . $temp['item'];
                }
                elseif ($item['type'] == 1)
                {
                    $actions[] = "Side Box: " . $temp['item'];                    
                }
            }
            elseif ($data->num_rows($itemsql2) == 1)
            {
                $actions[] = "Static Content: " . $temp['item'];
            }
            elseif($data->num_rows($itemsql3) == 1)
            {
                $actions[] = "Sub Site: " . $temp['item'];
            }
            else
            {
                $actions[] = "Item does not exist anymore";
            }
		} 
        else 
        {
			$actions[] = "URL: " . $temp['url'];
		}
		$menuitems[] = $temp;
	}
	$tpl->assign('item', $menuitems);
	$tpl->assign('actions', $actions);
	$tpl->assign('numitems', $numitems);
	$tpl->assign('menu', $menu);
} 
elseif($action == "editcat")
{
	$sql2 = $data->select_query("menu_cats", "WHERE id='$id'");
	$menu = $data->fetch_array($sql2);
	$tpl->assign('menu', $menu);
}
elseif($action == "moveup")
{
    $sql = $data->select_query("menu_cats", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];

    $temppos = $pos1 - 1;
    $sql = $data->select_query("menu_cats", "WHERE side='{$row['side']}' AND position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['position'];
    if ($pos2 == 0 || $pos1 == 0)
        header("Location: $server"."?page=menus"); 
        
    $data->update_query("menu_cats", "position=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("menu_cats", "position=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=menus");
}
elseif($action == "movedown")
{
    $sql = $data->select_query("menu_cats", "WHERE id=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];
    $temppos = $pos1 +1;
    $sql = $data->select_query("menu_cats", "WHERE side='{$row['side']}' AND position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['position'];
    $data->update_query("menu_cats", "position=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("menu_cats", "position=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=menus");
}
elseif($action == "moveitemup")
{
    $sql = $data->select_query("menu_items", "WHERE id='$id'");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 -1;
    $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2 = $row2['pos'];
    
    if ($pos2 == 0 || $pos1 == 0)
        header("Location: $server"."?page=menus&action=catview&id=$cid"); 
        
    $data->update_query("menu_items", "pos='$pos2'", "id={$row['id']}", "", "", false);
    $data->update_query("menu_items", "pos='$pos1'", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=menus&action=catview&id=$cid");
}
elseif($action == "moveitemdown")
{
    $sql = $data->select_query("menu_items", "WHERE id='$id'");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 + 1;
    $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2 = $row2['pos'];
    $data->update_query("menu_items", "pos='$pos2'", "id={$row['id']}", "", "", false);
    $data->update_query("menu_items", "pos='$pos1'", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=menus&action=catview&id=$cid");
}
elseif ($action=="fixcat")
{
    $sql = $data->select_query("menu_cats", "WHERE side='left' ORDER BY position ASC");
    if($data->num_rows($sql)>0)
    {
        $i = 1;
        while($temp=$data->fetch_array($sql))
        {
            $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
            $i++;
        }
    }

    $sql = $data->select_query("menu_cats", "WHERE side='right' ORDER BY position ASC");
    if($data->num_rows($sql)>0)
    {
        $i = 1;
        while($temp=$data->fetch_array($sql))
        {
            $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
            $i++;
        }
    }

    $sql = $data->select_query("menu_cats", "WHERE side='top' ORDER BY position ASC");
    if($data->num_rows($sql)>0)
    {
        $i = 1;
        while($temp=$data->fetch_array($sql))
        {
            $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
            $i++;
        }
    }

     header("Location: $server"."?page=menus");
}


$tpl->assign('cid', $cid);
$tpl->assign('id', $id);
$tpl->assign('action', $action);
$tpl->assign('editFormAction', $editFormAction);
$filetouse = "admin_menus.tpl";
?>