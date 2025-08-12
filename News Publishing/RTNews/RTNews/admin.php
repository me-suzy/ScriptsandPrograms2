<?php
include("config.php");


$news_error = false;

$db = mysql_connect($db_host, $db_user, $db_password);

if ($db == FALSE)
	die ("Error, could not connect to database, check file config.php");

mysql_select_db($db_name, $db)
	or die ("Error selecting db. Check file config.php");

$query = "SELECT _password FROM RTNews_auth";

$result = mysql_query($query, $db);
$row = mysql_fetch_array($result);
$password = $row['_password'];

session_start();

if (IsSet($_POST['posted_password']) && $password==md5($_POST['posted_password']))
	$_SESSION['user']="admin";

if(IsSet($_POST['remember_pass']) && IsSet($_SESSION['user']))
{
	$cookie=$password;
	setcookie("RT_News_admin",$cookie,time()+1000000);
}

if($_GET['logout']==1)
{
	$_SESSION=array(); 
	session_destroy(); 
	if(IsSet($_COOKIE['RT_News_admin']))
		setcookie("RT_News_admin",$cookie,time());
	header("Location: admin.php"); 
	exit; 
}

if(IsSet($_COOKIE['RT_News_admin']))
{
	$cookie_pass=$_COOKIE['RT_News_admin'];
	if($cookie_pass==$password)
		$_SESSION['user']="admin";
}

if(IsSet($_POST['PHPSESSID']) && !IsSet($_COOKIE['PHPSESSID'])){
	$PHPSESSID=$_POST['PHPSESSID'];
	header("Location: admin.php?PHPSESSID=$PHPSESSID"); 
}

if(!IsSet($_COOKIE['PHPSESSID']) && IsSet($_POST['remember_pass']))
	header("Location: admin.php?nocookie=1");
	
include("news.php");
?>

<HTML>
<HEAD>
</HEAD>
<BODY>
<?php

$PHPSESSID=session_id();

if(!IsSet($_SESSION['user'])){
if($_GET['nocookie']==1) 
	print("Enable cookie if you want to remember your login");

print("<FORM METHOD=POST ACTION=\"admin.php\"><BR>
password:<INPUT TYPE=PASSWORD SIZE=20 NAME=posted_password><BR>
remember login: <INPUT TYPE=CHECKBOX NAME=remember_pass VALUE=1><BR><BR>
<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=\"Logme\"><BR>");

if(!IsSet($_COOKIE['PHPSESSID']))
  print("<INPUT TYPE=HIDDEN NAME=PHPSESSID VALUE=$PHPSESSID>");
print("</FORM>");
}
  else // Login sussesfully done
  {
  if($write_news)
	if	($author != "" && $mail != "" && $text != "")
		new_news();
	else
		$news_error = true;
  news_form();	  
  if (isset($news_to_del))
  	delete_news($news_to_del);
  list_news();
  print '<BR><FORM METHOD=POST ACTION=admin.php><BR>  
  Change password:<INPUT TYPE=PASSWORD SIZE=20 NAME=posted_new_password>
  <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=Change_Password>';
    if (isset($posted_new_password))
  {
  	ch_password($posted_new_password);
	echo "Password updated, please logout";
  }
  print("<BR><BR><A HREF=\"index.php\">return to homepage</A><BR>");
  print("<A HREF=\"admin.php?logout=1\">logout</A><BR>");
  }
  ?>
  
  </BODY>
  </HTML>