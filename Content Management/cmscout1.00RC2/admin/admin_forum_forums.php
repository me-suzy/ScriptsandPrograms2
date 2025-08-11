<?php
/**************************************************************************
    FILENAME        :   admin_patrol.php
    PURPOSE OF FILE :   Displays patrols and gives access to patrol content manager and menu manager
    LAST UPDATED    :   27 September 2005
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
//error_reporting(E_ALL);
if( !empty($getmodules) )
{
	$module['Forum Management']['Forums'] = "forum_forums";
    $permision['Forums'] = 1;
	return;
}

if ($level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}
$action = $_GET['action'];
$cid = $_GET['cid'];
$fid = $_GET['fid'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($action == "")
{
    $sql = $data->select_query("forumscats", "ORDER BY pos ASC");
    $numcats = $data->num_rows($sql);
    $cats = array();
    while($cats[] = $data->fetch_array($sql));
    
    $tpl->assign("numcats", $numcats);
    $tpl->assign("cats", $cats);
}
elseif($action=="view")
{
    $sql = $data->select_query("forumscats", "WHERE id=$cid");
    $catinfo = $data->fetch_array($sql);

    $sql = $data->select_query("forums", "WHERE cat=$cid ORDER BY pos ASC");
    $numforums = $data->num_rows($sql);
    $forums = array();
    while($temp = $data->fetch_array($sql))
    {
        $i = 0;
        $j = 0;
        $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp["id"]}");
        $temp["numtopics"] = $data->num_rows($sql2);
        while($temp2 = $data->fetch_array($sql2))
        {
            $i += $temp2["numviews"];
            $j += $temp2["numposts"];
        }
        $temp["numviews"] = $i;
        $temp["numposts"] = $j;
        $forums[] = $temp;
    }
    
    $tpl->assign("numforums", $numforums);
    $tpl->assign("forums", $forums);
    $tpl->assign("catinfo", $catinfo);
}
elseif($action == "moveup")
{
    $sql = $data->select_query("forumscats", "WHERE id=$cid");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 -1;
    if($tempos <= 0) $tempos=1;
    $sql = $data->select_query("forumscats", "WHERE pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("forumscats", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("forumscats", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=forum_forums");
}
elseif($action == "movedown")
{
    $sql = $data->select_query("forumscats", "WHERE id=$cid");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 + 1;
    $sql = $data->select_query("forumscats", "WHERE pos='$temppos'");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("forumscats", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("forumscats", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=forum_forums");
}
elseif($action == "movefup")
{
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 -1;
    if($tempos <= 0) $tempos=1;
    $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("forums", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("forums", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=forum_forums&action=view&cid=$cid");
}
elseif($action == "movefdown")
{
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $row = $data->fetch_array($sql);
    
    $pos1 = $row['pos'];
    $temppos = $pos1 + 1;
    $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid");
    $row2 = $data->fetch_array($sql);
    
    $pos2= $row2['pos'];
    $data->update_query("forums", "pos=$pos2", "id={$row['id']}", "", "", false);
    $data->update_query("forums", "pos=$pos1", "id={$row2['id']}", "", "", false);
    
    $server = $_SERVER['PHP_SELF'];
    header("Location: $server"."?page=forum_forums&action=view&cid=$cid");
}
elseif($action == "add")
{
    if ($_POST['Submit'] == "Submit")
    {
        if ($_POST['catname'] == '')
        {
            error_message("You need to supply a name for the category");
            exit;
        }
        $catname = safesql($_POST['catname'], "text");
    
		$pos = 1;
		do 
		{
			$temp = $data->select_query("forumscats", "WHERE pos = '$pos'");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 	
        
        $sql = $data->insert_query("forumscats", "'', $catname, '$pos'", "Forums", "Category added");
        
        if ($sql)
        {
            echo "<script> alert('Category Added'); window.location = '$pagename';</script>";
            exit; 
        }
    }
}
elseif($action == "edit")
{
    $sql = $data->select_query("forumscats", "WHERE id=$cid");
    $cat = $data->fetch_array($sql);
    $tpl->assign("cat", $cat);
    
    if ($_POST['Submit'] == "Submit")
    {
        if ($_POST['catname'] == '')
        {
            error_message("You need to supply a name for the category");
            exit;
        }
        $catname = safesql($_POST['catname'], "text");
    
        
        $sql = $data->update_query("forumscats", "name=$catname", "id=$cid", "Forums", "Category edited");
        
        if ($sql)
        {
            echo "<script> alert('Category Updated'); window.location = '$pagename';</script>";
            exit; 
        }
    }
}
elseif($action == "addforum")
{
    $sql = $data->select_query("authteam");
	$numgroups = $data->num_rows($sql);
	$groups = array();
	while ($groups[] = $data->fetch_array($sql));
    $tpl->assign('groups', $groups);
	$tpl->assign('numgroups', $numgroups);
    
    if ($_POST['Submit'] == "Submit")
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the forum");
            exit;
        }
        $forumname = safesql($_POST['name'], "text");
        $desc = safesql($_POST['desc'], "text");
        
        $newtopic = array();
        $reply = array();
        $editpost = array();
        $deletepost = array();
        $mod = array();
        $view = array();
        $read = array();
        
        $groupid = "Guest";
        $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
        $newtopic[$groupid] = $value;
        
        $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
        $reply[$groupid] = $value;
        
        $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
        $editpost[$groupid] = $value;
        
        $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
        $deletepost[$groupid] = $value;
        
        $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
        $mod[$groupid] = $value;
        
        $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
        $view[$groupid] = $value;
        
        $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
        $read[$groupid] = $value;
        
        
        for($i=0;$i<$numgroups;$i++)
        {
            $groupid = $groups[$i]['id'];
            $groupname = $groups[$i]['teamname'];
            $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
            $newtopic[$groupname] = $value;
            
            $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
            $reply[$groupname] = $value;
            
            $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
            $editpost[$groupname] = $value;
            
            $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
            $deletepost[$groupname] = $value;
            
            $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
            $mod[$groupname] = $value;
            
            $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
            $view[$groupname] = $value;
            
            $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
            $read[$groupname] = $value;
        }
        
        $access = array('admin_level', 'scouter_level','tl_level','pl_level','second_level');
        for($i=0;$i<5;$i++)
        {
            $groupid = $access[$i];
            $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
            $newtopic[$groupid] = $value;
            
            $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
            $reply[$groupid] = $value;
            
            $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
            $editpost[$groupid] = $value;
            
            $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
            $deletepost[$groupid] = $value;
            
            $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
            $mod[$groupid] = $value;
            
            $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
            $view[$groupid] = $value;
            
            $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
            $read[$groupid] = $value;
        }
        
        $newtopic = safesql(@serialize($newtopic), "text");
        $reply = safesql(@serialize($reply), "text");
        $editpost = safesql(@serialize($editpost), "text");
        $deletepost = safesql(@serialize($deletepost), "text");
        $mod = safesql(@serialize($mod), "text");
        $view = safesql(@serialize($view), "text");
        $read = safesql(@serialize($read), "text");

		$pos = 1;
		do 
		{
			$temp = $data->select_query("forums", "WHERE pos = '$pos' AND cat=$cid");
			if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
		} while ($data->num_rows($temp) != 0); 		

        $sql = $data->insert_query("forums", "'', $forumname, $desc, '', '', '', $cid, $pos", "Forums", "Added $forumname");
        if($sql)
        {
            $sql = $data->select_query("forums", "WHERE name=$forumname AND cat=$cid");
            $forum = $data->fetch_array($sql);
            $sql = $data->insert_query("forumauths", "{$forum['id']}, $newtopic, $reply, $editpost, $deletepost, $mod, $view, $read", "", "", false);
            if ($sql)
            {
                echo "<script> alert('Forum Added'); window.location = '$pagename&action=view&cid=$cid';</script>";
                exit; 
            }
        }
    }
}
elseif($action == "editforum")
{
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);
    
    $sqls = $data->select_query("authteam");
	$numgroups = $data->num_rows($sqls);
	$groups = array();

    $sql = $data->select_query("forumauths", "WHERE forum_id=$fid");
    $auth = $data->fetch_array($sql);
    
    $auths['new'] = unserialize($auth['new_topic']);
    
    $auths['reply'] = unserialize($auth['reply_topic']);
    
    $auths['edit'] = unserialize($auth['edit_post']);
    
    $auths['delete'] = unserialize($auth['delete_post']);
    
    $auths['mod'] = unserialize($auth['moderate']);
    
    $auths['view'] = unserialize($auth['view_forum']);
    
    $auths['read'] = unserialize($auth['read_topics']);
    
    $guest['new'] = $auths['new']['Guest'];
    $guest['reply'] = $auths['reply']['Guest'];
    $guest['edit'] = $auths['edit']['Guest'];
    $guest['delete'] = $auths['delete']['Guest'];
    $guest['mod'] = $auths['mod']['Guest'];
    $guest['view'] = $auths['view']['Guest'];
    $guest['read'] = $auths['read']['Guest'];

    $realauth = array();
    $access = array('admin_level', 'scouter_level','tl_level','pl_level','second_level');
    for($i=0;$i<5;$i++)
    {
        $temp = $access[$i];
        $tempauth['new'] = $auths['new'][$temp];
        $tempauth['reply'] = $auths['reply'][$temp];
        $tempauth['edit'] = $auths['edit'][$temp];
        $tempauth['delete'] = $auths['delete'][$temp];
        $tempauth['mod'] = $auths['mod'][$temp];
        $tempauth['view'] = $auths['view'][$temp];
        $tempauth['read'] = $auths['read'][$temp];
        $realauth[$temp] = $tempauth;
    };
    
    while ($temp = $data->fetch_array($sqls))
    {
        $tempauth['new'] = $auths['new'][$temp['teamname']];
        $tempauth['reply'] = $auths['reply'][$temp['teamname']];
        $tempauth['edit'] = $auths['edit'][$temp['teamname']];
        $tempauth['delete'] = $auths['delete'][$temp['teamname']];
        $tempauth['mod'] = $auths['mod'][$temp['teamname']];
        $tempauth['view'] = $auths['view'][$temp['teamname']];
        $tempauth['read'] = $auths['read'][$temp['teamname']];
        
        $realauth[] = $tempauth;
        $groups[] = $temp;
    }

    if ($_POST['Submit'] == "Submit")
    {
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name for the forum");
            exit;
        }
        $forumname = safesql($_POST['name'], "text");
        $desc = safesql($_POST['desc'], "text");
        
        $newtopic = array();
        $reply = array();
        $editpost = array();
        $deletepost = array();
        $mod = array();
        $view = array();
        $read = array();
        
        $groupid = "Guest";
        $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
        $newtopic[$groupid] = $value;
        
        $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
        $reply[$groupid] = $value;
        
        $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
        $editpost[$groupid] = $value;
        
        $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
        $deletepost[$groupid] = $value;
        
        $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
        $mod[$groupid] = $value;
        
        $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
        $view[$groupid] = $value;
        
        $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
        $read[$groupid] = $value;
        
        for($i=0;$i<$numgroups;$i++)
        {
            $groupid = $groups[$i]['id'];
            $groupname = $groups[$i]['teamname'];
            
            $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
            $newtopic[$groupname] = $value;
            
            $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
            $reply[$groupname] = $value;
            
            $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
            $editpost[$groupname] = $value;
            
            $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
            $deletepost[$groupname] = $value;
            
            $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
            $mod[$groupname] = $value;
            
            $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
            $view[$groupname] = $value;
            
            $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
            $read[$groupname] = $value;
        }
        
        for($i=0;$i<5;$i++)
        {
            $groupid = $access[$i];
            $value = $_POST["newtopic_$groupid"] == 1 ? 1 : 0;
            $newtopic[$groupid] = $value;
            
            $value = $_POST["reply_$groupid"] == 1 ? 1 : 0;
            $reply[$groupid] = $value;
            
            $value = $_POST["edit_$groupid"] == 1 ? 1 : 0;
            $editpost[$groupid] = $value;
            
            $value = $_POST["delete_$groupid"] == 1 ? 1 : 0;
            $deletepost[$groupid] = $value;
            
            $value = $_POST["moderate_$groupid"] == 1 ? 1 : 0;
            $mod[$groupid] = $value;
            
            $value = $_POST["view_$groupid"] == 1 ? 1 : 0;
            $view[$groupid] = $value;
            
            $value = $_POST["read_$groupid"] == 1 ? 1 : 0;
            $read[$groupid] = $value;
        }
        
        $newtopic = safesql(@serialize($newtopic), "text");
        $reply = safesql(@serialize($reply), "text");
        $editpost = safesql(@serialize($editpost), "text");
        $deletepost = safesql(@serialize($deletepost), "text");
        $mod = safesql(@serialize($mod), "text");
        $view = safesql(@serialize($view), "text");
        $read = safesql(@serialize($read), "text");
	

        $sql = $data->update_query("forums", "`name`=$forumname, `desc`=$desc ", "id=$fid", "Forums", "Edited $forumname");
        if($sql)
        {
            $sql = $data->update_query("forumauths", "new_topic = $newtopic, reply_topic = $reply, edit_post = $editpost, delete_post = $deletepost, moderate = $mod, view_forum = $view, read_topics = $read", "forum_id=$fid", "", "", false);
            if ($sql)
            {
                echo "<script> alert('Forum Changed'); window.location = '$pagename&action=view&cid=$cid';</script>";
                exit; 
            }
        }
    }

    $tpl->assign("guest", $guest);
    $tpl->assign("auths", $realauth);
    $tpl->assign("forum", $forum);
    $tpl->assign('groups', $groups);
	$tpl->assign('numgroups', $numgroups);
}
elseif ($action == "delete")
{
    $sql = $data->select_query("forums", "WHERE cat=$cid");
    $numforums = $data->num_rows($sql);
    
    $sql = $data->select_query("forumscats");
    $numcats = $data->num_rows($sql);
    $cats = array();
    while ($cats[] = $data->fetch_array($sql));
    
    $sql = $data->select_query("forumscats", "WHERE id=$cid");
    $cat = $data->fetch_array($sql);

    $tpl->assign("cats", $cats);
    $tpl->assign("numcats", $numcats);
    $tpl->assign("numforums", $numforums);
    $tpl->assign("cat", $cat);
    
    if ($_POST['submit'] == "Delete")
    {
        $where = $_POST['cats'];
        if($where == "del" || empty($where))
        {
            $sql = $data->select_query("forums", "WHERE cat=$cid");
            while($temp=$data->fetch_array($sql))
            {
                $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp['id']}");
                while($temp2 = $data->fetch_array($sql2))
                {
                    $data->delete_query("forumposts", "topic={$temp2['id']}", "", "", false);
                }
                $data->delete_query("forumtopics", "forum={$temp['id']}", "", "", false);
            }
            $data->delete_query("forums", "cat=$cid", "", "", false);
            $sql = $data->delete_query("forumscats", "id=$cid", "Forums", "Category Deleted");
            if ($sql)
            {
                echo "<script> alert('Category Deleted'); window.location = '$pagename';</script>";
                exit; 
            }
        }
        else
        {
            $forumid = safesql($where, "int");
            $data->update_query("forums", "cat=$forumid", "cat=$cid", "", "", false);
            $sql = $data->delete_query("forumscats", "id=$cid", "Forums", "Category Deleted");
            if ($sql)
            {
                echo "<script> alert('Category Deleted'); window.location = '$pagename';</script>";
                exit; 
            }
        }
    }
}
elseif ($action == "deleteforum")
{
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid");
    $numtopics = $data->num_rows($sql);
    
    $sql = $data->select_query("forums");
    $numforums = $data->num_rows($sql);
    $forums = array();
    while ($forums[] = $data->fetch_array($sql));
    
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);

    $tpl->assign("forums", $forums);
    $tpl->assign("numforums", $numforums);
    $tpl->assign("numtopics", $numtopics);
    $tpl->assign("forum", $forum);
    
    if ($_POST['submit'] == "Delete")
    {
        $where = $_POST['forum'];
        if($where == "del" || empty($where))
        {
            $sql2 = $data->select_query("forumtopics", "WHERE forum={$forum['id']}");
            while($temp2 = $data->fetch_array($sql2))
            {
                $data->delete_query("forumposts", "topic={$temp2['id']}", "", "", false);
            }
            $data->delete_query("forumtopics", "forum={$forum['id']}", "", "", false);
                
            $sql = $data->delete_query("forums", "id=$fid", "Forums", "Forum Deleted");
            if ($sql)
            {
                echo "<script> alert('Forum Deleted'); window.location = '$pagename&action=view&cid={$forum['cat']}';</script>";
                exit; 
            }
        }
        else
        {
            $forumid = safesql($where, "int");
            $data->update_query("forumtopics", "forum=$forumid", "forum=$fid", "", "", false);
            $sql = $data->delete_query("forums", "id=$fid", "Forums", "Forum Deleted");
            if ($sql)
            {
                echo "<script> alert('Forum Deleted'); window.location = '$pagename&action=view&cid={$forum['cat']}';</script>";
                exit; 
            }
        }
    }
}

$tpl->assign('editFormAction', $editFormAction);   
$tpl->assign('action', $action);
$filetouse = "admin_forum_forums.tpl";
?>