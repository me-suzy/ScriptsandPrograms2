<?
srand ((double) microtime() * 1000000);
include ("inc.php");
unset($namefailed);
unset($badmail);
$real_name= preg_replace ($disallowed_symbols, "", $HTTP_POST_VARS["form_name"]);
$user_name= preg_replace ($disallowed_symbols, "", $HTTP_POST_VARS["form_user"]);
$e_mail = preg_replace ($mail_disallowed_symbols, "", $HTTP_POST_VARS["form_email"]);
if (strlen($user_name) <5) {
	$namefailed = "Username (5+ letters):";
}
if (strlen($e_mail) <1) {
	$badmail = "E-mail:";
} elseif (!preg_match ("/^\w+((\-|\.)\w+)*@\w+((\-|\.)\w+)*\.[A-z]{2,}$/", $e_mail)) {
	$badmail = "Check e-mail format.";
}
if (strlen($badmail) <2 || strlen($namefailed) < 2) {
	mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error");
	mysql_select_db($db_name);
	$sql_look = "select * from $users_table where username='$user_name' or email='$e_mail'";
	$user_record = mysql_query ($sql_look) or die ("Bad query");
	$num_Rows = mysql_num_rows ($user_record);
	if ($num_Rows > 0) {
		while ($user = mysql_fetch_assoc($user_record)) {
			if ($user["username"] == $user_name) {
				$namefailed = "Username already registered.";
			}
			if ($user["email"] == $e_mail) {
				$badmail = "E-mail address already registered.";
			}
		}
		mysql_close();
	}
}
if (strlen($badmail) >2 || strlen($namefailed) > 2){?>
	<html>
<head>
<style type="text/css">
			body {
				color: #EEEEEE;
				font-family: Verdana;
				background-color: #222299;
				font-size: 13px;
			}
			INPUT {
				color: #FFFF00;
				background : #000088;
				font : bold Verdana;
				font-size : 12px;
				border : inset White;
				border-width : 2px 1px 1px 2px;
				padding-left : 3px;
				padding-top : 2px;
				padding-bottom : 2px;
				text-align : center;
			}
			td {
				color: #EEEEEE;
				font-family: Verdana;
				font-size: 13px;
			}
</style>
<title>Hacker Hanter. Register testing.</title>
</head>
<body>
<table width="90%" height="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td align="center">Enter your Name, e-mail and select username. Please use only letters, numbers and _ symbol.
</td></tr>
<tr><td align="center">
<form method=post><b>
<? if (strlen($namefailed) < 2) {
	echo "Username: <font color=\"#AAFF33\">$user_name</font> <input type=\"hidden\" name=\"form_user\" value=\"$user_name\" size=\"20\">";
} else {
	echo "<font color=\"#FF3333\">$namefailed </font><br><input type=\"text\" name=\"form_user\" value=\"$user_name\"  size=\"20\">";
}
if (strlen($badmail) < 2) {
	echo "<p>Username: <font color=\"#AAFF33\">$e_mail</font> <input type=\"hidden\" name=\"form_email\" value=\"$e_mail\" size=\"20\">";
} else {
	echo "<p><font color=\"#FF3333\">$badmail</font><br><input type=\"text\" name=\"form_email\" value=\"$e_mail\" size=\"20\">";
}
echo "<p>Real name:</b><br><input type=\"text\" name=\"form_name\" value=\"$real_name\" size=\"20\">";
?>
<p><input type="submit" value="Register me!">
</form>
</td>
</tr><tr>
<td height="130" align="right"><img src="hackerHunter_logo.gif" width="120" height="119" border="0"></td>
</tr><tr>
<td height="20"align="center"><b>Web sites protection system &copy; Polar Lights Labs 1994-2002.</b></td></tr>
</table>
</body>
</html>
<?} else {
	if (!$real_name) {
		$real_name = "Registered Member";
	}
	$symbols = array ("B","q","o","0","i","O","s","w","z","b","R","p","P","n","4","G","y","N","8","5","r","H","e","7","m","E","Z","L","u","j","M","Y","d","t","V","W","Q","g","U","c","_","F","X","I","6","a","A","x","J","K","1","T","2","l","f","D","S","h","9","k","3","C","v");
	$tmp_arr = array_rand ($symbols, 8);
	foreach ($tmp_arr as $value) {
		$password.=$symbols[$value];
	}
	$message = "Hello ".$real_name."!\n\n";
	$message .= "Here is your login information for $protected_url\n\n";
	$message .= "Your username is: $user_name";
	$message .= "\nYour password is: $password\n\n";
	$message .= "Please keep your password in safe place.\n\n";
	$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
	$headers = "From: Web Sites Protection System<$webmaster_mail>\n";
	$headers .= "X-Sender: <$webmaster_mail>\n"; 
	$headers .= "X-Mailer: PHP\n";
	$headers .= "X-Priority: 1\n";
	$headers .= "Return-Path: <$webmaster_mail>\n";  
	mail ($e_mail,"Welcome!",$message,$headers);
	$sql="insert into $users_table (username,password,email,real_name,dDate) values ('$user_name','".md5($password)."','$e_mail','$real_name',".time().")";
	mysql_query ($sql) or die ("Bad query");
	header ("Location: $login_page");
}
?>