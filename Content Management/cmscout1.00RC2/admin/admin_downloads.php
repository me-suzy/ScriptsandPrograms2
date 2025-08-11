<?php
/**************************************************************************
    FILENAME        :   admin_downloads.php
    PURPOSE OF FILE :   Manage downloads and download categories
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
	$module['Troop Content Management']['Downloads'] = "downloads";
    $permision['Downloads'] = 2;
	return;
}

if ($level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
$id = $_GET['id'];
$did = $_GET['did'];

if ($action == 'delete') 
{
	$sqlq = $data->delete_query("download_cats", "id=$id", "", "", false);
	if ($sqlq) 
    { 
		$sqlq = $data->delete_query("downloads","cat=$id", 'Downloads',  "Deleted category");		
        if ($sqlq)
        {
            echo "<script> alert('Download Category deleted'); window.location = '$pagename';</script>\n";
            exit; 
        }
	}
} 
elseif ($action == "deletedown") 
{
	$sqlq = $data->delete_query("downloads", "id=$did", "Downloads", "Download for category $id deleted");
	$action = "view";
    if ($sqlq)
    {
        echo "<script> alert('Download deleted'); window.location = '$pagename&action=view&id=$id';</script>\n";
        exit; 
    }
}
elseif ($action == 'publish') 
{
	$sqlq = $data->update_query("downloads", "allowed = 1", "id=$did", "Downloads", "Published $did");
    header("Location: $pagename&action=view&id=$id");
}
elseif ($action == 'unpublish') 
{
	$sqlq = $data->update_query("downloads", "allowed = 0", "id=$did", "Downloads", "Unpublished $did");
    header("Location: $pagename&action=view&id=$id");
}


$submit = $_POST['Submit'];
if ($submit == "Submit") 
{
    if ($action == "adddown") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to enter a name for the download");
            exit;
        }
        if ($_POST['desc'] == '')
        {
            error_message("You need to enter a description for the download");
            exit;
        }
        $name = safesql($_POST['name'], "text");
		$desc = safesql($_POST['desc'], "text");
		$where = "../" . $config['downloadpath'] . "/";
			$message = $message . 'Uploading ' . $_FILES['file']['name'] . ' (' .$_FILES['file']['type'] . ', ' .ceil($_FILES['file']['size'] / 1024) . ' Kb).<br />';
			if (!file_exists($where . $_FILES['file']['name']))
			{
                move_uploaded_file($_FILES['file']['tmp_name'],$where . $_FILES['file']['name']);
			}
            
			$file = safesql($_FILES['file']['name'], "text");
			$sql = $data->insert_query("downloads", "'', $name, $desc, '$id', $file, '0', '".ceil($_FILES['file']['size'] / 1024)."','{$check['uname']}', 1", "Downloads", "Added Download $name");
			$action="view";
            if ($sql)
            {
                echo "<script> alert('Download added'); window.location = '$pagename&action=view&id=$id';</script>\n";
                exit; 
            }
	} 
    elseif ($action == "editdown") 
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to enter a name for the download");
            exit;
        }
        if ($_POST['desc'] == '')
        {
            error_message("You need to enter a description for the download");
            exit;
        }
        $name = safesql($_POST['name'], "text");
		$desc = safesql($_POST['desc'], "text");
		$where = "../" . $config['downloadpath'] . "/";
		if ($_FILES['file']['name'] != '') {	
			$message = $message . 'Uploading ' . $_FILES['file']['name'] . ' (' .$_FILES['file']['type'] . ', ' .ceil($_FILES['file']['size'] / 1024) . ' Kb).<br />';
			if (file_exists($where . $_FILES['file']['name']))
			{
				$message = $message .  $_FILES['file']['name'] . ' already exists.<br> ';
				$message = $message . "Using existing file on server instead.<br>";
			}
			else
			{
				echo $_FILES['file']['tmp_name'] . '<br>' . $_FILES['file']['name'] . '<br>' .  $_FILES['file']['size'];
				move_uploaded_file($_FILES['file']['tmp_name'],$where . $_FILES['file']['name']);
				$message = $message . "File successfully uploaded.<br>";
			}
			$file = safesql($_FILES['file']['name'], "text");
			$sql = $data->update_query("downloads", "name = $name, descs = $desc, file = $file", "id = '$did'", "Downloads", "Changed Download $name");
		} 
        else 
        {
			$sql = $data->update_query("downloads", "name = $name, descs = $desc", "id = '$did'", "Downloads", "Changed Download $name");
		}	
        if ($sql)
        {
            echo "<script> alert('Download updated'); window.location = '$pagename&action=view&id=$id';</script>\n";
            exit; 
        }
	    $action="view";
	}
    elseif ($action == "add")
    {
        if ($_POST['catname'] == '')
        {
            error_message("You need to enter a name for the category");
            exit;
        }
        $catname = safesql($_POST['catname'], "text");
        
        $downauths['Guest'] = $_POST['dguest'];

        $sql = $data->select_query("authteam");
        $groups = $data->fetch_array($sql);
        do { 
            $upauths[$groups['teamname']] = $_POST["u".$groups['id']];
        } while ($groups = $data->fetch_array($sql));
        
        $sql = $data->select_query("authteam");
        $groups = $data->fetch_array($sql);
        do { 
            $downauths[$groups['teamname']] = $_POST["d".$groups['id']];
        } while ($groups = $data->fetch_array($sql));
        $downauths = safesql(serialize($downauths), "text");
        $upauths = safesql(serialize($upauths), "text");
        
        $sql = $data->insert_query("download_cats", "'', $catname, $upauths, $downauths", "Downloads", "Added download cat $catname");
        if ($sql)
        {
            echo "<script> alert('Category added'); window.location = '$pagename';</script>\n";
            exit; 
        }
		$action = "";
    }
    elseif ($action == "edit")
    {
        if ($_POST['catname'] == '')
        {
            error_message("You need to enter a name for the category");
            exit;
        }
        $catname = addslashes($_POST['catname']);
        
        $downauths['Guest'] = $_POST['dguest'];
        $sql = $data->select_query("authteam");
        $groups = $data->fetch_array($sql);
        do { 
            $upauths[$groups['teamname']] = $_POST["u".$groups['id']];
        } while ($groups = $data->fetch_array($sql));
        
        $sql = $data->select_query("authteam");
        $groups = $data->fetch_array($sql);
        do { 
            $downauths[$groups['teamname']] = $_POST["d".$groups['id']];
        } while ($groups = $data->fetch_array($sql));
        
        $downauths = serialize($downauths);
        $upauths = serialize($upauths);
        
        $sql = $data->update_query("download_cats", "name = '$catname', upauth = '$upauths', downauth = '$downauths'", "id = $id", "Downloads", "changed download cat $catname");
        if ($sql)
        {
            echo "<script> alert('Category Updated'); window.location = '$pagename';</script>\n";
            exit; 
        }
    }
}

if ($action == "view") 
{
	$query = $data->select_query("download_cats", "WHERE id = $id");
	$catinfo = $data->fetch_array($query);
    $down_query = $data->select_query("downloads", "WHERE cat='$id'");
    $numdown = $data->num_rows($down_query);
    $downloads = array();
    $downloads[] = $data->fetch_array($down_query);
    while ($downloads[] = $data->fetch_array($down_query));
	$tpl->assign("downloads", $downloads);
	$tpl->assign("numdown", $numdown);
	$tpl->assign("catinfo", $catinfo);
	$tpl->assign('id', $id);
	$tpl->assign("downpath", "../" . $config["downloadpath"] . "/");
} 
elseif ($action == "adddown") 
{
} 
elseif ($action == "editdown") 
{
	$que = $data->select_query("downloads", "WHERE id = '$did'");
	$download = $data->fetch_array($que);
	$tpl->assign('download', $download);
} 
elseif ($action == "edit")
{
    $sql = $data->select_query("download_cats", "WHERE id = $id");
    $cat = $data->fetch_array($sql);

    $sql = $data->select_query("authteam");
	$numgroups = $data->num_rows($sql);
	$groups = array();
	$groups[] = $data->fetch_array($sql);
	do { } while ($groups[] = $data->fetch_array($sql));
    
    $authtemp = unserialize($cat['upauth']);
    $numauths = count($auths);
    $upauths = array();
    if ($authtemp != "")
    {
        while(list($group, $auth) = each($authtemp) )
        {
            $notfound = true;
            for($i=0;$i<count($groups);$i++)
            {
                if($groups[$i]['teamname'] == $group)
                {
                    $upauths[] = $auth;
                    $notfound = false;
                }
            }
            if ($notfound)
            {
                $auths[] = '1';
            }
        }
    }
    
    $authtemp = unserialize($cat['downauth']);
    $numauths = count($auths);
    $downauths = array();
    if ($authtemp != "")
    {
        while(list($group, $auth) = each($authtemp) )
        {
            $notfound = true;
            for($i=0;$i<count($groups);$i++)
            {
                if($groups[$i]['teamname'] == $group)
                {
                    $downauths[] = $auth;
                    $notfound = false;
                }
            }
            if ($notfound)
            {
                $auths[] = '1';
            }
        }
    }
    $tpl->assign('guest', $authtemp['Guest']);
    $tpl->assign('upauths', $upauths);
    $tpl->assign('downauths', $downauths);
    $tpl->assign('item', $item);
    $tpl->assign('group', $groups);
	$tpl->assign('numgroups', $numgroups);
    $tpl->assign("cat", $cat);
}
elseif ($action == "add")
{
    $sql = $data->select_query("authteam");
	$numgroups = $data->num_rows($sql);
	$groups = array();
	$groups[] = $data->fetch_array($sql);
	while ($groups[] = $data->fetch_array($sql));
    $tpl->assign('group', $groups);
	$tpl->assign('numgroups', $numgroups);
}
else 
{
	$cats = $data->select_query("download_cats", "ORDER BY name ASC");
    $row_cats = array();
	$row_cats[] = $data->fetch_array($cats);
	$num_cats = $data->num_rows($cats);
	while ($row_cats[] = $data->fetch_array($album)); 
	$tpl->assign('cats', $row_cats);
	$tpl->assign('num_cats', $num_cats);
}

$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('id', $id);
$tpl->assign('action', $action);
$filetouse = 'admin_downloads.tpl';
?>