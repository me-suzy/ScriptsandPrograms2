<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
if(!isset($loginname)) {
$loginname = $logincookie[user];
}

$userdetails = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$loginname'");
$usernumber = mysql_result($userdetails, 0, "usernumber");
$userpassword = mysql_result($userdetails, 0, "userpassword");
$username = mysql_result($userdetails, 0, "username");
$usercountry = mysql_result($userdetails, 0, "usercountry");
$useremail = mysql_result($userdetails, 0, "useremail");
$userprofile = mysql_result($userdetails, 0, "userprofile");
$registerdate = mysql_result($userdetails, 0, "registerdate");
$usermsn = mysql_result($userdetails, 0, "usermsn");
$useraol = mysql_result($userdetails, 0, "useraol");
$usericq = mysql_result($userdetails, 0, "usericq");
$useryahoo = mysql_result($userdetails, 0, "useryahoo");
$userhomepage = mysql_result($userdetails, 0, "userhomepage");
$usersig = mysql_result($userdetails, 0, "usersig");
$userdob = mysql_result($userdetails, 0, "userdob");
$usersex = mysql_result($userdetails, 0, "usersex");
$dispemail = mysql_result($userdetails, 0, "dispemail");
$imgsig = mysql_result($userdetails, 0, "imgsig");
$pmnotify = mysql_result($userdetails, 0, "pmnotify");
$timezone = mysql_result($userdetails, 0, "timezone");

$usersig = str_replace("'", "\'", $usersig);
$usersig = str_replace('"', '\"', $usersig);
$userprofile = str_replace("'", "\'", $userprofile);
$userprofile = str_replace('"', '\"', $userprofile);

$lastvisit = mysql_result($userdetails, 0, "lastaccesstime");

$result = mysql_query("SELECT UNIX_TIMESTAMP(lastaccesstime) as epoch_time FROM ${table_prefix}users WHERE userid='$loginname'"); 
$lastvisitunix = mysql_result($result, 0, 0);

$users_tablename = "${table_prefix}users";

$users_table_def = "'$usernumber', '$loginname', '$userpassword', '$username', '$usercountry', '$useremail', '$userprofile', '$registerdate', now(), '$usermsn', '$useraol', '$usericq', '$useryahoo', '$userhomepage', '$usersig', '$userdob', '$usersex', '$dispemail', '$imgsig', '$pmnotify', '$timezone'";

if(!mysql_query("REPLACE INTO $users_tablename VALUES($users_table_def)")) die(sql_error());

if($mark == "read") {
$lastvisit = date("YmdHis", time());
$lastvisitunix = time();
if($prot != 1) {
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
}
}
else {
if($prot != 1) {
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
}
}

?>