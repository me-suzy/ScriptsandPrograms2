<?php
/**************************************************************************
    FILENAME        :   admin_advancements.php
    PURPOSE OF FILE :   Manages award schemes
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
	$module['Troop Content Management']['Award Schemes'] = "advancements";
    $permision['Award Schemes'] = 1;
	return;
}

if ($check['level'] != 1 && $check['level'] != 0)
{
 error_message("Sorry, you can't access this section");
}	

$editFormAction = $_SERVER['PHP_SELF'];
$editFormAction .= "?page=advancements";

$id = $_GET['id'];
$rid = $_GET['rid'];
$Submit = $_POST['Submit'];
$action = $_GET['action'];
if ($action == "deladd") 
{
	$data->delete_query("requirements", "advancement = '$id'", "Advancements", "Deleted requirement $rid from $id");
	$sql = $data->delete_query("advancements", "id = '$id'", "Advancements", "Deleted advancement $id");
    if ($sql)
    {
        echo "<script> alert('Award Badge deleted'); window.location = '$pagename&action=viewsch&id={$_GET['sid']}';</script>\n";
        exit; 
    }
} 
elseif ($action == "delreq") 
{
	$sql = $data->delete_query("requirements", "id = '$rid'", "Advancements", "Deleted requirement $rid from $id");	
    if ($sql)
    {
        echo "<script> alert('Requirement deleted'); window.location = '$pagename&action=viewadd&id=$id&sid={$_GET['sid']}';</script>\n";
        exit; 
    }
}
elseif ($action == "delsch")
{
	$sql = $data->select_query("advancements", "WHERE scheme=$id");
    while ($temp = $data->fetch_array($sql))
    {
        $data->delete_query("requirements", "advancement = '{$temp['ID']}'");
    }
	$sql = $data->delete_query("advancements", "scheme = '$id'");
	$sql = $data->delete_query("awardschemes", "id = '$id'");
    if ($sql)
    {
        echo "<script> alert('Award Scheme deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
}
elseif($action == "moveup")
{
    $sql = $data->select_query("advancements", "WHERE ID=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];
    $temppos = $pos1 -1;
    if($tempos <= 0) $tempos=1;
    $sql = $data->select_query("advancements", "WHERE position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['position'];
    $data->update_query("advancements", "position=$pos2", "ID={$row['ID']}", "", "", false);
    $data->update_query("advancements", "position=$pos1", "ID={$row2['ID']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=advancements");
}
elseif($action == "movedown")
{
    $sql = $data->select_query("advancements", "WHERE ID=$id");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];
    $temppos = $pos1 +1;
    $sql = $data->select_query("advancements", "WHERE position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['position'];
    $data->update_query("advancements", "position=$pos2", "ID={$row['ID']}", "", "", false);
    $data->update_query("advancements", "position=$pos1", "ID={$row2['ID']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=advancements");
}
elseif($action == "moveitemup")
{
    $sql = $data->select_query("requirements", "WHERE ID='$rid'");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];
    $temppos = $pos1 -1;
    if($tempos <= 0) $tempos=1;
    $sql = $data->select_query("requirements", "WHERE advancement='$id' AND position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2 = $row2['position'];
    $data->update_query("requirements", "position='$pos2'", "ID={$row['ID']}", "", "", false);
    $data->update_query("requirements", "position='$pos1'", "ID={$row2['ID']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=advancements&action=editadd&id=$id");
}
elseif($action == "moveitemdown")
{
    $sql = $data->select_query("requirements", "WHERE ID='$rid'");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['position'];
    $temppos = $pos1 +1;
    //echo $temppos . "<br>";
    $sql = $data->select_query("requirements", "WHERE advancement='$id' AND position='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2 = $row2['position'];
    //echo $pos1 . ') pos1 - pos2 ('. $pos2 . ') -row1 ('. $row['ID'] . ') -row2 ('. $row2['ID'];
    $data->update_query("requirements", "position='$pos2'", "ID={$row['ID']}", "", "", false);
    $data->update_query("requirements", "position='$pos1'", "ID={$row2['ID']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=advancements&action=editadd&id=$id");
}

if ($Submit == 'Submit') 
{
	if ($action == "newadd") 
    {
        if ($_POST['adv'] == '')
        {
            error_message("You need to enter a name for the award badge");
            exit;
        }
        $scheme = $_GET['sid'];
        $add = safesql($_POST['adv'], "text");
		$pos = 1;
		do 
		{
			$temp = $data->select_query("advancements", "WHERE position = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 		
		$sql = $data->insert_query("advancements", "'', $add, '$pos', $scheme", "Advancements", "Added $add");
        if ($sql)
        {
            echo "<script> alert('Award badge added'); window.location = '$pagename&action=viewsch&id=$scheme';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "editadd") 
    {
        if ($_POST['adv'] == '')
        {
            error_message("You need to enter a name for the award badge");
            exit;
        }
        $scheme = $_GET['sid'];
        $add = safesql($_POST['adv'], "text");
		$sql = $data->update_query("advancements", "advancement = $add", "id = '$id'", "Advancements", "Changed $id to $add");		
        if ($sql)
        {
            echo "<script> alert('Award badge updated'); window.location = '$pagename&action=viewsch&id=$scheme';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "editreq") 
    {
        if ($_POST['req'] == '')
        {
            error_message("You need to enter a name for the requirement");
            exit;
        }
        $reqid = $_GET['rid'];
		$req= safesql($_POST['req'], "text");
		$desc= safesql($_POST['desc'], "text");
		$sql = $data->update_query("requirements", "item = $req, description = $desc","id = '$reqid'", "Advancements", "Changed $reqid to $req");
		$action = "viewadd";
        if ($sql)
        {
            echo "<script> alert('Requirement updated'); window.location = '$pagename&action=viewadd&id=$id&sid={$_GET['sid']}';</script>\n";
            exit; 
        }
	} 
    elseif ($action == "newreq") 
    {
        if ($_POST['req'] == '')
        {
            error_message("You need to enter a name for the requirement");
            exit;
        }
        $req= safesql($_POST['req'], "text");
		$desc= safesql($_POST['desc'], "text");
		$pos = 1;
		do 
		{
			$temp = $data->select_query("requirements", "WHERE advancement = '$id' AND position = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 
		$sql = $data->insert_query("requirements", "'', $req, $desc, '$id', '$pos'", "Advancements", "Added $req to $id");
		$action = "viewadd";		
        if ($sql)
        {
            echo "<script> alert('Requirement added'); window.location = '$pagename&action=viewadd&id=$id&sid={$_GET['sid']}';</script>\n";
            exit; 
        }
	}
    elseif ($action == "newsch")
    {
        if ($_POST['adv'] == '')
        {
            error_message("You need to enter a name for the award scheme");
            exit;
        }
        $add = safesql($_POST['adv'], "text");	
		$sql = $data->insert_query("awardschemes", "'', $add");
        if ($sql)
        {
            echo "<script> alert('Award Scheme added'); window.location = '$pagename';</script>\n";
            exit; 
        }
    }
    elseif ($action == "editsch")
    {
        if ($_POST['adv'] == '')
        {
            error_message("You need to enter a name for the award scheme");
            exit;
        }
        $add = safesql($_POST['adv'], "text");
		$sql = $data->update_query("awardschemes", "name = $add", "id = '$id'");		
        if ($sql)
        {
            echo "<script> alert('Award Scheme updated'); window.location = '$pagename';</script>\n";
            exit; 
        }
    }
}

if ($action == "viewadd")
{
    $result = $data->select_query("advancements", "WHERE id = '$id'");
    $row = $data->fetch_array($result);
    $advan = $row['advancement'];
    $result = $data->select_query("requirements", "WHERE advancement = '$id' ORDER BY position ASC");
    $req  = array();
    $numreqs = $data->num_rows($result);
    while ($req[]= $data->fetch_array($result));
    
    $tpl->assign("advan", $advan);
    $tpl->assign("req", $req);
    $tpl->assign("numreqs", $numreqs);
    $tpl->assign("sid", $_GET['sid']);
    $tpl->assign("id", $id);

}
elseif ($action == "viewsch")
{
    $result = $data->select_query("advancements", "WHERE scheme = $id ORDER BY position ASC");
    $adv  = array();
    $numads = $data->num_rows($result);
    while ($row = $data->fetch_array($result))
    {
        $sql = $data->select_query("requirements", "WHERE advancement={$row['ID']}");
        $row['numitems'] = $data->num_rows($sql);
        $adv[] = $row;
    }
    
    $result = $data->select_query("awardschemes", "WHERE id = '$id'");
    $row = $data->fetch_array($result);
    $scheme = $row['name'];   
    
    $tpl->assign("scheme", $scheme);
    $tpl->assign("sid", $id);
}
elseif ($action == "editreq") 
{
    $result = $data->select_query("advancements", "WHERE id = '$id'");
    $row = $data->fetch_array($result);
    $advan = $row['advancement'];
	$rid = $_GET['rid'];
	$result = $data->select_query("requirements", "WHERE id = '$rid'");
	$row = $data->fetch_array($result);
	$tpl->assign("requirement", $row);	
    $tpl->assign("id", $id);
    $tpl->assign("rid", $rid);
    $tpl->assign("advan", $advan);
    $tpl->assign("sid", $_GET['sid']);
}
elseif($action == "newreq")
{
    $result = $data->select_query("advancements", "WHERE id = '$id'");
    $row = $data->fetch_array($result);
    $advan = $row['advancement'];
    $tpl->assign("advan", $advan);
    $tpl->assign("id", $id);
    $tpl->assign("sid", $_GET['sid']);
}
elseif($action == "editsch")
{
    $result = $data->select_query("awardschemes", "WHERE id = '$id'");
    $row = $data->fetch_array($result);
    $advan = $row['name'];
    
    $tpl->assign("advan", $advan);
    $tpl->assign("id", $id);
}
elseif ($action == "editadd")
{
    $result = $data->select_query("advancements", "WHERE ID = '$id'");
    $row = $data->fetch_array($result);
    $advan = $row['advancement'];
    $tpl->assign("sid", $_GET['sid']);
    $tpl->assign("advan", $advan);
    $tpl->assign("id", $id);
}
elseif ($action == "newadd")
{
    $tpl->assign("sid", $_GET['sid']);
}
else
{
    $result = $data->select_query("awardschemes");
    $adv  = array();
    $numschemes = $data->num_rows($result);
    while ($row = $data->fetch_array($result))
    {
        $sql = $data->select_query("advancements", "WHERE scheme ={$row['id']}");
        $row['numitems'] = $data->num_rows($sql);
        $schemes[] = $row;
    }
}

$tpl->assign('numreqs', $numreqs);
$tpl->assign('adv', $adv);
$tpl->assign('numads', $numads);
$tpl->assign('schemes', $schemes);
$tpl->assign('numschemes', $numschemes);
$tpl->assign('action',$action);
$tpl->assign('editFormAction',$editFormAction);

$filetouse = "admin_advancements.tpl";
?>