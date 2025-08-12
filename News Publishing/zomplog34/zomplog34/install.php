<?
ob_start();
?>
<style type="text/css">
<!--
body {
	background-image: url(admin/images/back.gif);
}
-->
</style><table width="10"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="text">
  <tr>
    <td><img src="admin/images/spacer.gif" width="15" height="15"></td>
    <td>&nbsp;</td>
    <td><img src="admin/images/spacer.gif" width="15" height="15"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="admin/images/head.jpg" width="750" height="132"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>welcome to zomplog. this installer will take you through the necessary steps. </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="admin/images/head_bottom.jpg" width="750" height="11"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php

include('admin/functions.php');
include('admin/config.php');
include('admin/session.php');

checkLoggedIn("no");

?>
<link rel="stylesheet" href="admin/style.css" type="text/css" />
<?

/* Written by Gerben Schmidt, http://scripts.zomp.nl */

if(!$_POST["submit"]){

echo "<br /><div class='title'>Step 1: Creating database tables</div>";

$query = "CREATE TABLE $table (
     id int (10 )  NOT NULL auto_increment ,
     title varchar (100)  NOT NULL ,
     text text NOT NULL ,
     extended text NOT NULL ,
     image text NOT NULL ,

	 imagewidth text NOT NULL ,
	 imageheight text NOT NULL ,
	 
	 fullwidth int (10) NOT NULL ,
	 mediafile varchar (100)  NOT NULL ,
	 mediatype int (10) NOT NULL ,
	 
	 catid int (10) NOT NULL ,
	 date varchar(25) NOT NULL default '',
	 username text NOT NULL ,
	 userid int (10 ) NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br><div class='text'>creating table <b>$table</b>... ";
$q = mysql_query($query) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";


$query2 = "CREATE TABLE $table_comments (
     id int (10 )  NOT NULL auto_increment ,
     entry_id int (10 )  NOT NULL ,
     name varchar (100 )  NOT NULL ,
     comment text NOT NULL ,
	 date varchar(25) NOT NULL default '',
	 ip varchar(25) NOT NULL,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_comments</b>... ";
$q2 = mysql_query($query2) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";


$query3 = "CREATE TABLE $table_users (
  id int(5) NOT NULL auto_increment,
  login varchar(15) default 0,
  password varchar(15) default 0,
  admin int(10) NOT NULL default 0,
  name text NOT NULL,
  email text NOT NULL,
  about text NOT NULL,
  PRIMARY KEY  (id)
)";

echo "<br>creating table <b>$table_users</b>...";
$q3 = mysql_query($query3) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";


$query4 = "CREATE TABLE $table_cat (
  id int(5) NOT NULL auto_increment,
  name text NOT NULL,
  permissions text NOT NULL,
  PRIMARY KEY  (id)
)";

echo "<br>creating table <b>$table_cat</b>... ";
$q4 = mysql_query($query4) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";


$query5 = "CREATE TABLE $table_settings (
  name text NOT NULL,
  weblog_title text NOT NULL,
  image int(11) NOT NULL default 0,
  comments int(11) NOT NULL default 0,
  categories int(11) NOT NULL default 0,
  pages int(11) NOT NULL default 0,
  max int(11) NOT NULL default 0,
  scroll int(15) NOT NULL default 0,
  date text NOT NULL,
  skin text NOT NULL,
  language text NOT NULL,
  use_upload int(15) NOT NULL default 0,
  max_upload int(50) NOT NULL default 0,
  
  use_mediafile int(50) NOT NULL default 0,
  admin_welcome text NOT NULL,
  site_welcome text NOT NULL,
  img_width text NOT NULL,
  img_fullwidth text NOT NULL,
  use_join int(50) NOT NULL default 0,
  PRIMARY KEY  (image)
)";

$query6 = "INSERT INTO $table_settings (name, image, comments, categories, max, scroll, date, skin, language, use_upload, max_upload, use_mediafile, admin_welcome, img_width, img_fullwidth) VALUES ('default', '1', '1', '1', '5', '5', 'm d Y, G:i', 'default', 'english', '1', '300000', '1', 'Welcome to the Zomplog Dashboard!', '150', '450')";

echo "<br>creating table <b>$table_settings</b>... ";
$q5 = mysql_query($query5) or die (mysql_error()); 
$q6 = mysql_query($query6) or die (mysql_error()); 
echo "<div class='good'>succeeded</div>";

$query7 = "CREATE TABLE $table_moblog (
  id int(11) NOT NULL auto_increment,
  email varchar(50) NOT NULL default 0,
  server varchar(150) NOT NULL default 0,
  user varchar(50) NOT NULL default 0,
  password varchar(50) NOT NULL default 0,
  shared text NOT NULL,
  use_moblog int(15) NOT NULL default 0,
  PRIMARY KEY  (id)
)";

$query8 = "INSERT INTO $table_moblog (id, email, server, user, password, shared, use_moblog) VALUES ('1', 'name@email.com', 'localhost', 'name', 'password', 'FALSE', '0')";

echo "<br>creating table <b>$table_moblog</b>... ";
$q7 = mysql_query($query7) or die (mysql_error());
$q8 = mysql_query($query8) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

$query9 = "CREATE TABLE $table_pages (
     id int (10 )  NOT NULL auto_increment ,
     title varchar (100 )  NOT NULL ,
     text text NOT NULL ,
	 use_form int(15) NOT NULL default 0,
	 form_email varchar (100 )  NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_pages</b>... ";
$q9 = mysql_query($query9) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

$query10 = "CREATE TABLE $table_banned (
     id int (10 )  NOT NULL auto_increment ,
     ip varchar (100 )  NOT NULL ,
     PRIMARY KEY  (id )
)";

echo "<br>creating table <b>$table_banned</b>... ";
$q10 = mysql_query($query10) or die (mysql_error());
echo "<div class='good'>succeeded</div>";

   /* Closing connection */
    mysql_close($link);
}	

echo "<br />";	

if($_POST["submit"]){
	
	field_validator("login name", $_POST["login"], "alphanumeric", 4, 15);
	field_validator("password", $_POST["password"], "string", 4, 15);
	field_validator("confirmation password", $_POST["password2"], "string", 4, 15);
	
	
	if(strcmp($_POST["password"], $_POST["password2"])) {
		
		$messages[]="Your passwords did not match";
	}

	
	$query="SELECT login FROM $table_users WHERE login='".$_POST["login"]."'";
	
	
	$result=mysql_query($query, $link) or die("MySQL query $query failed.  Error if any: ".mysql_error());
	

	if( ($row=mysql_fetch_array($result)) ){
		$messages[]="Login ID \"".$_POST["login"]."\" already exists.  Try another.";
	}

	if(!$_POST[weblog_title])
	{
	$messages[]="Please enter a title for your weblog";
	}

	
	if(empty($messages)) {
	
	$query="UPDATE $table_settings SET weblog_title = '$_POST[weblog_title]'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());

		
		newUser();
		
		// Zomplog phone home
		$server = $_SERVER['HTTP_HOST'];
		$referer = $_SERVER['HTTP_REFERER'];
		$adress = $_SERVER['REMOTE_ADDR'];
		$name = $_SERVER['SERVER_NAME'];
		$software = $_SERVER['SERVER_SOFTWARE'];
		$body = "New Zomplog installation!\n\n $server, $referer, $adress, $name, $software";
		mail("zomplog@zomp.nl", "Zomplog 3.4 installation $name", "$body", "From: zomplog@zomp.nl");
		
		
		cleanMemberSession($_POST["login"], $_POST["password"]);

		
		header("Location: admin/members.php?".session_name()."=".session_id());
		ob_end_flush();

	}
}

if(!empty($messages)){
	displayErrors($messages);
}

?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
  <table border="0" cellspacing="0" class="text">
   <tr> 
      <td colspan="2" class="title">Step 2: initiate weblog</td>
    </tr>
  <tr> 
      <td>Name of your weblog:</td>
      <td><input type="text" name="weblog_title" value="<?php print $_POST["weblog_title"] ?>" maxlength="20"></td>
    </tr>
	<tr> 
      <td colspan="2" class="title">&nbsp;</td>
    </tr>
	<tr> 
      <td colspan="2" class="title">Step 3: create an administrator</td>
    </tr>
    <tr> 
      <td>Username:</td>
      <td><input type="text" name="login" value="<?php print $_POST["login"] ?>" maxlength="15"></td>
    </tr>
    <tr> 
      <td>Password:</td>
      <td><input type="password" name="password" value="" maxlength="15"></td>
    </tr>
    <tr> 
      <td>Confirm password:</td>
      <td><input type="password" name="password2" value="" maxlength="15"></td>
    </tr>
    <tr> 
      <td><input name="admin" type="hidden" id="admin" value="1"></td>
      <td><input name="submit" type="submit" value="Submit"></td>
    </tr>
  </table>
</form></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
