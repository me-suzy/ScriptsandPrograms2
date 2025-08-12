<?php
$z = "b";
include ("config.php");

if ($ipbancheck1 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the site...now go away!";
} else {

if ((($_GET['action'] == "register") && ($_GET['ver'] == "yes") && ($_GET['other'] == ""))) {
if (($_COOKIE[username]) && ($_COOKIE[password])) {
headera();
echo "Umm you already have an account";
die;
} else {
	$query4 = mysql_query("SELECT * FROM onecms_users WHERE username = '".$_POST["username"]."'") or die(mysql_error());
    $check = mysql_num_rows($query4);
	if ($check > "0") {
	echo "Sorry, but the username <i>".$_POST["username"]."</i>, is already in use. Please go back and <a href='members.php?action=register'>choose another username</a>";
	} else {

if ($_POST['passwordc'] == "") {
echo "Sorry, but you did not provide a password. Please go back and <a href='members.php?action=register'>try again</a>";
} else {

$passk = md5($_POST["passwordc"]);
$userk = stripslashes($_POST['username']);

if (checkemail($_POST['email']) == TRUE) {
$registera1 = "INSERT INTO onecms_permissions VALUES ('null', '".$userk."', 'yes', 'no'";

	$catq = mysql_query("SELECT * FROM onecms_cat");
	while($row = mysql_fetch_array($catq)) {
		$registera1 .= ", 'no'";
	}
	$registera1 .= ")";

$register1 = mysql_query($registera1);

$register = "INSERT INTO onecms_users VALUES ('null', '".$userk."', '$passk', '".$_POST["email"]."'";
	$query = mysql_query("SELECT * FROM onecms_userlevels WHERE level = '6' LIMIT 1");
	while($row = mysql_fetch_array($query)) {
		$levelh = "$row[name]";
	}
$register .= ", '$levelh', '0', 'no', 'no', '', '1', '1', '')";
$register3p = "INSERT INTO onecms_profile VALUES ('null', '".$userk."', '', '', '', '', '', '', ''";

$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id` DESC");
while($row = mysql_fetch_array($query)) {
	$register3p .= ", ''";
}

$register3p .= ")";

mysql_query($register3p);
$register2 = mysql_query($register) or die(mysql_error());

if ($_POST['autologin']) {
setcookie("username", $userk, time()+24*3600*14);
setcookie("password", $passk, time()+24*3600*14);
}

}
}
}
if (($register2 == TRUE) && ($register1 == TRUE)) {
headera();
echo "Registration successfull! - <a href='".$_POST['url']."'>Return to where you were</a><br>";
} else {
headera();
echo "Sorry but that email is not valid<br>";
}
}
}

if ((($_GET['action'] == "logout") && ($_GET['other'] == "") && ($_COOKIE[username]))) {

mysql_query("UPDATE onecms_users SET logged = '0' WHERE username = '".$_COOKIE[username]."'") or die(mysql_error());

setcookie("username", "", time()-24*3600*14);
setcookie("password", "", time()-24*3600*14);

headera();
echo "You are now logged out.";
}

if ((($_GET['action'] == "login") && ($_GET['step'] == "2") && ($_GET['other'] == ""))) {

$loginuser = stripslashes($_POST['username']);
$loginpass = md5(stripslashes($_POST['password']));
$url = stripslashes($_POST['url']);

$sql = mysql_query("SELECT * FROM onecms_users WHERE username = '".$loginuser."' AND password = '".$loginpass."' LIMIT 1");
$login_check = mysql_num_rows($sql);

if ($login_check == "1") {

setcookie("username", $loginuser, time()+24*3600*14);
setcookie("password", $loginpass, time()+24*3600*14);

mysql_query("UPDATE onecms_users SET logged = '".time()."' WHERE username = '".$loginuser."' AND password = '".$loginpass."'") or die(mysql_error());

headera();
echo "Login Successful! Welcome back ".$loginuser.". Continue to where you were...<a href='".$url."'><b>Continue</b></a>";

} else {
headera();

mysql_query("UPDATE onecms_users SET logged = '0' WHERE username = '".$loginuser."'") or die(mysql_error());

echo "Sorry, but your login info is incorrect. Please <b>go back</b> and try again.";
}
}

if ((($_GET['action'] == "register") && ($_GET['ver'] == "") && ($_GET['other'] == ""))) {
if (($_COOKIE[username]) && ($_COOKIE[password])) {
headera();
echo "Umm you already have an account";
die;
} else {
headera();
echo "<form action='members.php?action=register&ver=yes' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'><tr><td>Username</td><td><input type='text' name='username'></td></tr><tr><td>Password</td><td><input type='password' name='passwordc'></td></tr><tr><td>E-mail</td><td><input type='text' name='email'></td></tr><tr><td><input type='checkbox' name='autologin' checked>Log me in</td><td><input type='submit' value='Submit'></td></tr></table></form>";
}
}

if (((($_GET['action'] == "login") && ($_GET['step'] == "1") or ($_GET['step'] == "") && ($_GET['action'] == "login")))) {
if (($_COOKIE[username]) && ($_COOKIE[password])) {
headera();
echo "Umm you are already logged in";
die;
} else {
headera();
echo '<form action="members.php?action=login&step=2" method="post"><table cellpadding="2" cellspacing="0" border="0"><tr><td>Username:</td><td><input type="text" name="username" size="10"></td><tr><td>Password:</td><td><input type="password" name="password" size="10"></td></tr><tr><td><input type="submit" name="submit" value="Log In"></td></tr></table>';
echo "<input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></form>";
}
}

if (($_GET['action'] == "list") && ($_GET['other'] == "")) {
headera();
echo "<table cellpadding=5 cellspacing=0 border=0><tr><td><b>Username</b></td><td><b>E-Mail</b></td><td><b>Level</b></td></tr>";
$query="SELECT * FROM onecms_users ORDER BY `id` DESC";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	echo "<tr><td>";
$query2 = mysql_query("SELECT id FROM onecms_profile WHERE username = '$row[username]'");
$pn1 = mysql_fetch_row($query2);
$pn2 = mysql_num_rows($query2);

$pn = $pn2;
$pid = $pn1[0];
if ($pn == "") {
	echo "$row[username]";
} else {
	echo "<a href='members.php?action=profile&id=".$pid."'>$row[username]</a>";
}
	echo "</td><td><a href='mailto:".$row[email]."'>$row[email]</a></td><td>$row[level]</td></tr>";
}
echo "</table>";
}

if ($_GET['action'] == "changepass") {
headera();
if ($_COOKIE[username] == "") {
echo "Sorry, but you need to <a href='members.php?action=login&step=1'><b>login</b></a> or <a href='members.php?action=register'><b>register</b></a>";
} else {
echo "<form action='members.php?action=changepass2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>New Password</b></td><td><input type='password' name='pass'></td></tr><tr><td><b>Confirm New Password</b></td><td><input type='password' name='pass2'></td></tr><tr><td><input type='submit' name='submit' value='Change Password'></td></tr></table></form>";
}
}

if ($_GET['action'] == "changepass2") {
headera();
if ($_COOKIE[username] == "") {
echo "Sorry, but you need to <a href='members.php?action=login'><b>login</b></a> or <a href='members.php?action=register'><b>register</b></a>";
die;
} else {
if ($_POST['pass'] == $_POST['pass2']) {
$sql = mysql_query("UPDATE onecms_users SET password = '".md5($_POST['pass'])."' WHERE username = '".$_COOKIE[username]."'");

if ($sql == TRUE) {
headera();
echo "Congratulations, you have successfully changed your password to: <b>".$_POST['pass']."</b>";
}
} else {
echo "Passwords do not match. Go back and try again.";
}
}
}

if ($_GET['action'] == "profile") {
headera();
if (($_GET['other'] == "") && ($_COOKIE[username] == "")) {
echo "<SCRIPT LANGUAGE=\"JavaScript\">

<!-- Begin
redirTime = \"1\";
redirURL = \"members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."\";
function redirTimer() { self.setTimeout(\"self.location.href = redirURL;\",redirTime); }
//  End -->
</script>

<BODY onLoad=\"redirTimer()\">";
   }
// AUTH END
	if ($_GET['id'] == "") {
	if ((!$_POST['submit']) && ($_COOKIE[username])) {
	$query="SELECT * FROM onecms_profile WHERE username = '".$_COOKIE[username]."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$aim = stripslashes($row[aim]);
		$msn = stripslashes($row[msn]);
		$website = stripslashes($row[website]);
		$nickname = stripslashes($row[nickname]);
		$location = stripslashes($row[location]);
		$sig = stripslashes($row[sig]);
		$avatar = stripslashes($row[avatar]);
	
		echo "<center><a href='members.php?action=profile&id=".$useridn."'>View Profile</a><br><a href='members.php?action=changepass'>Change Password</a></center><br><form action='members.php?action=profile' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>AIM</b></td><td><input type='text' name='aim' value='".$aim."'></td></tr><tr><td><b>MSN</b></td><td><input type='text' name='msn' value='".$msn."'></td></tr><tr><td><b>Website</b></td><td><input type='text' name='website' value='".$website."'></td></tr><tr><td><b>Nickname</b></td><td><input type='text' name='nickname' value='".$nickname."'></td></tr><tr><td><b>Location</b></td><td><input type='text' name='location' value='".$location."'></td></tr><tr><td><b>Avatar</b></td><td><input type='text' name='avatar' value='".$avatar."' size='36'></td><td>";
		if ($avatar) {
		echo "<script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";
		
		list($widtha, $heighta) = getimagesize("".$avatar."");

		$heighta2 = $heighta + 16;
		$widtha2 = $widtha + 16;

		echo "<a href='javascript:awindow(\"".$avatar."\", \"\", \"width=".$widtha2.",height=".$heighta2.",scroll=yes\")'>Current Avatar</a>";
		}
		echo "</td></tr><tr><td><b>Signature</b></td><td><textarea name='sig' cols='33' rows='12'>".$sig."</textarea></td></tr>";

	$query2 = "SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`";
	$result2 = mysql_query($query2);
	while($row2 = mysql_fetch_array($result2)) {
		$name = "$row2[name]";

		echo "<tr><td><b>".$name."";
		if ($row2[des]) {
			echo " <a href='javascript:awindow(\"a_a_help.php?id=$row2[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>?</b></a>";
		}
		
		if ($row2[type] == "textarea") {
			echo "</td><td><textarea name='$name' cols=\"40\" rows=\"16\">".$row["$name"]."</textarea></td></tr>";
		} else {
			echo "</td><td><input type=\"text\" name='$name' value='".$row["$name"]."'></td></tr>";
		}
	}
	}
	echo "<tr><td><input type='submit' name='submit' value='Submit Changes'></td></tr></table></form>";
		}
	if ($_POST['submit']) {
	$query = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$_COOKIE[username]."'");
	$rows = mysql_num_rows($query);

	if ($rows == "1") {

	$_POST["sig"] = addslashes($_POST["sig"]);

    $edit2 = "UPDATE onecms_profile SET aim = '".$_POST["aim"]."', msn = '".$_POST["msn"]."', website = '".$_POST["website"]."', nickname = '".$_POST["nickname"]."', location = '".$_POST["location"]."', sig = '".$_POST["sig"]."', avatar = '".$_POST["avatar"]."'";
	
	$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$_POST["$name"] = addslashes($_POST["$name"]);
		if ($_POST["$name"]) {
		$edit2 .= ", ".$name." = '".$_POST["$name"]."'";
		}
	}
	
	$edit2 .= " WHERE username = '".$username."'";

    $edit = mysql_query($edit2) or die(mysql_error());
	} else {
    $edit2 = "INSERT INTO onecms_profile VALUES ('null', '".$_COOKIE[username]."', '".$_POST["aim"]."', '".$_POST["msn"]."', '".$_POST["website"]."', '".$_POST["nickname"]."', '".$_POST["location"]."', '".$_POST["sig"]."', '".$_POST["avatar"]."'";

	   		$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$_POST["$name"] = addslashes($_POST["$name"]);
		if ($_POST["$name"] == "") {
		$edit2 .= ", ''";
		} else {
		$edit2 .= ", '".$_POST["$name"]."'";
		}
	}
$edit2 .= ")";

	$edit = mysql_query($edit2) or die(mysql_error());
	}
   if ($edit == TRUE) {
   echo "Your profile has been updated. Click <a href='members.php?action=profile'>here</a> to return back.";
   }
   }

   } else {

	$query = mysql_query("SELECT * FROM onecms_profile WHERE id = '".$_GET['id']."'");
	while($row = mysql_fetch_array($query)) {
		$aim = "".stripslashes($row[aim])."";
		$msn = "".stripslashes($row[msn])."";
		$website = "".stripslashes($row[website])."";
		$nickname = "".stripslashes($row[nickname])."";
		$location = "".stripslashes($row[location])."";


		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>AIM</b></td><td><a href='aim:goim?screenname=".$aim."&message=Hello+Are+you+there?'>".$aim."</a></td></tr><tr><td><b>MSN</b></td><td>".$msn."</td></tr><tr><td><b>Website</b></td><td><a href='".$website."' target='popup'>".$website."</a></td></tr><tr><td><b>Nickname</b></td><td>".$nickname."</td></tr><tr><td><b>Location</b></td><td>".$location."</td></tr>";
				
	$result2 = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users'");
	while($row2 = mysql_fetch_array($result2)) {
	$name = "$row2[name]";

	echo"<tr><td><b>".$name."</b></td><td>".stripslashes($row["$name"])."</td></tr>";
	}
	
	echo "<tr><td><br><a href='members.php?action=profile&id=".$_GET['id']."&other=posts'>View All posts by ".$row[username]."</a></td><td><br><a href='members.php?action=profile&id=".$_GET['id']."&other=topics'>View All topics by ".$row[username]."</a></td>";
		
		$query = mysql_query("SELECT slist FROM onecms_users WHERE username = '".$row[username]."'");
		$proc = mysql_fetch_row($query);

		if ($proc[0] == "Yes") {
			echo "<td><br><a href='staff.php?user=".$row[username]."'>View All content by ".$row[username]."</a></td>";
		}
		echo "</tr>";

	}
echo "</table>";
}
}

if ($_GET['action'] == "profile") {
if (($_GET['other'] == "posts") && ($_GET['id'])) {
	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>Date Posted</b></td><td><b>Forum Posted at</b></td>";

	$query="SELECT * FROM onecms_posts WHERE uid = '".$_GET['id']."' AND type = 'post'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		$qur = mysql_query("SELECT name FROM onecms_forums WHERE id = '".$row[fid]."'");
		$fet = mysql_fetch_row($qur);
		echo "<tr><td><a href='".$f2part1."".$row[tid]."".$f2part2."#".$row[id]."'>".$row[subject]."</a></td><td>".date($dformat, $row[date])."</td><td><a href='".$f1part1."".$row[fid]."".$f1part2."'><b>".$fet[0]."</b></a></td></tr>";
	}
	$qur2 = mysql_query("SELECT username FROM onecms_profile WHERE id = '".$_GET['id']."'");
	$fet2 = mysql_fetch_row($qur2);

	echo "<tr><td><br><center><a href='members.php?action=profile&id=".$_GET['id']."'>[ <b>Back to ".$fet2[0]."'s profile</b> ]</a></center></td></tr></table>";
}

if (($_GET['other'] == "topics") && ($_GET['id'])) {
	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>Date Posted</b></td><td><b>Forum Posted at</b></td>";

	$query="SELECT * FROM onecms_posts WHERE uid = '".$_GET['id']."' AND type = 'topic'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		$qur = mysql_query("SELECT name FROM onecms_forums WHERE id = '".$row[fid]."'");
		$fet = mysql_fetch_row($qur);
		echo "<tr><td><a href='".$f2part1."".$row[id]."".$f2part2."'>".$row[subject]."</a></td><td>".date($dformat, $row[date])."</td><td><a href='".$f1part1."".$row[fid]."".$f1part2."'><b>".$fet[0]."</b></a></td></tr>";
	}

	$qur2 = mysql_query("SELECT username FROM onecms_profile WHERE id = '".$_GET['id']."'");
	$fet2 = mysql_fetch_row($qur2);

	echo "<tr><td><br><center><a href='members.php?action=profile&id=".$_GET['id']."'>[ <b>Back to ".$fet2[0]."'s profile</b> ]</a></center></td></tr></table>";
}
}

footera();

}
}
}
?>