<?
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


IF (isset($HTTP_POST_VARS[run])) {
//this is where the username and pass and grades are thrown into the database.	

//create password
$HTTP_POST_VARS['pass'] = MD5($HTTP_POST_VARS['pass']);

//throw the data into tables
mysql_query("INSERT INTO ".$conf['tbl']['teachers']."  (user, pass, email, name, level) VALUES
('".addslash($HTTP_POST_VARS[user])."','$HTTP_POST_VARS[pass]','$HTTP_POST_VARS[email]','Admin','1')");


IF(isset($HTTP_POST_VARS[check0])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']." (grades) VALUES ('".addslash($HTTP_POST_VARS[check0])."')"); }
IF(isset($HTTP_POST_VARS[check1])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check1])."')"); }
IF(isset($HTTP_POST_VARS[check2])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check2])."')"); }
IF(isset($HTTP_POST_VARS[check3])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check3])."')"); }
IF(isset($HTTP_POST_VARS[check4])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."   (grades) VALUES ('".addslash($HTTP_POST_VARS[check4])."')"); }
IF(isset($HTTP_POST_VARS[check5])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check5])."')"); }
IF(isset($HTTP_POST_VARS[check6])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check6])."')"); }
IF(isset($HTTP_POST_VARS[check7])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check7])."')"); }
IF(isset($HTTP_POST_VARS[check8])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check8])."')"); }
IF(isset($HTTP_POST_VARS[check9])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check9])."')"); }
IF(isset($HTTP_POST_VARS[check10])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check10])."')"); }
IF(isset($HTTP_POST_VARS[check11])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check11])."')"); }
IF(isset($HTTP_POST_VARS[check12])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[check12])."')"); }

IF(!empty($HTTP_POST_VARS[cust1])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."   (grades) VALUES ('".addslash($HTTP_POST_VARS[cust1])."')"); }
IF(!empty($HTTP_POST_VARS[cust2])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']." (grades)   VALUES ('".addslash($HTTP_POST_VARS[cust2])."')"); }
IF(!empty($HTTP_POST_VARS[cust3])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[cust3])."')"); }
IF(!empty($HTTP_POST_VARS[cust4])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[cust4])."')"); }
IF(!empty($HTTP_POST_VARS[cust5])) {
mysql_query("INSERT INTO ".$conf['tbl']['grades']."  (grades)  VALUES ('".addslash($HTTP_POST_VARS[cust5])."')"); }

  @unlink("update.php");

$mail_to = "jeff@digital-scribe.org";
$mail_subject = "DS Installed";
$mail_body = "Congrats! \r\n";
$mail_body .= "Server URL: $_SERVER_VARS[SCRIPT_URI] \r\n Server Sig: $_SERVER[SERVER_SIGNATURE] \r\n Server Name: $_SERVER[SERVER_NAME] \r\n File Path: $_SERVER[SCRIPT_FILENAME] \r\n Server: $_SERVER[HTTP_HOST] \r\n";

$headers  = '';
  $headers  .= "Content-Type: text/plain \r\n";
  $headers  .= "Date: ". date('r'). " \r\n";
  $headers  .= "Return-Path: jeff@digital-scribe.org \r\n";
  $headers  .= "From: jeff@digital-scribe.org \r\n";
  $headers  .= "Sender: jeff@digital-scribe.org \r\n";
  $headers  .= "Reply-To: jeff@digital-scribe.org \r\n";
  $headers  .= "Organization: Digital Scribe \r\n";
  $headers  .= "X-Sender: jeff@digital-scribe.org \r\n";
  $headers  .= "X-Priority: 3 \r\n";
  $headers  .= "X-Mailer: php\r\n";

@mail($mail_to, $mail_subject, $mail_body, $headers);




	echo "<P>You are done!  The Digital Scribe is installed.<P>Please delete this file.<P>You may login <A HREF=login.php>here</A>.";
	exit;
}

//create tables


mysql_query("CREATE TABLE ".$conf['tbl']['teachers']." (
user VARCHAR (20) not null,
pass CHAR (32) BINARY not null,
name VARCHAR (20),
ID INT (11) not null AUTO_INCREMENT,
email VARCHAR (255),
filename VARCHAR (255),
grade VARCHAR (20),
level CHAR(1),
PRIMARY KEY (ID))")OR DIE
("There is a problem with creating table ".$conf['tbl']['teachers']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['teachers']." created.";


mysql_query("CREATE TABLE ".$conf['tbl']['studentwork']." (
stufirstname VARCHAR (40),
stulastname VARCHAR (40),
title VARCHAR (255),
stuwork text,
teacher VARCHAR (50),
active VARCHAR (30),
project VARCHAR (255),
id INT (11) not null AUTO_INCREMENT,
filename VARCHAR (50),
TID INT (11) NOT NULL,
PRIMARY KEY (id))")OR DIE
("There is a problem with creating table ".$conf['tbl']['studentwork']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['studentwork']." created.";


mysql_query("CREATE TABLE ".$conf['tbl']['projecttable']." (
project VARCHAR (255),
teachername VARCHAR (20),
description text,
publish VARCHAR(50),
grade VARCHAR (50),
teacheruser VARCHAR (20),
imagename VARCHAR (20),
date VARCHAR (100),
archive CHAR(3),
TID INT (11) NOT NULL,
ID INT (11) not null AUTO_INCREMENT,
PRIMARY KEY (ID))")OR DIE
("There is a problem with creating table ".$conf['tbl']['projecttable']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['projecttable']." created.";


mysql_query("CREATE TABLE ".$conf['tbl']['grades']." (
SID INT (11) not null AUTO_INCREMENT,
PRIMARY KEY (SID),
grades varchar(255))")OR DIE
("There is a problem with creating table ".$conf['tbl']['grades']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['grades']." created.";


mysql_query("CREATE TABLE ".$conf['tbl']['announcements']." (
   title varchar(255) NOT NULL,
   announcement text NOT NULL,
   author varchar(255) NOT NULL,
   a_id int(5) NOT NULL auto_increment,
   date varchar(255) NOT NULL,
   PRIMARY KEY (a_id))")OR DIE
("There is a problem with creating table ".$conf['tbl']['announcements']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['announcements']." created.";


mysql_query("CREATE TABLE ".$conf['tbl']['homework']." (
   title varchar(255) NOT NULL,
   work text NOT NULL,
   proj_id int(5) DEFAULT '0' NOT NULL,
   hw_id int(5) NOT NULL auto_increment,
   t_name varchar(255) NOT NULL,
   t_user varchar(255) NOT NULL,
   month_due varchar(10) NOT NULL,
   day_due int(2) DEFAULT '0' NOT NULL,
   PRIMARY KEY (hw_id))")OR DIE
("There is a problem with creating table ".$conf['tbl']['homework']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['homework']." created.";

mysql_query("CREATE TABLE ".$conf['tbl']['projecthomework']." (
   teach_id int(5) DEFAULT '0' NOT NULL,
   proj_title varchar(255) NOT NULL,
   proj_desc text NOT NULL,
   live char(1) NOT NULL,
   proj_ID int(5) NOT NULL auto_increment,
   t_name varchar(255) NOT NULL,
   grade varchar(255) NOT NULL,
   t_user varchar(255) NOT NULL,
   PRIMARY KEY (proj_ID))")OR DIE
("There is a problem with creating table ".$conf['tbl']['projecthomework']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['projecthomework']." created.";


echo "<P><B>All tables have been created.</B>";

@chmod("header1.php", 0666);
@chmod("header2.php", 0666);
@chmod("footer.php", 0666);
if(!@chmod("style.css", 0666)) {
echo "<P><B>In order to fully use the Digital Scribe you must set the file permissions to writable (chmod 666) for the following files:</B><BR>header1.php<BR>header2.php<BR>footer.php<BR>style.css";

}

echo "<P>Please create a username, password, and e-mail address for administration.";
?>

<BR><FORM METHOD=POST ACTION=run_first.php>

<BR>Username: <INPUT TYPE=TEXT NAME=user>
<BR>Password: <INPUT TYPE=TEXT NAME=pass>
<BR>E-mail Address: <INPUT TYPE=TEXT NAME=email>

<P>Please select what grades your school has:

<BR><INPUT TYPE=CHECKBOX NAME=check0 VALUE="Kindergarten">Kindergarten
<BR><INPUT TYPE=CHECKBOX NAME=check1 VALUE="1st Grade">1st Grade
<BR><INPUT TYPE=CHECKBOX NAME=check2 VALUE="2nd Grade">2nd Grade
<BR><INPUT TYPE=CHECKBOX NAME=check3 VALUE="3rd Grade">3rd Grade
<BR><INPUT TYPE=CHECKBOX NAME=check4 VALUE="4th Grade">4th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check5 VALUE="5th Grade">5th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check6 VALUE="6th Grade">6th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check7 VALUE="7th Grade">7th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check8 VALUE="8th Grade">8th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check9 VALUE="9th Grade">9th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check10 VALUE="10th Grade">10th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check11 VALUE="11th Grade">11th Grade
<BR><INPUT TYPE=CHECKBOX NAME=check12 VALUE="12th Grade">12th Grade
<P><INPUT TYPE=TEXT NAME=cust1 COLS=25 VALUE=""> Custom Grade
<BR><INPUT TYPE=TEXT NAME=cust2 COLS=25 VALUE=""> Custom Grade
<BR><INPUT TYPE=TEXT NAME=cust3 COLS=25 VALUE=""> Custom Grade
<BR><INPUT TYPE=TEXT NAME=cust4 COLS=25 VALUE=""> Custom Grade
<BR><INPUT TYPE=TEXT NAME=cust5 COLS=25 VALUE=""> Custom Grade
<BR>If you have additional custom grades you can submit them later.
<BR><INPUT TYPE=submit NAME=run VALUE="Continue">
<?



mysql_close(); //close connection
?>