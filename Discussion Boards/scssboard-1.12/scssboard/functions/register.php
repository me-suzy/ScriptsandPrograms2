<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php
if ($_MAIN[allow_signups] == "yes") {
if($_GET[acc] == "") {
echo "

<div class='catheader' style='width:300px; padding:5px; margin-left:auto; margin-right:auto;'>&nbsp; <b>Register</b></div>
<div class='msg_content' style='width:300px; padding:5px; margin-left:auto; margin-right:auto;'>";
                    if($_COOKIE[sCssForum]) {
                        echo "<p align='center'>You are already registered and logged in.</p>";
                    } else {
                        echo "<form method='post' action='index.php?&amp;act=register&amp;acc=new'><br />
                        &nbsp; Username:<br />
                        &nbsp; <input type='text' name='username' size='20' class='input'><br /><br />
                        &nbsp; Password (twice):<br />
                        &nbsp; <input type='password' name='password' size='20' class='input'>&nbsp;
								<input type='password' name='password2' size='20' class='input'><br />
						&nbsp; <span style='font-size:10px;'>(At least 5 characters)</span><br /><br />
                        &nbsp; E-Mail:<br />
                        &nbsp; <input type='email' name='email' size='20' class='input'><br /><br />
                        &nbsp; <input type='submit' value='Submit' class='form_button'>
                        </form>";
                    }
                echo "</div>
        <br />";
    } elseif($_GET[acc] == "new") {
        $username = $_POST[username];
        $password = $_POST[password];
        $password2 = $_POST[password2];
        $email = $_POST[email];
		$username = trim($username);
		$username = strip_tags($username);
        echo "<p align='center'>";
					if($email) {
					$valid_email_1 = strpos($email,"@");	//I made a stupid mistake here in 1.1. Thanks a bunch
					$valid_email_2 = strpos($email,".");	//to tuoermin for pointing it out and helping fix it.
					}
					if((!$valid_email_1) or (!$valid_email_2)) {
						echo "Error: Invalid e-mail address. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif($username == "") {
                        echo "Error: Username was left blank. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif(strlen($username) > 16) {
                        echo "Error: Username must have 16 or less characters. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif($password == "") {
                        echo "Error: Password was left blank. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif(strlen($password) > 10) {
                        echo "Error: Password must have 5 - 10 characters. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif(strlen($password) < 5) {
                        echo "Error: Password must have 5 - 10 characters. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } elseif($password != $password2) {
                        echo "Error: Password fields do not match. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                    } else {
                        $check_username_and_email = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_username = '$username'"));
                        if($check_username_and_email) {
                            echo "Error: This username has already been registered. <br /><br /><span class='main_button'><a href='javascript:history.back()'>Back</a></span>";
                        } else {
							$password = md5($password);
                            @mysql_query("insert into $_CON[prefix]users(users_username,users_password,users_email,users_style) values('$username','$password','$email','$_MAIN[default_style]')");
                            echo "Congratulations, your account was created successfully! You may now log in by entering your details at the top of the page.";
                        }
                    }
                echo "</p><br />";
    }
} else {
	echo "New registrations disabled.";
}
?>