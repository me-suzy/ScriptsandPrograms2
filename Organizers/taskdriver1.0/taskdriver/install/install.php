<?php

include '../config.php';
$connection = mysql_connect($hostname, $user, $pass)or die(mysql_error());
$db = mysql_select_db($database, $connection)or die(mysql_error());


echo "<table class=\"black\" align=\"center\" width=\"40%\"><tr><td align=\"center\"><img src=\"taskdriverinstall.jpg\"><br><br><br></td></tr>";


/* Creation of user table. */
echo "<tr><td><font size=2 face=arial>Creating user table in database......";
$query = "CREATE TABLE `$userstable` (username VARCHAR(255),email VARCHAR(255),password VARCHAR(255),realname VARCHAR(255),location VARCHAR(255),workphone VARCHAR(255),department VARCHAR(255),userlevel varchar(5) default '2',vcode VARCHAR(255), PRIMARY KEY (email))";
if(mysql_query($query)){
echo "<b> DONE.</b><br><br></font></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial><b>Error, the $userstable table was not created. Please try again.</b><br><br></font></td></tr>";
}

/* Creation of $cattable table. */
echo "<tr><td><font size=2 face=arial>Creating $cattable table in database......";
$query = "CREATE TABLE `$cattable` (catid int(11) NOT NULL auto_increment,catname varchar(255),taskid varchar(11),PRIMARY KEY  (catid)) ";
if(mysql_query($query)){
echo "<b> DONE.</b><br><br></font></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial>Error, the $cattable table was not created. Please try again.</b><br><br></font></td></tr>";
}

/* Creation of $taskstable table. */
echo "<tr><td><font size=2 face=arial>Creating tasks table in database......";
$query = "CREATE TABLE $taskstable (taskid mediumint(9) NOT NULL auto_increment, priority tinyint(4) NOT NULL default '0', title varchar(255) NOT NULL default '', description varchar(255) NOT NULL default '', open_date date default NULL, last_change timestamp(14) NOT NULL, deadline date NOT NULL default '0000-00-00', statusname varchar(15) NOT NULL default 'Received', catname varchar(100) NOT NULL default '', status varchar(5) NOT NULL default '0%', display char(1) NOT NULL default 'Y', personnel varchar(255) NOT NULL default '',manager varchar(255) NOT NULL, PRIMARY KEY  (taskid), KEY description (description),
  KEY open_date (open_date), KEY deadline (deadline), KEY catname (catname), KEY personnel (personnel))";
if(mysql_query($query)){
echo "<b> DONE.</b><br><br></font></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial>Error, the $taskstable table was not created. Please try again.</b><br><br></font></td></tr>";
}

/* Creation of history table. */
echo "<tr><td><font size=2 face=arial>Creating history table in database......";
$query = "CREATE TABLE `$historytable` (histid int(11) NOT NULL auto_increment,taskid varchar(255), notes text, time_stamp timestamp(14),PRIMARY KEY  (histid))";
if(mysql_query($query)){
echo "<b> DONE.</b><br><br></font></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial>Error, the history table was not created. Please try again.</b></font><br><br></td></tr>";
}


/* Inserting admin username and password into $userstable. */
echo "<tr><td><font size=2 face=arial>Inserting admin username and password into database......";
$query3 = "INSERT INTO `$userstable` (username,password,email,userlevel) VALUES ('admin',md5('admin'),'$AdminEmail','A')";
if(mysql_query($query3)){
echo "<b> DONE.</b><br><br></font></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial>Error, the admin data was not inserted into the $userstable table. Please try again.</b></font><br><br></td></tr>";
}

/* Inserting testuser username and password into $userstable. */
echo "<tr><td><font size=2 face=arial>Inserting testuser username and password into database......";
$query4 = "INSERT INTO `$userstable` (realname,username,password,email,userlevel) VALUES ('Bob Tester','testuser',md5('testuser'),'testuser@uremail.com','2')";
if(mysql_query($query4)){
echo "<b> DONE.</b><br><br></font><font color=\"FF0000\" face=\"arial\" size=\"4\">All tables have been created successfully!<br><br> <b>Please remove the install file from the server.</font></b><br><br><br><br><center><font size=2 face=arial><b><a href=\"http://" . $domain . $directory . "index.php\">Click here to access TaskDriver</a></b></font></center></td></tr>";
} else {
echo "<br><b><font color=\"FF0000\" face=arial>Error, data was not entered into the database. Please try again.</b></font><br><br></td></tr>";
}

echo "</table>";
?>