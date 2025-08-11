<?php
/**************************************************************************
    FILENAME        :   admin_user_edit.php
    PURPOSE OF FILE :   Edits users profiles
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
	return;
}

if ($level != 2 && $level != 1 && $level != 0) 
{
 error_message("Sorry, you can't access this section");
}
$ulevel = $level;
$id = (isset($_GET['id'])) ? $_GET['id'] : error_message("Something is wrong. Try again");
$action = $_GET['action'];	

$message = "";
/********************************************Build page*****************************************/
$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($action == "Edit" && $_POST['Submit'] == 'Edit') 
{
    $exit = false;
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = strtotime($_POST['dob']);
    $tel = $_POST['tel'];
    $cell = $_POST['cell'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    if ($ulevel == 1 || $ulevel == 0)
    {
        $username = $_POST['usernames'];
        $password = $_POST['passwords'];
        $repass = $_POST['repass'];
        $team = $_POST['team'];
        $level = $_POST['access'];
        $status = $_POST['status'];
        $oldname = $_POST['oldname'];
        $troopuser = $_POST['troopuser'];
    }
    
    $action = $_POST['Submit'];
  
    if ($troopuser == 1)
    {
        $scheme = $_POST['scheme'];
    }
    else
    {
        $scheme = 0;
    }
    
    if ($level == 1 || $level == 0)
    {
        if (($_POST['passwords'] != $_POST['repass']) && ($_POST['password'] != ''))
        {
            error_message("Passwords do not match");
            exit;
        }
        elseif ((strlen($_POST['passwords']) < 6) && ($_POST['password'] != ''))
        {
            error_message("Minimum password length is 6 characters");
            exit;
        }
    }
    
    if (!isset($_POST['email']) || $_POST['email'] == "")
    {
        error_message("You need to supply a email address");
        exit;
    }
    else
    {
        if (is_valid_email_address($_POST['email']))
        {
           error_message("Email address is not valid");
            exit;
        }
    }

    if (!isset($_POST['firstname']) || $_POST['firstname'] == "")
    {
        error_message("You need to supply a first name");
        exit;
    }
    if (!isset($_POST['lastname']) || $_POST['lastname'] == "")
    {
        error_message("You need to supply a last name/surname");
        exit;
    }
    
    if (!isset($_POST['dob']) || $_POST['dob'] == "")
    {
        error_message("You need to supply a birthdate.");
        exit;
    }
    elseif(!validdate($_POST['dob']))
    {
        error_message("The date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd.");
        exit;
    }
    
    $user_query = $data->select_query("authuser", "WHERE id='$id'");
    $user = $data->fetch_array($user_query);
    $datas = $data->select_query("records", "WHERE email='{$_POST['email']}' AND uname != '{$user['uname']}'");
    $numrows = $data->num_rows($datas);
    if ($numrows > 0) 
    {
        error_message("That email address has already been used, please use another email address.");
        exit;
    } 
            

        
        if ($ulevel == 1 || $ulevel == 0)
        {
            $update = $users->modify_user($username, $password, $team, $level, $status, $_POST['zone']);
        
            if ($update==1) 
            {
                $insertSQL = sprintf("firstname=%s, lastname=%s, dob=%s, tel=%s, cell=%s, address=%s, email=%s, uname=%s, scheme=%s, troopuser=%s",
                   safesql($firstname, "text"),
                   safesql($lastname, "text"), 
                   safesql($dob, "int"),
                   safesql($tel, "text"),
                   safesql($cell, "text"),
                   safesql($address, "text"),
                   safesql($email, "text"), 
                   safesql($username, "text"),
                   safesql($scheme, "text"),
                   safesql($troopuser, "int"));
                
                $user_query = $data->select_query("authuser", "WHERE id='$id'");
                $users = $data->fetch_array($user_query);
                $Result1 = $data->update_query("records", $insertSQL, "uname='{$users['uname']}'");
                if ($Result1) 
                {
                    echo "<script> alert('User details updated'); if (confirm('Carry on editing {$users['uname']}?')) window.location = 'admin.php?page=user_edit&action=Edit&id=$id'; else window.location = 'admin.php?page=users';</script>\n";
                    exit; 
                }
            }
            elseif ($update == "blank level") 
            {
                error_message("Level field cannot be blank.");
                exit;
            }
            elseif ($update == "admin cannot be inactivated")
            {
                eerror_message("This user cannot be deactivated");
                exit;
            }
        }
        else
        {
                $insertSQL = sprintf("firstname=%s, lastname=%s, dob=%s, tel=%s, cell=%s, address=%s, email=%s",
                   safesql($firstname, "text"),
                   safesql($lastname, "text"), 
                   safesql($dob, "int"),
                   safesql($tel, "text"),
                   safesql($cell, "text"),
                   safesql($address, "text"),
                   safesql($email, "text"));
                
                $user_query = $data->select_query("authuser", "WHERE id='$id'");
                $users = $data->fetch_array($user_query);
                $Result1 = $data->update_query("records", $insertSQL, "uname='{$users['uname']}'");
                if ($Result1) 
                {
                    echo "<script> alert('User details updated'); if (confirm('Carry on editing {$users['uname']}?')) window.location = 'admin.php?page=user_edit&action=Edit&id=$id'; else window.location = 'admin.php?page=users';</script>\n";
                    exit; 
                }
        }
} 

if ($action == "Edit") 
{
    $user_query = $data->select_query("authuser", "WHERE id='$id'");
    $users = $data->fetch_array($user_query);

    $records_query = $data->select_query("records", "WHERE uname = '{$users['uname']}'");
    $record = $data->fetch_array($records_query);

    $action = 'Edit'; 
} 

$sql = "SELECT * FROM authteam";
$team_query = $data->select_query("authteam");
$numteams = $data->num_rows($team_query);
$teama = $data->fetch_array($team_query);
$teamlist = array();
do 
{
 $teamlist[] = $teama['teamname'];
} while ($teama = $data->fetch_array($team_query));


$sql = $data->select_query("timezones", "ORDER BY offset ASC");
$zone = array();
$numzones = $data->num_rows($sql);
while ($zone[] =  $data->fetch_array($sql));

$sql = $data->select_query("awardschemes", "ORDER BY name ASC");
$schemes = array();
$numschemes = $data->num_rows($sql);
while ($schemes[] =  $data->fetch_array($sql));

$tpl->assign('zone', $zone);
$tpl->assign('numzones', $numzones);
$tpl->assign('schemes', $schemes);
$tpl->assign('numschemes', $numschemes);
$tpl->assign('numteams', $numteams);
$tpl->assign('teamlist', $teamlist);
$tpl->assign('uinfo', $users);
$tpl->assign('details', $record);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('action', $action);

if ($ulevel == 1 || $ulevel == 0) 
{
	include("admin_users.php");
	$filetouse = "admin_users.tpl";
	$limit = "no";
} 
else 
{
	include("admin_users.php");
	$filetouse = "admin_users.tpl";
	$limit = "yes";
} 

$tpl->assign('limit', $limit);
?>