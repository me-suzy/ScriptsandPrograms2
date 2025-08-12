<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/REGISTER.PHP > 03-11-2005

include("../config.php"); 
include("cookies.php"); 
include("system/functions.php"); 
destroy_cookie("mobsuser"); 
destroy_cookie("mobspass"); 
$login = TRUE; 
include("$skindir/header.php"); 
include("system/error.php"); 
 
$result = mysql_query("SELECT * FROM ".$prefix."settings"); 
while($row=mysql_fetch_object($result)) { 
	$registration = $row->registration; 
} 
 
if ($registration == 1) {
if (!$action) { 
		if ($e) { echo $error[$e]; echo "<br><br>"; } 
		echo "<strong>Register as new user</strong><br /><table><tr><td><form method='post' action='$PHP_SELF'></td></tr>				</table><input type='hidden' name='action' value='register'> 
		<table> 
		<tr><td width=175>Username</td><td><input size=50 maxlength=20 type='text' name='un' value='$un'></td></tr> 
		<tr><td width=175>Password once</td><td><input size=50 type='password' name='pw1'></td></tr> 
		<tr><td width=175>Password twice</td><td><input size=50 type='password' name='pw2'></td></tr> 
		<tr><td width=175>Email</td><td><input size=50 type='text' name='email' value='$email'></td></tr> 
		<tr><td width=175>Register</td><td><input size=50 type='submit' value='proceed'></td></tr></table> 
		"; 
} elseif ($action == "register") { 
		if (!$un) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=3'>";  
		if (!$pw1 && !$pw2) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=4&un=$un&email=$email'>";  
		if (!$pw1 || !$pw2) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=5&un=$un&email=$email'>";  
 
		if(!eregi("@", $email)) { echo "<meta http-equiv=Refresh content=0;URL='register.php?e=6&un=$un&email=$email'>"; $email = ""; } 
 
		if (!$email) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=7&un=$un'>";  
 
		$result = mysql_query("SELECT * FROM ".$prefix."users"); 
		while($row=mysql_fetch_object($result)) { 
			if ($un == $row->username) { 
				$exists = 1; 
			} 
		} 
 
		if ($exists) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=8&un=$un&email=$email'>";  
		if ($pw1 != $pw2) echo "<meta http-equiv=Refresh content=0;URL='register.php?e=9&un=$un&email=$email'>";  
 
		$pass = md5($pw1); 
 
		if ($pw1 && $pw2 && $un && $email && $pw1 == $pw2 && $exists == 0) { 
			$result = mysql_query("SELECT * FROM ".$prefix."settings"); 
			while($row=mysql_fetch_object($result)) { 
				$startlevel = $row->startlevel; 
			} 
 
			$result = mysql_query("INSERT INTO ".$prefix."users  
				(username,password,level)  
				VALUES  
				('$un','$pass','$startlevel')"); 
			$result = mysql_query("INSERT INTO ".$prefix."profile  
				(username,nickname,email)  
				VALUES  
				('$un','$un','$email')"); 
 
			echo "<meta http-equiv=Refresh content=0;URL='register.php?action=complete'>"; 
		} 
} elseif ($action == "complete") { 
		echo "Registration complete - <a href='index.php'>log in</a>"; 
} 
} else {
		echo "Registration not allowed";
}

include("$skindir/footer.php"); 
 
?>