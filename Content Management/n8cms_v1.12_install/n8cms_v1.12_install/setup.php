<? 
error_reporting(E_ALL &~E_NOTICE &~E_WARNING);
/*
this set up file is for n8cms_v1.0 
// this  will only create/populate the MySql database.
//creates the following tables
//home
//comment
//users
//populates home with and index page and users with 4 default users levels 1,2,3,4
*/
echo" Welcome to n8's cms v1.01 set up.<br> If you have already set up the program you should delete this file and fwrite.php from your web directory<br>the following info is part of the config array in _.php<br>"; 

//check for connection, if _.php < 1kb then run fwrite.php
//include ('html/setup_form.html');

$file_x=readfile('_.php');
//echo "file_x size= ".$file_x."<br> \r";
if ($file_x < 200){include ('html/setup_form.html');}
if ($file_x==179){echo'';}
if ($file_x > 350){include ('_.php') or exit(mysql_error());
$setup=0;
}

// if connection info is wirtten to _.php then create tables
@ $connect=mysql_select_db(DB_NAME);

//first see if the database exists if not create it
$setup=$_GET[setup];
if ( $file_x > 180 )

	{
	include('_.php');
	echo"<br>Could not connect to database. Assuming set up and installing SQL data<br>";
	echo"<a href='?setup=1'>Make Database<br>Database name=".DB_NAME."</a>";
}

if ($setup==1){
	$mk_db=mysql_query("CREATE DATABASE `".DB_NAME."`");
	echo "<br>".DB_NAME." created, next to populate<br>";
	echo"<a href='?setup=2'>create tables</a>";
}
if ($setup==2){
$setup_query0="CREATE TABLE comment (
  com_id int(6) NOT NULL auto_increment,
  dir varchar(20) NOT NULL default '',
  page_id int(6) NOT NULL default '0',
  sender_name varchar(16) NOT NULL default '',
  comment_ip varchar(16) NOT NULL default '',
  comment_text longtext NOT NULL,
  datetime varchar(20) NOT NULL default '',
  KEY com_id (com_id)
) TYPE=MyISAM;
    ";
$setup_query01="CREATE TABLE banned_ip (
  com_id int(6) NOT NULL auto_increment,
  dir varchar(20) NOT NULL default '',
  page_id int(6) NOT NULL default '0',
  sender_name varchar(16) NOT NULL default '',
  comment_ip varchar(16) NOT NULL default '',
  comment_text longtext NOT NULL,
  datetime varchar(20) NOT NULL default '',
  KEY com_id (com_id)
) TYPE=MyISAM;
    ";

$setup_query1="CREATE TABLE home (
  page_id int(6) unsigned NOT NULL auto_increment,
  rec_crt varchar(60) NOT NULL default '0000-00-00 00:00:00',
  rec_edit datetime NOT NULL default '0000-00-00 00:00:00',
  auth_id tinyint(4) NOT NULL default '0',
  isactive tinyint(1) NOT NULL default '0',
  pg_title tinytext NOT NULL,
  hits tinyint(4) NOT NULL default '0',
  admin_lvl tinyint(4) NOT NULL default '0',
  content text NOT NULL,
  rec_expire date NOT NULL default '0000-00-00',
  PRIMARY KEY  (page_id)
) TYPE=MyISAM ;
";
$setup_query2="CREATE TABLE users (
  userid int(25) unsigned NOT NULL auto_increment,
  first_name varchar(25) NOT NULL default '',
  last_name varchar(37) NOT NULL default '',
  email_address varchar(25) NOT NULL default '',
  username varchar(25) NOT NULL default '',
  PASSWORD varchar(255) NOT NULL default '',
  info text NOT NULL,
  user_level enum('0','1','2','3','4') NOT NULL default '0',
  signup_date datetime NOT NULL default '0000-00-00 00:00:00',
  last_login datetime NOT NULL default '0000-00-00 00:00:00',
  activated enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (userid)
) TYPE=MyISAM;
";
$datetime = date("Y-m-d h:i");
$lt_date = date ("Ymd");

$setup_query3="INSERT INTO `users` VALUES (9, 'N8', 'r0x', 'hillbillyfunk@gmail.com', 'n8r0x', '4ce49794777e274413a0470d465dead6', 'N8CMS Creator', '4', '2004-04-20 16:20:00', '2004-04-20 16:20:00', '1');";

$setup_query5="INSERT INTO `users` VALUES (11, 'default', 'User', 'none@noway.com', 'setup', 'c21f969b5f03d33d43e04f8f136e7682', 'Setup admin', '4', '2005-07-28 12:22:48', '2005-07-28 12:23:04', '1'); ";
$setup_query6="INSERT INTO `home` VALUES (1, '".$lt_date."', '".$datetime."', 9, 0, 'index', 0, 4, '<p>setup succesful!</p>you may login by pressing ALT - L at the same time  then enter, <br><b>user=setup</b><br><b>pass=default</b><br>', '2004-04-20');";


//user setup pass=default
mysql_query($setup_query0) or mysql_error();
echo"<br>setup_query 0 ,CREATE TABLE comments: pass<br>";
mysql_query($setup_query01) or mysql_error();
echo"<br>setup_query 0 ,CREATE TABLE banned_ip: pass<br>";
mysql_query($setup_query1) or mysql_error();
echo"setup_query 1 ,CREATE TABLE home: pass<br>";
mysql_query($setup_query2) or mysql_error();
echo"setup_query 2 ,CREATE TABLE users :pass <br>";
mysql_query($setup_query3) or mysql_error();
echo"setup_query 3 INSERT INTO users: pass<br>";
//mysql_query($setup_query4) or mysql_error();
//echo"setup_query 4 INSERT INTO users: pass<br>";
mysql_query($setup_query5) or mysql_error();
//echo"setup_query 5 INSERT INTO users: pass<br>";
mysql_query($setup_query6) or mysql_error();
echo"setup_query 5 INSERT INTO home: pass<br>";
unlink('fwrite.php');
echo "<h4>Mysql structure Created</h4>Default users added<br><a href=index.php>see if it worked</a>";

}
?>	