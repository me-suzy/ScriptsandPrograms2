<?php
/**************************************************************************
    FILENAME        :   profile.php
    PURPOSE OF FILE :   Allows users to change password, theme and other information
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
location("Editing Profile", $check["uid"]);

if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$message = "";
$exit = false;

if (!$error) 
{
    /********************************************Build page*****************************************/
    $currentPage = $_SERVER["PHP_SELF"];
    
    $uname = $check["uname"];
    
    $scouts = $data->select_query("records", "WHERE uname = '$uname'");
    $row_scouts = $data->fetch_array($scouts);
    $totalRows_scouts = $data->num_rows($scouts);
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    if ($_POST["submit"] == "Update record") 
    {
        if ($_FILES['avy']['name'] != '')
        {
            $avyfilename = "";
            $sql=$data->select_query("records", "WHERE uname='$uname'");
            $temp = $data->fetch_array($sql);
            unlink($config['avatarpath']."/".$temp['avyfile']);
            if (($_FILES['avy']['type'] == 'image/gif') || ($_FILES['avy']['type'] == 'image/jpeg') || ($_FILES['avy']['type'] == 'image/png') || ($_FILES['avy']['type'] == 'image/pjpeg')) 
            {
                $filestuff = uploadpic($_FILES['avy'], $config['avyx'], $config['avyy'], false, $config['avatarpath']);
                $avyfilename = $filestuff['filename'];
            }
            else
            {
                error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}");
            }
        }
        $sig = strip_tags($_POST['sig'], "<img>, <a>");
        if (strlen($sig) > $config['sigsize'])
        {
            error_message("Your signature is to long, it can't be longer than {$config['sigsize']} characters");
            exit;
        }
        
        $errors .= "<span id=\"error\">";
                
        if (($_POST['password'] != $_POST['repassword']) && ($_POST['password'] != ''))
            $errors .= "Passwords do not match<br />";
        elseif ((strlen($_POST['password']) < 6) && ($_POST['password'] != ''))
            $errors .= "Minimum password length is 6 characters<br />";
        
        if (!isset($_POST['email']) || $_POST['email'] == "")
            $errors .= "You need to supply a email address<br />";
        else
        {
            if (is_valid_email_address($_POST['email']))
            {
               $errors .= "Email address is not valid<br />";
            }
        }

        if (!isset($_POST['firstname']) || $_POST['firstname'] == "")
            $errors .= "You need to supply your first name<br />";
        if (!isset($_POST['lastname']) || $_POST['lastname'] == "")
            $errors .= "You need to supply your last name/surname<br />";
            
        if (!isset($_POST['dob']) || $_POST['dob'] == "")
            $errors .= "You need to supply your birthdate. You can use the ... button next to the text box to open a calender<br />";
        elseif(!validdate($_POST['dob']))
        {
            $errors .= "The date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender<br />";
        }
        
        $datas = $data->select_query("records", "WHERE email='{$_POST['email']}' AND uname != '{$check['uname']}'");
        $numrows = $data->num_rows($datas);
        if ($numrows > 0) 
        {
            $errors .= "That email address has already been used, please use another email address.<br />"; 
        } 
                
        if ($errors != "<span id=\"error\">")
        {
            $errors .= "</span>";
            $exit = true;
        }
        else
            $errors = "";
        

        if (!$exit)
        {
            if ($_FILES['avy']['name'] != '')
            {
                $insertSQL = sprintf("firstname=%s, lastname=%s, dob=%s, tel=%s, cell=%s, address=%s, email=%s, newtopic=%s, allowemail=%s, newpm=%s, sig=%s, avyfile=%s, scheme=%s",
                       safesql($_POST['firstname'], "text"),
                       safesql($_POST['lastname'], "text"),
                       safesql(strtotime($_POST['dob']), "int"),
                       safesql($_POST['tel'], "text"),
                       safesql($_POST['cell'], "text"),
                       safesql($_POST['address'], "text"),
                       safesql($_POST['email'], "text"),
                       safesql($_POST['newtopic'], "int"),
                       safesql($_POST['allowemail'], "int"),
                       safesql($_POST['newpm'], "int"),             
                       safesql($sig, "text"),
                       safesql($avyfilename, "text"),
                       safesql($_POST['scheme'], "int"));
            }
            else
            {
                        $insertSQL = sprintf("firstname=%s, lastname=%s, dob=%s, tel=%s, cell=%s, address=%s, email=%s, newtopic=%s, allowemail=%s, newpm=%s, sig=%s, scheme=%s",
                       safesql($_POST['firstname'], "text"),
                       safesql($_POST['lastname'], "text"),
                       safesql(strtotime($_POST['dob']), "int"),
                       safesql($_POST['tel'], "text"),
                       safesql($_POST['cell'], "text"),
                       safesql($_POST['address'], "text"),
                       safesql($_POST['email'], "text"),
                       safesql($_POST['newtopic'], "int"),
                       safesql($_POST['allowemail'], "int"),
                       safesql($_POST['newpm'], "int"),             
                       safesql($sig, "text"),
                       safesql($_POST['scheme'], "int"));
            }
            $Result1 = $data->update_query("records", $insertSQL, "uname='$uname'", "", "", false);
            if ($Result1) 
            {
               $themeid = $_POST['theme'];
                $zone = $_POST['zone'];
                $pass = md5($_POST['password']);
                $repass = md5($_POST['repassword']);
                if ($pass != $repass) 
                {
                    error_message("Passwords don\'t match");
                }
                $oldpass = $check['passwd'];
                if ($pass == $oldpass) 
                { 
                    $pass=$oldpass; 
                } 
                elseif ($pass == md5('')) 
                { 
                    $pass=$oldpass; 
                }
        
                $insertSQL = sprintf("passwd=%s, theme_id=%s, timezone=%s",
                      safesql($pass, "text"),
                      safesql($themeid, "int"),
                      safesql($zone, "int"));
        
                $Result2 = $data->update_query("authuser", $insertSQL, "uname='$uname'", "", "", false);					   
                if (($Result1) && ($Result2)) 
                { 
                    show_message("Information successfully updated");
                    echo "<script> window.location = 'index.php?page=profile';</script>";
                }
            } 	
        }
        else
        {
            $tpl->assign("errors", $errors);
        }
    }

        $theme_q = $data->select_query("themes");
        $theme = array();
        $numthemes = $data->num_rows($theme_q);
        while ($theme[] =  $data->fetch_array($theme_q));
        
        $sql = $data->select_query("timezones", "ORDER BY offset ASC");
        $zone = array();
        $numzones = $data->num_rows($sql);
        while ($zone[] =  $data->fetch_array($sql));
        
        $sql = $data->select_query("awardschemes", "ORDER BY name ASC");
        $schemes = array();
        $numschemes = $data->num_rows($sql);
        while ($schemes[] =  $data->fetch_array($sql));
    
    if (!$exit)
    {        
        $tpl->assign('personal', $row_scouts);
    }
    else
    {
        $row_scouts['firstname'] = $_POST['firstname'];
        $row_scouts['lastname'] = $_POST['lastname'];
        $row_scouts['dob'] = $_POST['dob'];
        $row_scouts['tel'] = $_POST['tel'];
        $row_scouts['cell'] = $_POST['cell'];
        $row_scouts['address'] = $_POST['address'];
        $row_scouts['email'] = $_POST['email'];
        $row_scouts['newtopic'] = $_POST['newtopic'];
        $row_scouts['allowemail'] = $_POST['allowemail'];
        $row_scouts['newpm'] = $_POST['newpm'];    
        $row_scouts['sig'] = $_POST['sig'];
        $row_scouts['scheme'] = $_POST['scheme'];
        $tpl->assign('personal', $row_scouts);
    }

    $tpl->assign('uinfo', $check);
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign('numthemes', $numthemes);
    $tpl->assign('theme', $theme);
    $tpl->assign('zone', $zone);
    $tpl->assign('numzones', $numzones);
    $tpl->assign('schemes', $schemes);
    $tpl->assign('numschemes', $numschemes);

}
$dbpage = true;
$pagename = "profile";
?>