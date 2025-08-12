<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/LOGIN.PHP > 03-11-2005

include("system/error.php"); 
extract($_POST);
extract($_GET);
 
if ($a == "logout") { 
	include("../config.php"); 
	include("cookies.php"); 
	include("system/functions.php"); 
	destroy_cookie("mobsuser"); 
	destroy_cookie("mobspass"); 
	$login = TRUE; 
	include("$skindir/header.php"); 
	if ($reset) {
		echo "You've been logged out, back to where you <a href='/sets/$reset'>came from</a> with you";
	} else {
		echo "You've been logged out, back to the <a href='/'>index</a> with you."; 
	}
	include("$skindir/footer.php");
	exit;
} 
 
if ($login) { 
	echo  " 
		<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td colspan=2><form action='login.php' method='post'></td></tr> 
		<tr><td width=175>Username</td><td><input type='text' name='login_username' size=50></td></tr> 
		<tr><td width=175>Password</td><td><input type='password' name='login_password' size=50></td></tr> 
		<tr><td width=175>&nbsp;</td><td><br /><input type='submit' value='Login'></td></tr></table> 
		"; 
} else { 
	include("../config.php"); 
	include("cookies.php"); 
	include("system/functions.php"); 
	if (!$login_username || !$login_password) { 
		include("$skindir/header.php"); 
		echo $error[0]; 
	} else { 
		loaduser($login_username); 
		if ($login_username == $userdata['username']) { 
			$login_password = md5($login_password); 
			if ($login_password == $userdata['password']) { 
				install_cookie("mobsuser",$login_username); 
				install_cookie("mobspass",$login_password); 
				include("$skindir/header.php"); 
				echo "<meta http-equiv=Refresh content=0;URL='index.php'>"; 
			} else { 
				include("$skindir/header.php"); 
				echo $error[1]; 
			} 
		} else { 
			include("$skindir/header.php"); 
			echo $error[2]; 
		} 
	} 
} 
?>