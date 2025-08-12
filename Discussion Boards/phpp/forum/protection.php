<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

include "settings.php";

include "common_db.inc";

error_reporting(0);

$link_id = db_connect();
if(!$link_id) die(sql_error());

if(!mysql_select_db($dbname)) die(sql_error());

$connected = 1;

if(!isset($logincookie[last]) && (isset($loginname) || isset($logincookie[user])) && $action != "logout") {
$prot = 1;
include "lastvisit.php";
}

if(isset($logincookie[user]) || isset($loginname)) {
if(isset($logincookie[user])) {
	$loginname = $logincookie[user];

	$pwcheck = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$loginname'");
	for ($i = 0; $i < mysql_num_rows($pwcheck); $i++) {
		$storedpass = mysql_result($pwcheck, $i, "userpassword");
	}

	$thepass = mysql_query("SELECT PASSWORD('$loginpass')");
	$password = mysql_result($thepass, 0);

	if ($action == "logout") {
		Setcookie("logincookie[pwd]","",time() - 172800000);
		Setcookie("logincookie[user]","",time() - 172800000);
		Setcookie("logincookie[last]","",time() - 172800000);
		Setcookie("logincookie[lastv]","",time() - 172800000);
		include "logout.php";
		exit;
	}

}

	elseif(isset($loginname)) {

		$pwcheck = mysql_query("SELECT * FROM ${table_prefix}users WHERE userid='$loginname'");
		for ($i = 0; $i < mysql_num_rows($pwcheck); $i++) {
			$storedpass = mysql_result($pwcheck, $i, "userpassword");
		}

		$thepass = mysql_query("SELECT PASSWORD('$loginpass')");
		$password = mysql_result($thepass, 0);


	if ($action == "login") {

		if (($loginname == "") || ($loginpass == ""))
		{
			include "invalidlogin.php";
			exit;
		}
		else if (strcmp($storedpass,$password) == 0)
		{
			if ($remember == 1) {
			Setcookie("logincookie[pwd]",$password,time() + 172800000);
			Setcookie("logincookie[user]",$loginname,time() + 172800000);
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
			}
			else {
			Setcookie("logincookie[pwd]",$password);
			Setcookie("logincookie[user]",$loginname);
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
			}
		}
		else
		{
			include "invalidlogin.php";
			exit;
		}

	}

}
}

else {
	if (($logincookie[pwd] == "") || ($logincookie[user] == "") || ($logincookie[last] == ""))
	{
		$loginname = "logincookie[user]";
		include "login.php";
		exit;
	}
	else if ($storedpass == $logincookie[pwd])
	{
			if ($remember == 1) {
			Setcookie("logincookie[pwd]",$password,time() + 1728000);
			Setcookie("logincookie[user]",$loginname,time() + 1728000);
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
			}
			else {
			Setcookie("logincookie[pwd]",$password);
			Setcookie("logincookie[user]",$loginname);
			Setcookie("logincookie[last]","$lastvisit");
			Setcookie("logincookie[lastv]","$lastvisitunix");
			}
		$loginname = "$logincookie[user]";
	}
	else
	{
		include "invalidlogin.php";
		exit;
	}
}


?>