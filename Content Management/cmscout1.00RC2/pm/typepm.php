<?php
/**************************************************************************
    FILENAME        :   typepm.php
    PURPOSE OF FILE :   Sends Personal Messages to users
    LAST UPDATED    :   19 November 2005
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

function sendpmmail($username, $pid)
{
    global $config, $check, $data;
    $tempsql = $data->select_query("records", "WHERE uname='$username'");
    $temp = $data->fetch_array($tempsql);
    
    if($temp['allowemail'] && $temp['newpm'])
    {
        $topicpath = $config['siteaddress'] . "index.php?page=pmmain&action=readpm&id=$pid";
        $email = $temp['email'];
        
        $subject = "[{$config['troopname']}] New Personal Message";

        $emess = "Hi $username,

You indicated that you would like to recieve a notification when somebody sends a personal message to you on the {$config['troopname']} website.

This is just to tell you that \"{$check['uname']}\" has just sent you a personal message.
To read the personal message just point your browser to: $topicpath (You will need to login).

Regards
{$config['troopname']} Webmaster
{$config['sitemail']}";

        $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
        
        $mailsuc = @mail($email, $subject, $emess, $headers);
    }
}

$postaction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $postaction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign("postaction", $postaction);

if ($editit == true)
{
    $pid = $_GET['id'];
    $sql = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sql);
    $tpl->assign("editpm", $pm);
    $tpl->assign("editmode", true);
}
elseif ($reply == true)
{
    $pid = $_GET['id'];
    $sqls = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sqls);
    
    $newpm['text'] = htmlentities("<strong>Quoting {$pm['fromuser']}</strong><br />");
    $newpm['text'] .= htmlentities("<em>&quot;{$pm['text']}&quot;</em><br />");
    
    $newpm['subject'] = "Re: {$pm['subject']}";
    
    $newpm['touser'] = $pm['fromuser'];
    
    $tpl->assign("editpm", $newpm);
}
elseif ($sendit == true)
{
    $pid = $_GET['id'];
    $sqls = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sqls);
    $subject = safesql($pm['subject'], "text");
    $message = safesql($pm['text'], "text", false);
    $username = safesql($pm['fromuser'], "text");
    $to = safesql($pm['touser'], "text");
    
    $sql = $data->update_query("pms", "date = $timestamp, type=1, newpm=1, readpm=0", "id=$pid", "", "", false);
    $sql = $data->insert_query("pms", "'', $subject, $message, $timestamp, 2, 1, 0, $username, $to", "", "", false);
    
    if($sql)
    {
        sendpmmail($pm['touser'], $pm['id']);
        show_message("You message has been sent");
        echo("<script>window.location = 'index.php?page=pmmain&action=drafts';</script>");
        exit;
    }
}

if(isset($_GET['user']))
{
    $newpm['touser'] = strip_tags($_GET['user']);
    $tpl->assign("editpm", $newpm);
}

if (($_POST['submit'] == "Send PM" || $_POST['submit'] == "Save PM") && $editit==true && isset($_GET['id']))
{
    $pid = $_GET['id'];
    $data->delete_query("pms", "id=$pid", "", "", false);
}

if ($_POST['submit'] == "Send PM")
{
    if ($_POST['subject'] == '')
    {
        error_message("You need to enter a subject for the personal message");
        exit;
    }
    if ($_POST['story'] == '')
    {
        error_message("You need to enter a personal message");
        exit;
    }
    if ($_POST['touser'] == '')
    {
        error_message("You need to specify who you want to send the message to");
        exit;
    }
    $tousers = explode(',', strip_tags($_POST['touser']));
    $subject = safesql($_POST['subject'], "text");
    $pm = safesql($_POST['story'], "text", false);
    $username = safesql($check['uname'], "text");
    $okusers = array();
    $notokusers = array();
    for($i=0;$i<count($tousers);$i++)
    {
        $message = "";
        $to = safesql(trim($tousers[$i]), "text");
        $sql = $data->select_query("authuser", "WHERE uname = $to");
        if ($data->num_rows($sql) > 0 && $tousers[$i] != $check['uname'])
        {
            $sql = $data->insert_query("pms", "'', $subject, $pm, $timestamp, 1, 0, 1, $username, $to", "", "", false);
            $sql2 = $data->insert_query("pms", "'', $subject, $pm, $timestamp, 2, 1, 0, $username, $to", "", "", false);
            if ($sql)
            {
                $sql = $data->select_query("pms", "WHERE subject=$subject AND text=$pm AND touser=$to AND fromuser=$username");
                $pm = $data->fetch_array($sql);
                sendpmmail($tousers[$i], $pm['id']);
                $okusers[] = $tousers[$i];
            }
            else
            {
                $notokusers[] = $tousers[$i];
            }
        }
        elseif ($tousers[$i] == $check['uname'])
        {
            $message .= "You can\\'t send a message to yourself";
        }
        else
        {
            $notokusers[] = $tousers[$i];
        }
    }

    if (count($okusers) == 1)
    {
        $userlist = implode(', ', $okusers);
        $message .= "You message has been sent to the following user: $userlist. ";
    }
    elseif (count($okusers) > 1)
    {
        $userlist = implode(', ', $okusers);
        $message .= "You message has been sent to the following users: $userlist. ";
    }
    
    if (count($notokusers) == 1)
    {
        $userlist = implode(', ', $notokusers);
        $message .= "The following user does not exist: $userlist. ";
    }
    elseif (count($notokusers) > 1)
    {
        $userlist = implode(', ', $notokusers);
        $message .= "The following users do not exist: $userlist. ";
    }
    
    show_message($message);
    echo("<script>window.location = 'index.php?page=pmmain';</script>");
    exit;
}
elseif ($_POST['submit'] == "Save PM")
{
    if ($_POST['subject'] == '')
    {
        error_message("You need to enter a subject for the personal message");
        exit;
    }
    if ($_POST['story'] == '')
    {
        error_message("You need to enter a personal message");
        exit;
    }
    if ($_POST['touser'] == '')
    {
        error_message("You need to specify who you want to send the message to");
        exit;
    }
    $tousers = explode(',', strip_tags($_POST['touser']));
    $subject = safesql($_POST['subject'], "text");
    $pm = safesql($_POST['story'], "text", false);
    $username = safesql($check['uname'], "text");
    $okusers = array();
    $notokusers = array();
    for($i=0;$i<count($tousers);$i++)
    {
        $message = "";
        $to = safesql(trim($tousers[$i]), "text");
        $sql = $data->select_query("authuser", "WHERE uname = $to");
        if ($data->num_rows($sql) > 0 && $tousers[$i] != $check['uname'])
        {
            $sql = $data->insert_query("pms", "'', $subject, $pm, $timestamp, 4, 0, 1, $username, $to", "", "", false);
            if ($sql)
            {
                $okusers[] = $tousers[$i];
            }
            else
            {
                $notokusers[] = $tousers[$i];
            }
        }        
        elseif ($tousers[$i] == $check['uname'])
        {
            $message .= "You can\\'t send a message to yourself";
        }
        else
        {
            $notokusers[] = $tousers[$i];
        }
    }

    if (count($okusers) > 0)
    {
        $message .= "You message has been saved in your drafts folder. ";
    }
    
    if (count($notokusers) == 1)
    {
        $userlist = implode(', ', $notokusers);
        $message .= "The following user does not exist: $userlist. ";
    }
    elseif (count($notokusers) > 1)
    {
        $userlist = implode(', ', $notokusers);
        $message .= "The following users do not exist: $userlist. ";
    }
    
    show_message($message);
    echo("<script>window.location = 'index.php?page=pmmain&action=drafts';</script>");
    exit;
}
$tpl->assign("isedit", "simp");

$tpl->assign("pm", $inboxpm);
$tpl->assign("numpm", $numpm);
$tpl->assign("onpage", "New Personal Message");
$pagenum = 3;
?>