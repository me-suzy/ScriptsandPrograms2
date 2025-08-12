<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];


if ($num_rows==1){
	$SID=f_ip2dec($REMOTE_ADDR);
	if (!session_id($SID))
		session_start();
	if (!session_is_registered('signed_in'))
		session_register('signed_in');
	$signed_in = "indeed";
	session_register('uname');
	$uname = $username;
	session_register('pass');
	$pass = $password;

	if ($id=="1"){
		Header( "Location: admin.php");
	} 
	
	else {Header ("Location: index.php?action=1");}
}
else {
     Header( "Location: index.php?action=1");
}

?>
