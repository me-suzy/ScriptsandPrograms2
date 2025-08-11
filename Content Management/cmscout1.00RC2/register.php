<?php
/**************************************************************************
    FILENAME        :   register.php
    PURPOSE OF FILE :   Allows users to register on the site
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

$errors = '';
$exit = false;
$script = "";
if (!isset($check["level"]) || $check["level"] == '' || $check["level"] == "-1" || $check["uname"] == "Guest") 
{
    if ($config['register'] == 1)
    {
        $rid = $_GET['rid'];
        if (!$rid) {
            $rid = md5(time() + "registration");
        }
        
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
          $editFormAction .= "?page=register&amp;rid=$rid";
        }
        
        $dataa = $data->select_query("temp", "WHERE name='$rid'");
        $t= $data->fetch_array($dataa);
        $info = @unserialize($t['value']);
        
        $oldpage = $pagenum;
        if ($_POST['back'] == 'Back') 
        {
            switch($oldpage)
            {
                case 1:
                    break;
                case 2:
                    $pagenum=1;
                    break;
                case 3:
                    if ($info['member'] == 1)
                    {
                        $pagenum=2;
                    }
                    else
                    {
                        $pagenum=1;
                    }
                    break;
            }
        }
        elseif ($_POST['next'] == 'Next') 
        {
            $pagenum++;
        }       

        if ($pagenum == 0)
            $pagenum = 1;
        
        $sql = $data->select_query("timezones", "ORDER BY offset ASC");
        $zone = array();
        $numzones = $data->num_rows($sql);
        while ($zone[] =  $data->fetch_array($sql));
        $tpl->assign('zone', $zone);
        $tpl->assign('numzones', $numzones);   
        
        switch($pagenum)
        {
            case 1:	             
                break;
        case 2:
            if ($oldpage==1) 
            {
                $errors .= "<span id=\"error\">";
                
                if (!isset($_POST['usernames']) || $_POST['usernames'] == "")
                    $errors .= "You need to supply a username<br />";
                
                if (isset($_POST['usernames']) && strtoupper($_POST['usernames']) == "GUEST")
                    $errors .= "'Guest' is a restricted username, please choose another username<br />";
                
                if (!isset($_POST['passwords']) || $_POST['passwords'] == "")
                    $errors .= "You need to supply a password<br />";
                elseif ($_POST['passwords'] != $_POST['repass'])
                    $errors .= "Passwords do not match<br />";
                elseif (strlen($_POST['passwords']) < 6)
                    $errors .= "Minimum password length is 6 characters<br />";
                
                if (!isset($_POST['email']) || $_POST['email'] == "")
                    $errors .= "You need to supply a email address<br />";
                else
                {
                    $temp = $_POST['email'];
                    if (is_valid_email_address($temp))
                    {
                       $errors .= "Email address is not valid<br />";
                    }
                }
    
                if (!isset($_POST['firstname']) || $_POST['firstname'] == "")
                    $errors .= "You need to supply a first name<br />";
                if (!isset($_POST['lastname']) || $_POST['lastname'] == "")
                    $errors .= "You need to supply a last name/surname<br />";
               
                if (!isset($_POST['dob']) || $_POST['dob'] == "")
                    $errors .= "You need to supply your birthdate. You can use the ... button next to the text box to open a calender<br />";
                elseif(!validdate($_POST['dob']))
                {
                    $errors .= "The date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender<br />";
                }

            if ($errors != "<span id=\"error\">")
                {
                    $errors .= "</span>";
                    $exit = true;
                }
                else
                    $errors = "";
                    
                $info["password"] = $_POST['passwords'];
                $info["email"] = $_POST['email'];
                $info["member"] = $_POST['member'];
                $info['first'] = $_POST['firstname'];
                $info['last'] = $_POST['lastname'];
                $info['dob'] = strtotime($_POST['dob']);
                $info['address'] = $_POST['address'];
                $info['tel'] = $_POST['tel'];
                $info['cell'] = $_POST['cell'];     
                $info["username"] = $_POST['usernames'];
                $info['timezone'] = $_POST['zone'];
                
                $datas = $data->select_query("authuser", "WHERE uname='{$info['username']}'");
                $numrows = $data->num_rows($datas);
                if ($numrows > 0) 
                {
                    $errors .= "<span id=\"error\">That username already exists, please try a different username<br /></span>"; 
                    $exit = true;
                }
                
                $datas = $data->select_query("records", "WHERE email='{$info['email']}'");
                $numrows = $data->num_rows($datas);
                if ($numrows > 0) 
                {
                    $errors .= "<span id=\"error\">That email address has already been used, please use another email address.<br /></span>"; 
                    $exit = true;
                }   
                
                
    
                $temp = serialize($info);
                if($data->num_rows($data->select_query("temp", "WHERE name = '$rid'")) == 0 && $exit != true)
                {
                    $data->insert_query("temp", "'$rid', '$temp'", "", "", false);
                }
                elseif($exit != true)
                {
                    $data->update_query("temp", "value = '$temp'", "name='$rid'", "", "", false);
                }
            }
             
            if($info['member'] == 1)
            {        
                if ($exit != true) 
                {	
                    $sql = $data->select_query("authteam", "WHERE register=1");
                    $numgroups = $data->num_rows($sql);
                    $groups = array();
                    while ($groups[] = $data->fetch_array($sql));
                    
                    $tpl->assign("groups", $groups);
                    $tpl->assign("numgroups", $numgroups);
                    
                    $sql = $data->select_query("awardschemes", "ORDER BY name ASC");
                    $schemes = array();
                    $numschemes = $data->num_rows($sql);
                    while ($schemes[] =  $data->fetch_array($sql));
                    $tpl->assign('schemes', $schemes);
                    $tpl->assign('numschemes', $numschemes);
                }
                break;
            }
            else
            {
                $pagenum = 3;
            }
        case 3:
            if ($oldpage==2) 
            {
                $errors .= "<span id=\"error\">";
                
                if (!isset($_POST['patrol']) || $_POST['patrol'] == "")
                    $errors .= "You need to supply a patrol name<br />";
                
                if (!isset($_POST['pos']) || $_POST['pos'] == "")
                    $errors .= "You need to supply a position in your patrol<br />";
    
                if ($errors != "<span id=\"error\">")
                {
                    $errors .= "</span>";
                    $exit = true;
                }
                else
                    $errors = "";
                
                $dataa = $data->select_query("temp", "WHERE name='$rid'");
                $t= $data->fetch_array($dataa);
                $info = @unserialize($t['value']);
                
                $info["patrol"] = $_POST['patrol'];
                $info["pos"] = $_POST['pos'];
                $info['badges'] = $_POST['badges'];
                $info['challenge'] = $_POST['challenge'];
                $info['scheme'] = $_POST['scheme'];
                
                $temp = serialize($info);
                
                if($data->num_rows($data->select_query("temp", "WHERE name = '$rid'")) == 0 && $exit != true)
                {
                    $data->insert_query("temp", "'$rid', '$temp'", "", "", false);
                }
                elseif($exit != true)
                {
                    $data->update_query("temp", "value = '$temp'", "name='$rid'", "", "", false);
                }
            }       
            break;
        case 4:
            $repass = $_POST['repass'];
            $reemail = $_POST['reemail'];
            if ($repass == "")
            {
                $errors .= "<span id=\"error\">You need to retype your password</span><br />";
                $exit = true;
            }
            
            if ($reemail == "")
            {
                $errors .= "<span id=\"error\">You need to retype your email address</span><br />";
                $exit = true;
            }
            
            if ($repass != $info['password']) {
                $errors .= "<span id=\"error\">The password you typed in is not the same as the orginal password, please try again</span>";
                $exit = true;
            }
            if ($reemail != $info['email']) {
                 $errors .= "<span id=\"error\"><br />The email address you typed in is not the same as the orginal email address, please try again</span>";
                $exit = true;
            } 
            
            if(!$exit)
            {
                $situation = $Auth->add_user(safesql($info["username"], "text"), md5($info["password"]), "New User", "5", "Active", $info['timezone']);
                if ($situation > 0)
                {
                    
                    $scheme = 0;
                    $member = $info['member'] == 1 ? 1 : 0;
                    if ($info['member'] == 1 && $exit != true) 
                    {
                        $patrol = safesql($info['patrol'], "text");
                        $pos = safesql($info['pos'], "text");
                        $badges = safesql($info['badges'], "text");
                        $challenge = safesql($info['challenge'], "text");
                        $uname = safesql($info['username'], "text");
                        $scheme = $info['scheme'];
                        $data->insert_query("registerinfo", "$uname, $patrol, $pos, $badges, $challenge", "", "", false);
                    } 
                    $insertSQL = sprintf("'', %s, %s, %s, %s, %s, %s, %s, %s, '', '', 0, 1, 1, %d, %s",
                           safesql($info["first"], "text"),
                           safesql($info["last"], "text"),
                           safesql($info["dob"], "int"),
                           safesql($info["tel"], "text"),
                           safesql($info["cell"], "text"),
                           safesql($info["address"], "text"),
                           safesql($info["email"], "text"),  
                           safesql($info["username"], "text"),
                           safesql($scheme, "int"),
                           $member);
                           
                    $Result = $data->insert_query("records", $insertSQL, "", "", false);
                    $Result = 1;
                    if ($Result == 1) 
                    {
                        $data->delete_query("temp", "name='$rid'", "", "", false);
                        /* subject */
                        $subject = "[{$config['troopname']}] Registration Information";
                        
                        /* message */
                        $emess = "Hi, {$info["username"]}
    
    Welcome to the {$config['troopname']} website.
    
    Please keep this email for your records. Your account information is as follows:
    
    ----------------------------
    Username: {$info["username"]}
    Password: {$info["password"]}
    ----------------------------
    
    Please do not forget your password as it has been encrypted in our database and we cannot retrieve it for you. However, should you forget your password you can request a new one.
    
    Thank you for registering.
    
    Regards
    {$config['troopnmame']} Webmaster
    {$config['sitemail']}
                    ";
                
                   
                    /* additional headers */
                    $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
                    
                    /* and now mail it */
                    $mailsuc = mail($info["email"], $subject, $emess, $headers);
                    
                    $subject = "[{$config['troopname']}] New user registered on your site";
                    
                    /* message */
                    $emess = "Hi {$config['troopname']} Webmaster,
    
    This email is from the site just to inform you that the following user has registered (Sorry, you're not getting their password)
    
    Username: {$info["username"]}
    
    First Name: {$info["first"]}
    Last Name: {$info["last"]}";
    
    if ($info['member'] == 1 && $exit != true) 
    {
     $emess .= "
             
    He/She indicated that they are part of your troop, their scouting details is as follows:
        Patrol: $patrol
        Position: $pos
        Advancement Level: $advance
        Interest Badges: $badges
        Callange Awards: $challenge";
    }
     $emess .= "
    Well, thats all.
    Regards
    {$config['troopname']}'s website";
                
                       
                        /* additional headers */
                        $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
                        
                        /* and now mail it */
                        $mailsuc = mail($config['sitemail'], $subject, $emess, $headers);
                    }
                }
            }
        }
        
        if ($exit == true) {
            $pagenum = $oldpage;
        }
        
        $tpl->assign("info", $info);
        $tpl->assign("editFormAction", $editFormAction);
        $tpl->assign("pagenum", $pagenum);
        $tpl->assign("script", $script);
        $tpl->assign("errors", $errors);
        
        $dbpage = true;
        $pagename = "register";
    }
    else
    {
        error_message("The administrator has disabled registrations.");
    }
} 
else 
{
    error_message("You are already logged in.");
}
?>