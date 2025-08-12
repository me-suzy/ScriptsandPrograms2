<?php

include ("config.php");


if($install==true && $admin_pass!="")
{
$db = mysql_connect($db_host, $db_user, $db_password);

if ($db == FALSE)
	die ("Error, could not connect to database, check file config.php");

mysql_select_db($db_name, $db)
	or die ("Error selecting db. Check file config.php");
	
$query = "CREATE TABLE RTGuestbook (id INT (5) UNSIGNED not null AUTO_INCREMENT, _author VARCHAR (50) not null, _mail VARCHAR (50),  _date VARCHAR(10) ,  _text TEXT not null , PRIMARY KEY (id))";


mysql_query($query, $db) 
	or die ("Error, could not create table!");

$query = "CREATE TABLE RTGuestbook_auth (_password VARCHAR (100) not null)";

mysql_query($query, $db)
	or die ("Error, could not create table!");

$enc = md5($admin_pass);
$query = "INSERT INTO RTGuestbook_auth (_password) VALUES ('$enc')";

mysql_query($query, $db)
	or die ("Error, could not create entry!");
	
echo "Installation completed <br>";
echo "please delete this file... <br>";
echo "use admin.php if you want to change the admin password... <br>";

mysql_close($db);

}
?>	

<form name="form1" method="post" action="">
  <p>
    <input type="password" name="admin_pass"> 
  Admin Password</p>
  <p>
    <input type="hidden" name="install" value=true>
    <input type="submit" name="submit" value="Submit">
</p>
</form>

