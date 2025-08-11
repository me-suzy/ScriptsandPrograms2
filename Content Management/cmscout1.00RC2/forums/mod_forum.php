<?php
/**************************************************************************
    FILENAME        :   mod_forum.php
    PURPOSE OF FILE :   Moderating control panel
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($_GET['f'])) $fid = $_GET['f'];
if (isset($_GET['t'])) $tid = $_GET['t'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$sql = $data->select_query("forums");
$numforums = $data->num_rows($sql);

if(!$tid)
{
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);
    
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid");
    $numtopics = $data->num_rows($sql);
    $topics = array();
    while($topics[] = $data->fetch_array($sql));
    
    $tpl->assign("forum", $forum);
    $tpl->assign("topics", $topics);
    $tpl->assign("numtopics", $numtopics);
}
elseif($tid)
{
    if (isset($_GET['a'])) $act = $_GET['a'];
    if($act == "move")
    {
        $sql = $data->select_query("forumtopics", "WHERE id=$tid");
        $topic = $data->fetch_array($sql);
        
        $sql = $data->select_query("forums", "WHERE id != $fid");
        $numforums = $data->num_rows($sql);
        $forums = array();
        while ($forums[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
    
        $tpl->assign("forums", $forums);
        $tpl->assign("topic", $topic);
        $tpl->assign("forum", $forum);

        if($_POST['submit'] == "Move Topic")
        {
            $forumid = $_POST['forum'];
            $sql = $data->update_query("forumtopics", "forum=$forumid", "id=$tid", "Forums", "Topic moved");
            if ($sql)
            {
                echo "<script> alert('Topic Moved'); window.location = 'index.php?page=forums&action=modf&f=$fid';</script>";
                exit; 
            }
        }
    }
    elseif($act == "del")
    {
        $sql = $data->select_query("forumtopics", "WHERE id=$tid");
        $topic = $data->fetch_array($sql);
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
        $tpl->assign("topic", $topic);
        $tpl->assign("forum", $forum);
        if($_POST['submit'] == "Yes")
        {
            $sql = $data->delete_query("forumtopics", "id=$tid", "Forums", "Topic Deleted");
            if ($sql)
            {
                $sql = $data->select_query("forumtopics", "WHERE forum=$fid ORDER BY lastdate DESC");
                if ($data->num_rows($sql) > 0)
                {
                    $latest = $data->fetch_array($sql);
                }
                else
                {
                    $latest['id'] = 0;
                    $latest['lastpost'] = 0;
                    $latest['lastdate'] = 0;
                }

                $sql = $data->update_query("forums", "lasttopic={$latest['id']}, lastpost='{$latest['lastpost']}', lastdate={$latest['lastdate']}","id=$fid","", "", false);
                echo "<script> alert('Topic Deleted'); window.location = 'index.php?page=forums&action=modf&f=$fid';</script>";
                exit; 
            }
        }
    }
    $tpl->assign("act", $act);
}

$tpl->assign('editFormAction', $editFormAction);  
$tpl->assign("numforums", $numforums);
$pagenum = 6;



?>