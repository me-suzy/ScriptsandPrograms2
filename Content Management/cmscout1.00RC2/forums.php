<?php
/**************************************************************************
    FILENAME        :   forums.php
    PURPOSE OF FILE :   Manages the forums
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($_GET['action'])) $action = $_GET['action'];
$pagenum = 1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$f = $_GET['f'];
$t = $_GET['t'];
if (!empty($t))
{
    $sql = $data->select_query("forumtopics", "WHERE id = $t");
    $temp = $data->fetch_array($sql);
    $f = $temp['forum'];
}
if (!empty($f))
{
    $sql = $data->select_query("forumauths", "WHERE forum_id=$f");
    $auth = $data->fetch_array($sql);
}
if(!empty($f) || !empty($t))
{
    $access = array('admin_level', 'scouter_level','tl_level','pl_level','second_level');
    
    if ($check['level'] != 5)
    {
        $useraccess = $access[$check['level']];
    }
    
    $usergroup = $check['team'];
    
    $currentauth = unserialize($auth['new_topic']);
    $userauths['new'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
    
    $currentauth = unserialize($auth['reply_topic']);
    $userauths['reply'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
    
    $currentauth = unserialize($auth['edit_post']);
    $userauths['edit'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
    
    $currentauth = unserialize($auth['delete_post']);
    $userauths['delete'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
    
    $currentauth = unserialize($auth['moderate']);
    $userauths['mod'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
    
    $currentauth = unserialize($auth['view_forum']);
    $userauths['view'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;

    $currentauth = unserialize($auth['read_topics']);
    $userauths['read'] = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;

    if ($userauths['view'] == 0 && !empty($f) && $check['level'] != 0) 
    {
        if ($check['uname'] != "Guest")
        {
            show_message_back("You do not have permisions to view this forum");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage");
        }
    }
    elseif ($userauths['read'] == 0 && !empty($t) && $check['level'] != 0)
    {
        if ($check['uname'] != "Guest")
        {
            show_message_back("You do not have permisions to read topics in this forum");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage");
        }
    }
}
switch($action)
{
    case "post": 
        $edit = false;
        $new=false;
        include("forums/post.php");
        break;
    case "edit": 
        $edit=true;
        $new=false;
        include("forums/post.php");
        break;
    case "new": 
        $new = true;
        $edit = false;
        include("forums/post.php");
        break;
    case "topic": include("forums/view_topic.php");
        break;
    case "modf":
        include("forums/mod_forum.php");
        break;
    case "delete":
        if ($userauths['delete'] == 1)
        {
            $pid = $_GET['p'];
            $tid = $_GET['t'];
            if ($_POST['submit'] == "Yes")
            {
                $sql = $data->select_query("forumposts", "WHERE topic=$tid");
                $postinfo = $data->fetch_array($sql);
                if($data->num_rows($sql) <= 1)
                {
                    echo "<script>alert('You can\\'t delete this post as it is the last post in the topic. Please use the moderator panel to delete a topic'); window.location='index.php?page=forums&action=topic&t=$tid';</script>";
                    exit;
                }
                $sql = $data->delete_query("forumposts", "id=$pid", "Forums", "Post Deleted");
                if ($sql)
                {
                    $sql = $data->select_query("forumtopics", "WHERE id=$tid");
                    $topic = $data->fetch_array($sql);
                    
                    $sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted DESC");
                    $latest = $data->fetch_array($sql);
                    $data->update_query("forumtopics", "lastpost='{$latest['userposted']}', lastdate={$latest['dateposted']}", "id=$tid", "", "", false);
                    
                    $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
                    $forum = $data->fetch_array($sql);
                    $data->update_query("forums", "lastpost='{$latest['userposted']}', lastdate={$latest['dateposted']}", "id={$topic['forum']}", "", "", false);
                    
                    echo "<script>alert('Post Deleted'); window.location='index.php?page=forums&action=topic&t=$tid';</script>";
                    exit;
                }
            }
            $pagenum=7;
            $tpl->assign("tid", $tid);
        }
        else
        {
                echo "<script>alert('You don\\'t have the required permisions to delete a post''); window.location='index.php?page=forums&action=topic&t=$tid';</script>";
                exit;
        }
        break;
    case "stopwatching":
        $tid = $_GET['tid'];
        $user = $_GET['u'];
        $data->update_query("forumstopicwatch", "notify=0", "username = '$user' AND topic_id=$tid", "", "", false);
        show_message("You are no longer watching that topic");
    default: include("forums/view_forum.php");
}

$tpl->assign("username", $check['uname']);
$tpl->assign("userauths", $userauths);
$tpl->assign('editFormAction', $editFormAction);  
$dbpage = true;
$pagename = "forums";
?>