<?php

if($_GET[p]==logout){
setcookie ("username", "none", time() - 3600);
setcookie ("pass", "none", time() - 3600);
setcookie ("username");
setcookie ("pass");
setcookie ("username");
setcookie ("pass");
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