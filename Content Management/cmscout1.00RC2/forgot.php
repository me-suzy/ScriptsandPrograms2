<?php
/**************************************************************************
    FILENAME        :   forgot.php
    PURPOSE OF FILE :   Resets users password and emails user with new password.
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

if ($uname != "Guest" || $uname="")
{
    error_message("Your logged on, how could you have forgotten your password?");
}

if ($_POST['submit'] == "Submit")
{
    $email = $_POST['email'];
    if ($email != "")
    {
        $sql = $data->select_query("records", "WHERE email='$email'");
    }
    else
    {
        show_message("Please enter a email address.");
    }
        
    if ($data->num_rows($sql) > 0 && $email != "")
    {    
        $temp = $data->fetch_array($sql);
        $username = safesql($temp['uname'], "text");
        $password = "";
        srand(time(NULL));
        $passlen = rand()%5+5;
        for($i=0;$i<=$passlen;$i++)
        {
            $password .= chr(rand()%26+97);
        }
        $password = safesql(md5(password), "text");
        $data->update_query("authuser", "passwd=$password", "uname=$username", "Forgetten Password", "User with $email address forgot username/password, uname=$username");
       
        /* subject */
        $subject = "[{$config['troopname']} site Admin] Password Reset";
        
        /* message */
        $emess = "
Hello $username

You are receiving this email because you have (or someone pretending to be you has) requested a new password be sent for your account on {$config['troopname']}. If you did not request this email then please ignore it (But, your password has been changed), if you keep receiving it please contact the webmaster.

You will now be able to login using the following information:

--------------------------
Username: $username
Password: $password
--------------------------

You can of course change this password yourself via the profile page. If you have any difficulties please contact the webmaster.

Regards
{$config['troopname']} Webmaster
{$config['sitemail']}";
    
       
        /* additional headers */
        $headers .= "From: {$config['troopname']} Webmaster <{$config['sitemail']}>\r\n";
        
        /* and now mail it */
        $mailsuc = mail($email, $subject, $emess, $headers);
        if (!$mailsuc) error_message("Error Sending Mail. Please contact the webmaster");
        else show_message("Your username and new password has been emailed to you.");
    }
    elseif ($data->num_rows($sql) == 0 && $email != "")
    {
        show_message("That email address does not seem to exist in our database.");
    }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign('editFormAction', $editFormAction);
$dbpage = true;
$pagename='forgot';
?>