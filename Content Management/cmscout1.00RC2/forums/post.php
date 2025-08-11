<?php
/**************************************************************************
    FILENAME        :   post.php
    PURPOSE OF FILE :   Manages posting new posts and editing of posts
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

if (isset($_GET['t'])) $tid = $_GET['t'];
if (isset($_GET['p'])) $pid = $_GET['p'];
if (isset($_GET['f'])) $fid = $_GET['f'];

$postaction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $postaction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign("postaction", $postaction);

if(!$edit && !$new && $userauths['reply'])
{
    if ($_POST['submit'] == "Submit")
    {
        $sql = $data->select_query("forumtopics", "WHERE id=$tid");
        $topic = $data->fetch_array($sql);
        $fid = $topic['forum'];
        $subject = safesql($_POST['subject'], "text");
        $post = safesql($_POST['story'], "text", false);
        
        if(empty($_POST['story']))
        {
            error_message("You post can't be empty");
        }
        $notifyonce = $_POST['next'];
        $notifylots = $_POST['always'];
        
        if ($notifyonce == 1)
        {
            $notify = 1;
        }
        if ($notifylots == 1)
        {
            $notify = 2;
        }

        $username = safesql($check['uname'], "text");

        $sql = $data->insert_query("forumposts", "'', $subject, $post, $username, $timestamp, $tid, 0, 0", "", "", false);
        if ($sql)
        {
            $sqls = $data->select_query("forumstopicwatch", "WHERE topic_id=$tid AND username!=$username AND (notify=1 OR notify=2)");
            $topicpath = $config['siteaddress']. "index.php?page=forums&action=topic&t=$tid";
            while($topicwatch = $data->fetch_array($sqls))
            {
                $tempsql = $data->select_query("records", "WHERE uname='{$topicwatch['username']}'");
                $temp = $data->fetch_array($tempsql);
                
                if($temp['allowemail'])
                {
                    $email = $temp['email'];
                    
                    $story = strip_tags($_POST['story']);
                    $subject = "[{$config['troopname']}] Topic Reply Notification";
                    
                    $emess = "Hi, {$topicwatch['username']}
    
You indicated that you would like to recieve a notification when somebody posts in the \"{$topic['subject']}\" topic on the {$config['troopname']} forum.

This is just to tell you that \"{$check['uname']}\" has just posted a reply to the topic.
To view the topic just point your browser to: $topicpath (You may need to login).

------------------------------------------------------------------
The reply is as follows:
{$story}

------------------------------------------------------------------";
                    if($topicwatch['notify'] == 1 )
                    {
                        $emess .= "
You will not be notified again of a reply on this topic until you post in it again it.
                        ";
                        $data->update_query("forumstopicwatch", "notify=0", "topic_id=$tid AND username='{$topicwatch['username']}'", "", "", false);
                    }
                    elseif($topicwatch['notify']==2)
                    {
                                        $emess .= "
To stop watching this topic please point your browser to: {$config['siteaddress']}index.php?page=forums&action=stopwatching&u={$topicwatch['username']}&tid=$tid
                        ";
                    }
                    $emess .= "Regards
{$config['troopname']} Webmaster
{$config['sitemail']}";
                
    
                    $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
                    
                    $mailsuc = @mail($email, $subject, $emess, $headers);
                }
            }
            
            if ($notify == 1 || $notify == 2)
            {
                $sql=$data->select_query("forumstopicwatch", "WHERE topic_id=$tid AND username=$username");
                if($data->num_rows($sql) == 0)
                {
                    $data->insert_query("forumstopicwatch", "$tid, $username, $notify", "", "", false);
                }
                else
                {
                    $data->update_query("forumstopicwatch", "notify = $notify", "topic_id=$tid AND username=$username", "", "", false);
                }
            }
            $data->update_query("forums", "lasttopic=$tid, lastpost='{$check['uname']}', lastdate=$timestamp", "id=$fid", "", "", false);
            $data->update_query("forumtopics", "lastpost='{$check['uname']}', lastdate=$timestamp", "id=$tid", "", "", false);
            

            $data->delete_query("forumnew", "topic=$tid", "", "", false);
            $sql = $data->select_query("authuser");
            while($temp = $data->fetch_array($sql))
            {
                $uname = safesql($temp['uname'], "text");
                $data->insert_query("forumnew", "'', $uname, $tid, $fid", "", "", false);
            }

            
            echo("<script> window.location='index.php?page=forums&action=topic&t=$tid'</script>");
        }
    }
    else
    {    

        $username = safesql($check['uname'], "text");
        $sql=$data->select_query("forumstopicwatch", "WHERE topic_id=$tid AND username=$username");
        $watch = $data->fetch_array($sql);
        
        $sql = $data->select_query("forumtopics", "WHERE id=$tid");
        $topic = $data->fetch_array($sql);
        
        $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
        $forum = $data->fetch_array($sql);
        
        $sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted DESC");
        $numposts = $data->num_rows($sql);
        $posts = array();
        while($posts[] = $data->fetch_array($sql));
        
        $tpl->assign("watch", $watch);
        $tpl->assign("posts", $posts);
        $tpl->assign("topic", $topic);
        $tpl->assign("numposts", $numposts);
        $tpl->assign("forum", $forum);
    }
}
elseif ($new && !$edit && $userauths['new'])
{    
    if ($_POST['submit'] == "Submit")
    {
        if ($_POST['subject'] == '')
        {
            error_message("You need to enter a subject for the post");
            exit;
        }        
        if ($_POST['story'] == '')
        {
            error_message("You need to enter a post");
            exit;
        }
        
        $subject = safesql($_POST['subject'], "text");
        $desc = safesql($_POST['desc'], "text");
        $post = safesql($_POST['story'], "text", false);
        
        $sql = $data->insert_query("forumtopics", "'', $subject, $desc ,0 , '{$check['uname']}', $timestamp,'{$check['uname']}', $timestamp, $fid", "", "", false);
        if ($sql)
        {
            $sql = $data->select_query("forumtopics", "WHERE subject=$subject AND numviews=0");
            $topic = $data->fetch_array($sql);
            
            $sql = $data->insert_query("forumposts", "'', $subject, $post, '{$check['uname']}', $timestamp, {$topic['id']}, 0, 0", "", "", false);
            if ($sql)
            {
                $sqls = $data->select_query("records", "WHERE newtopic=1 AND allowemail=1");
                $topicpath = $config['siteaddress']. "index.php?page=forums&action=topic&t={$topic['id']}";
                while($topicwatch = $data->fetch_array($sqls))
                {
                    $sql2 = $data->select_query("authuser", "WHERE uname='{$topicwatch['uname']}'");
                    $tempstuff = $data->fetch_array($sql2);
                    $usergroup = $tempstuff['team'];
                    
                    $sql = $data->select_query("forumauths", "WHERE forum_id=$fid");
                    $auth = $data->fetch_array($sql);
                    
                    $currentauth = unserialize($auth['read_topics']);
                    $userauth = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
        
                    if ($userauth == 1 && $check['uname'] != $topicwatch['uname'])
                    {
                        $email = $topicwatch['email'];
                        
                        $subject = "[{$config['troopname']}] New Topic Notification";
                        
                        $story = strip_tags($_POST['story']);

                        $emess = "Hi, {$topicwatch['uname']}
    
You indicated that you would like to recieve a notification when somebody posts a new topic on the {$config['troopname']} forum.
    
This is just to tell you that {$check['uname']} has just posted a new topic.
To view the topic just point your browser to: $topicpath (You may need to login).
    
------------------------------------------------------------------
The post is as follows:
{$story}
    
------------------------------------------------------------------

Regards
{$config['troopnmame']} Webmaster
{$config['sitemail']}";

                        $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
                        
                        $mailsuc = @mail($email, $subject, $emess, $headers);
                    }
                }
                
                $data->update_query("forums", "lasttopic={$topic['id']}, lastpost='{$check['uname']}', lastdate=$timestamp", "id=$fid", "", "", false);             

                $sql = $data->select_query("authuser");
                while($temp = $data->fetch_array($sql))
                {
                    if ($temp['uname'] != $check['uname'])
                    {
                        $uname = safesql($temp['uname'], "text");
                        $data->insert_query("forumnew", "'', $uname, {$topic['id']}, $fid", "", "", false);
                    }
                }

                echo("<script> window.location='index.php?page=forums&action=topic&t={$topic['id']}'</script>");
            }
        }
    }
    else
    {   
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
        
        $tpl->assign("forum", $forum);
        $tpl->assign("new", $new);
    }
}
elseif (!$new && $edit && $userauths['edit'])
{    
    if ($_POST['submit'] == "Submit")
    {       
        if ($_POST['story'] == '')
        {
            error_message("You need to enter a post");
            exit;
        }
        $subject = safesql($_POST['subject'], "text");
        $post = safesql($_POST['story'], "text", false);

        $sql = $data->update_query("forumposts", "subject = $subject, posttext = $post, edittime=$timestamp, edituser='{$check['uname']}'", "id=$pid", "", "", false);
        if ($sql)
        {
            $sql = $data->select_query("forumposts", "WHERE id=$pid");
            $post = $data->fetch_array($sql);
            header("Location: index.php?page=forums&action=topic&t={$post['topic']}");
        }
    }
    else
    {  
        $sql = $data->select_query("forumposts", "WHERE id=$pid");
        $post = $data->fetch_array($sql);
            
        $sql = $data->select_query("forumtopics", "WHERE id={$post['topic']}");
        $topic = $data->fetch_array($sql);
            
        $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
        $forum = $data->fetch_array($sql);
        
        $tpl->assign("post", $post);
        $tpl->assign("topic", $topic);
        $tpl->assign("forum", $forum);
        $tpl->assign("edit", $edit);
    }
}
else
{
    error_message("You don't have the required permisions do to that");
}

$tpl->assign("isedit", "simp");
$tpl->assign("isforumpost", true);
$pagenum = 4;
?>