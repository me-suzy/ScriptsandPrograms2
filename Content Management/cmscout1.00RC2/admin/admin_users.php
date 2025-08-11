<?php
/**************************************************************************
    FILENAME        :   admin_users.php
    PURPOSE OF FILE :   Displays users
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
	$module['User Management']['Users'] = "users";
    $permision['Users'] = 4;
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

if(isset($_GET['action'])) $action=$_GET['action']; else $action = "";
if(isset($_POST['Submit'])) $submit = $_POST['Submit']; else $submit = "";
$where = '';
$sort = 'uname';
$order  = "ASC";

if ($submit == 'Go') 
{
	$field = $_POST['field'];
    
	$comp = $_POST['comp'];
    
	$cond = safesql($_POST['cond'], "text");
    if ($cond == "NULL") $field = "none";
    
    $sort = $_POST['sort'];
    
    $order = $_POST['order'];
    
	if ($field != 'none') 
    {
		$where = " $field $comp $cond ";
	}
}

if ($action == "email")
{
    $id = $_GET['id'];
    $sql = $data->select_query("authuser", "WHERE id = '$id'");
    $row = $data->fetch_array($sql);
    $tpl->assign("username", $row['uname']);
    if($_POST['submit'] == "Send Email")
    {
        $sql = $data->select_query("records", "WHERE uname='{$row['uname']}'");
        $row2 = $data->fetch_array($sql);        
        /* subject */
        $subject = "[{$config['troopname']}] {$_POST['subject']}";
        
        /* message */
        $emess = "Hi, {$row["uname"]}
        
A email message has been sent to you via the {$config['troopname']} website. 
The author of the email message was {$check['uname']}
If this email message contains spam, or offensive material please contact the webmaster ({$config['sitemail']})

The email is as follows
---------------------------------------------------------------------
{$_POST['email']}";
        
           
            /* additional headers */
            $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
            $headers .= "Bcc: {$config['sitemail']}\r\n";
            /* and now mail it */
            $mailsuc = mail($row2["email"], $subject, $emess, $headers);
            if (!$mailsuc) error_message("Mail Error", "Error Sending Mail. Please contact the webmaster"); 
            else
            {
                echo "<script> alert('Email sent'); window.location = '$pagename';</script>\n";
                exit;   
            }
    }
}
elseif ($action == "emailall")
{
    $username = "All Users";
    $tpl->assign("username", $username);
    if($_POST['submit'] == "Send Email")
    {
        if ($level != 2 && $level != 1 && $level != 0) 
        {
         $patrol = $check['team'];
         $result = $data->select_query("authuser", "WHERE team='$patrol' AND uname != '{$check['uname']}'");
        } 
        else 
        {
            $result = $data->select_query("authuser", "WHERE uname != '{$check['uname']}'");
        }

        $row = $data->fetch_array($result);
        $sql = $data->select_query("records", "WHERE uname = '{$row['uname']}'");
        $row2 = $data->fetch_array($sql);
        $emailadds = $row2['email'];
        while($row = $data->fetch_array($result))
        {
            $sql = $data->select_query("records", "WHERE uname = '{$row['uname']}'");
            //echo $row['uname'] . "<br>";
            $row2 = $data->fetch_array($sql);
            $emailadds .= ', ' .$row2['email'];
        }

        /* subject */
        $subject = "[{$config['troopname']}] {$_POST['subject']}";
        
        /* message */
        $emess = "Hi,
        
A email message has been sent to you via the {$config['troopname']} website. 
The author of the email message was {$check['uname']}
If this email message contains spam, or offensive material please contact the webmaster ({$config['sitemail']})

The email is as follows
---------------------------------------------------------------------
{$_POST['email']}";
        
           
            /* additional headers */
            $headers = "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
            $headers .= "Bcc: $emailadds\r\n";
            /* and now mail it */
            $mailsuc = mail($config['sitemail'], $subject, $emess, $headers);

            if (!$mailsuc) error_message("Mail Error", "There was one or more errors while sending out the emails."); 
            else
            {
                echo "<script> alert('Emails sent'); window.location = '$pagename';</script>\n";
                exit;   
            }
    }
}
elseif ($action == "delete") 
{
    $id = $_GET['id'];
    $sql = $data->select_query("authuser", "WHERE id = $id");
    $temp = $data->fetch_array($sql);
    $username = $temp['uname'];
    $sql1 = $data->delete_query("records", "uname='$username'", "Delete User", "$username deleted");
    $sql2 = $data->delete_query("authuser", "id='$id'", "", "", false);
    $data->delete_query("scoutrecord", "userid='$id'", "", "", false);
    $data->delete_query("badges", "userid='$id'", "", "", false);
    if ($sql1 && $sql2)
    {
        echo "<script> alert('$username deleted'); window.location = '$pagename';</script>\n";
        exit; 
    }
    $action = "";
}
$row = array();
$record = array();
if ($level != 2 && $level != 1 && $level != 0) {
 $patrol = $check['team'];
 if ($where != '') {
    $result = $data->select_query("authuser", "WHERE team='$patrol' AND $where ORDER BY $sort $order");
 } else {
    $result = $data->select_query("authuser", "WHERE team='$patrol' ORDER BY $sort $order");
 }
} else {
 if ($where != '') {
    $result = $data->select_query("authuser", "WHERE $where ORDER BY $sort $order");
 } else {
    $result = $data->select_query("authuser", "ORDER BY $sort $order");
 }
}
$numusers = $data->num_rows($result);

while ($row[] = $data->fetch_array($result));

$filetouse = "admin_users.tpl";
$tpl->assign("action", $action);
$tpl->assign('numusers', $numusers);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('row', $row);
$tpl->assign('record', $record);
$tpl->assign("uname", $check['uname']);
$tpl->assign("level", $check['level']);
?>	