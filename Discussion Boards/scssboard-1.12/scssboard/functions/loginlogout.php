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
	
if ($_GET[act] == "login") {
	$username = $_POST[username];
	$password = $_POST[password];
	$password = md5($password);
	echo "<p align='center'>";
	$chk_username = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_username = '$username'"));
	$chk_username_password = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_username = '$username' and users_password = '$password'"));

	if(!$chk_username) {
		echo "The user '$_POST[username]' does not exist. <a href='javascript:history.back()'>Back...</a>";
	} elseif(!$chk_username_password) {
		echo "Your password was incorrect. <a href='javascript:history.back()'>Back...</a>";
	} else {
		$user_id = $chk_username_password[users_id];
		if($_POST[remember_me]) { $rm = 1; } else { $rm = 0; }
		if ($rm == 1) {
			setcookie("scb_uid",$user_id,time()+2592000,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
			setcookie("scb_ident",$password,time()+2592000,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
		} else {
			setcookie("scb_uid",$user_id,0,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
			setcookie("scb_ident",$password,0,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
		}
		echo "Your account has been confirmed. Please wait while you are redirected to the forum.";
		echo redirect("index.php");
	}
	echo "</p><br />";

} elseif ($_GET[act] == "logout") {
	echo "<p align='center'>";
	if(!$current_user) {
		echo "You cannot log out, because you are not logged in.";
	} else {
		setcookie("scb_uid",0,time()-2592000,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
		setcookie("scb_ident",0,time()-2592000,"$_MAIN[cookie_path]","$_MAIN[cookie_url]");
		echo "Logged out successfully.";
		echo redirect("index.php");
	}
	echo "</p><br />";
}
    ?>