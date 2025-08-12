<?php
/*
	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
include('com/db.php');
include('inc/redir.php');
include('inc/auth.php');
$ip=$_SERVER['REMOTE_ADDR'];
$date = date("g:i a, j F Y");
$time = explode(' ', microtime());
$post_time = $time[1];

if($_GET["install"]==Install) {
mysql_query("CREATE TABLE ".$so_prefix."_main ( 
    id int(9) NOT NULL auto_increment,
    catid int(9) NOT NULL,
    obj_name VARCHAR(20) NOT NULL,
    author VARCHAR(50) NOT NULL, 
    email VARCHAR(50) NOT NULL,
    comment TEXT NOT NULL,
    date VARCHAR(40) NOT NULL,
    ip VARCHAR(20) NOT NULL,
    post_time int(20) NOT NULL,
    PRIMARY KEY (id))") or die (smsg("Sorry, could not create table >>> " . mysql_error())); 

mysql_query("CREATE TABLE ".$so_prefix."_obj ( 
    catid int(9) NOT NULL auto_increment,
    obj_name VARCHAR(20) NOT NULL,
    UNIQUE (obj_name),
    PRIMARY KEY (catid))"); 

mysql_query("CREATE TABLE ".$so_prefix."_bannedip ( 
    id int(9) NOT NULL auto_increment,
    bannedip VARCHAR(20),
    bandate VARCHAR(40),
    PRIMARY KEY (id))"); 

mysql_query("CREATE TABLE ".$so_prefix."_smilies ( 
    id int(9) NOT NULL auto_increment,
    code VARCHAR(10),
    smilie VARCHAR(100),
    PRIMARY KEY (id))");

mysql_query("INSERT INTO ".$so_prefix."_main (catid,obj_name,author,email,comment,date,ip,post_time) 
    VALUES ('1','main','Author','dirmass [ at ] gmail [ dot ] com','Hello World! This is a test comment to ensure that the script is functioning properly. You may delete this at any time using the admin panel.','$date','$ip','$post_time')")
     or die (smsg("Sorry, could not add data to table >>> " . mysql_error()));

mysql_query("INSERT INTO ".$so_prefix."_obj (obj_name) VALUES ('main')")
     or die (smsg("Sorry, could not add data to table >>> " . mysql_error()));

mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(':)','<img src=\"$fullurl/smilies/smile.gif\" alt=\"\" border=\"0\" />')");
mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(':P','<img src=\"$fullurl/smilies/tongue.gif\" alt=\"\" border=\"0\" />')");
mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(':D','<img src=\"$fullurl/smilies/grin.gif\" alt=\"\" border=\"0\" />')");
mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(':(','<img src=\"$fullurl/smilies/sad.gif\" alt=\"\" border=\"0\" />')");
mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(':lol:','<img src=\"$fullurl/smilies/lol.gif\" alt=\"\" border=\"0\" />')");
mysql_query("INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES(';)','<img src=\"$fullurl/smilies/wink.gif\" alt=\"\" border=\"0\" />')")
     or die (smsg("Sorry, could not add data to table >>> " . mysql_error()));

smsg("Tables created successfully!<br /><br /> Please delete this file (<b>setup.php</b>) from the SayOp Directory.<br /><br /><a href='admin.php'>Click here</a> to login and access the control panel.");
} else {
echo "
<!DOCTYPE html
PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
   <head>
      <title>SayOp Installation</title>
      <link rel='stylesheet' type='text/css' href='style_1.css' />
      <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
   </head>
<body style='margin-left: 60px;'>
<h3>Welcome!</h3>
<div style='font-size: 11px;'>The SayOp installation will create the necessary MySQL tables with your specified prefix.<br /><br />Press install to continue.</div>
<center>
<form name='f' action='setup.php?install' method='get'>
<input type='submit' value='Install' name='install' />
</form>
</center>
</body>
</html>
";
}
?>