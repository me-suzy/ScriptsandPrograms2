<?php
if($_GET[p]==login){
	if($_GET[login]==yes){
		$username=$_POST['username'];
		$pass=$_POST['pass'];
		if($md5pass==1){
			$pass=md5($pass);
		}
		if($username&&$pass){
			if($_POST[remember]){
					setcookie("username",$username,time()+10000000);
					setcookie("pass",$pass,time()+10000000);
			}else{
					setcookie("username",$username);
					setcookie("pass",$pass);
			}
		}
	}
}

if($_GET[p]==logout){
setcookie ("username", "none", time() - 3600);
setcookie ("pass", "none", time() - 3600);
}



$username=$_COOKIE[username];
$pass=$_COOKIE[pass];
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
if(empty($user[username])||!$user[username]||!isset($user[username])){
$username="guest";
$pass="guest";
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
}

$stat = mysql_fetch_array(mysql_query("select * from characters where id='$user[activechar]'"));