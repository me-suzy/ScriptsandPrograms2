<?php
$z = "b";
include ("config.php");

if ($_GET['login'] == "yes") {

$usernameb = strip_tags(stripcslashes($_POST['username']));
$passwordc = $_POST['password']; 
$passwordb = md5($passwordc);

$sql = mysql_query("SELECT * FROM onecms_users WHERE username='$usernameb' AND password='$passwordb'");
$login_check = mysql_num_rows($sql);

$sql3 = mysql_query("SELECT * FROM onecms_userlevels WHERE name = '$levely'");
while($row = mysql_fetch_array($sql3)) {
	$leveltr = "$row[level]";
}

if (($login_check == "1") && ($leveltr < "6")) {

setcookie("username", $usernameb, time()+24*3600*14);
setcookie("password", $passwordb, time()+24*3600*14);

			mysql_query("UPDATE onecms_users SET logged = '$timelogin' WHERE username = '$usernameb'") or die(mysql_error());

			include ("a_header.inc");
            
               
	       	echo "Login Successful! Welcome back ".$usernameb.". Continue onto admin CP home...<br><a href='a_index.php?view=home'>Continue</a>";
	} else {
			include ("a_header.inc");

mysql_query("UPDATE onecms_users SET logged = '0' WHERE id = '".$row[id]."'") or die(mysql_error());

echo "Sorry, but your login info is incorrect. Please <a href='a_login.php'>go back</a> and try again.";
}
}

if ($_GET['login'] == "no") {
	include ("a_header.inc");
	echo "You are only a $level, you do not have permission to access the admin panel.";
}

if ($_GET['login'] == "") {

include ("a_header.inc");

echo '<form action="a_login.php?login=yes" method="post">
<table cellpadding=2 cellspacing=0 border=0>
<td>Username:</td><td><input type="text" name="username" value="" size=10></td><tr>
<td>Password:</td><td><input type="password" name="password" value="" size=10></td><tr><td><input type="submit" name="submit" value="Log In"></td>
</table></form>';
}
include ("a_footer.inc");
?>