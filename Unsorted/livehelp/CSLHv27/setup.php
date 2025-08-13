<? 
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

require("globals.php");
$version = "2.7";

// first check to see if the setup already ran if so do not do this page.
if ($installed == true){
  print "Installation program has already been run.. <a href=index.php>CLICK HERE</a>";
  exit;
}

// check to see if they can parse php
if (false) {
?>
 <META HTTP-EQUIV="refresh" content="2;URL=nophp.htm">
<?
}

if ($action == "INSTALL"){

 if ($dbtype == "txt-db-api.php"){
   $fp2 = fopen ("$txtpath/test.txt", "w+");
    if(!($fp2)){
    	$errors .= "<li>Can not write to the text based database directory . Make sure $txtpath is chmod 777 ";
    }
 }	
	
// check for errors.
if ($password == ""){  
   $errors .= "<li>You did not enter in a password.";
}

if ($password != $password2){  
   $errors .= "<li>The two passwords you entered do not equal eachother .. you might of mistyped it password the second time . please retype the passwords you entered in again.";
}
$password = ereg_replace("[^A-Za-z0-9]", "", $password);
if ($password != $password2){  
   $errors .= "<li>The password you entered contained invalid characters. Please only use letters and numbers ";
}
if ($email == ""){ $errors .= "<li>You did not enter in a e-mail address. This is important for if you ever loose your password. "; }
if ($dbtype == "mysql_options.php"){
   if ($database == ""){ $errors .= "<li>You did not enter in a Mysql database name "; }
   if ($datausername == ""){ $errors .= "<li>You did not enter in a Mysql Username name "; }        
   if($errors == ""){
     $conn = mysql_connect($server,$datausername,$mypassword);		
     if(!$conn) {
	  $errors .= "<li>Connection to the database failed. You may have the wrong database username/password "; 
     } 
     if(!mysql_select_db($database,$conn)) {
		$errors .= "<li>Database select failed. You may have the wrong database "; 
      }
   }
}


if( ($homepage == "") || ($homepage == "http://www.urltoyourwebsite.com") ){ 
	  $errors .= "<li>You did not enter in a valid homepage address. ";
}

if ($errors == ""){

// setup the config file..
$fcontents = implode ('', file ('config.php'));

$fcontents = ereg_replace("installed=false","installed=true",$fcontents);
$fcontents = ereg_replace("INPUT-DBTYPE","$dbtype",$fcontents);
$fcontents = ereg_replace("INPUT-SERVER","$server",$fcontents);
$fcontents = ereg_replace("INPUT-DATABASE","$database",$fcontents);
$fcontents = ereg_replace("INPUT-DATAUSERNAME","$datausername",$fcontents);
$fcontents = ereg_replace("INPUT-PASSWORD","$mypassword",$fcontents);
$lastchar = substr($txtpath,-1);
  if($lastchar != "/"){ $txtpath .= "/"; }
$lastchar = substr($rootpath,-1);  
  if($lastchar != "/"){ $rootpath .= "/"; }
$lastchar = substr($dbpath,-1);  
  if($lastchar != "/"){ $dbpath .= "/"; }
$lastchar = substr($homepage,-1);
  if($lastchar != "/"){ $homepage .= "/"; }
if (get_magic_quotes_gpc()) { 
 $fcontents = ereg_replace("INPUT-TXTPATH",stripslashes($txtpath),$fcontents);
 $fcontents = ereg_replace("INPUT-ROOTPATH",stripslashes($rootpath),$fcontents);
 $fcontents = ereg_replace("INPUT-DBPATH",stripslashes($dbpath),$fcontents);
 $fcontents = ereg_replace("INPUT-HTTP",stripslashes($homepage),$fcontents);
} else {
 $fcontents = ereg_replace("INPUT-TXTPATH",stripslashes($txtpath),$fcontents);
 $fcontents = ereg_replace("INPUT-ROOTPATH",stripslashes($rootpath),$fcontents);
 $fcontents = ereg_replace("INPUT-DBPATH",stripslashes($dbpath),$fcontents);
 $fcontents = ereg_replace("INPUT-HTTP",stripslashes($homepage),$fcontents);
}
  
$insert_query = "INSERT INTO livehelp_config (version, site_title, use_flush, membernum, offset, show_typing,webpath,speaklanguage) VALUES ('2.7', 'Live Help!', 'YES', 0, 0, '','$homepage','$speaklanguage')";
$insert_query2 = "INSERT INTO livehelp_users (username,password,isonline,isoperator,isadmin,isnamed,email,show_arrival,user_alert,auto_invite) VALUES ('$username','$password','N','Y','Y','Y','$email','Y','N','N')";
$onlineimage = $homepage . "online.gif";
$offlineimage = $homepage . "leavemessage.gif"; 
$insert_query3 = "INSERT INTO livehelp_departments (nameof, onlineimage, offlineimage, qaimage, requirename, messageemail, leaveamessage, opening, offline, qa_topic, qa_enabled) VALUES ('default', '$onlineimage', '$offlineimage', 'qaimage.gif', 'Y', '$email', 'YES', '<blockquote>$opening</blockquote>', '<blockquote>\r\nSorry no operators are currently online to provide Live support at this time.<blockquote>', 0, 'N')";
$insert_query4 = "INSERT INTO livehelp_operator_departments (recno, user_id, department, extra) VALUES (1, 1, 1, '')";

// update the config file.
if($manualinstall != "YES"){
  $fp = fopen ("config.php", "w+");
  fwrite($fp,$fcontents);
  fclose($fp);
} else {
  print "<b>config.php:</b> Select all of the code below and then copy and paste it over your existing config.php file on the server";
  print "<table bgcolor=DDDDDD><tr><td><pre>";
  print htmlspecialchars($fcontents);
  print "</pre></td></tr></table>";
}

// build the database.

if($installationtype == "upgrade"){
  // not supported...		

}
if($installationtype == "upgrade22"){
$sql = "ALTER TABLE `livehelp_config` ADD `speaklanguage` VARCHAR(10) DEFAULT 'eng' NOT NULL ";
mysql_query($sql,$conn);

 $sql = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
mysql_query($sql,$conn);

$installationtype = "upgrade24";

}

if($installationtype == "upgrade24"){
$sql = "UPDATE livehelp_config set speaklanguage='eng',version='2.7'";
mysql_query($sql,$conn);
 $sql = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
mysql_query($sql,$conn);

$installationtype = "upgrade25";
}

if($installationtype == "upgrade25"){
 if ($dbtype == "txt-db-api.php"){
   if (get_magic_quotes_gpc()) { 
     $txtpath = stripslashes($txtpath);
   } else {
     $txtpath = $txtpath;	
   }

$sql = "ALTER TABLE livehelp_users ADD auto_invite CHAR(1) NOT NULL ";
mysql_query($sql,$conn);

$sql = "ALTER TABLE livehelp_users ADD istyping CHAR(1) NOT NULL ";
mysql_query($sql,$conn);

$sql = "ALTER TABLE livehelp_users ADD visits INT(8) NOT NULL ";
mysql_query($sql,$conn);


$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_autoinvite.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "idnum#isactive#department#message#page#visits#referer#\n";
$headerfields .= "inc#str#int#str#str#int#str#\n";
$headerfields .= "0######";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777); 

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_modules.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "id#name#path#adminpath#query_string#\n";
$headerfields .= "inc#str#str#str#str#\n";
$headerfields .= "0#####\n";
$headerfields .= "1#Live Help!#livehelp.php###\n";
$headerfields .= "2#Contact#leavemessage.php###\n";
$headerfields .= "3#Q & A#user_qa.php#qa.php##\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_modules_dep.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "rec#departmentid#modid#ordernum#defaultset#\n";
$headerfields .= "inc#int#int#int#str#\n";
$headerfields .= "0#####\n";
$headerfields .= "1#1#1#1##\n";
$headerfields .= "2#1#2#2##\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

} else {
	
$sql = "ALTER TABLE livehelp_users ADD auto_invite CHAR(1) NOT NULL ";
mysql_query($sql,$conn);

$sql = "ALTER TABLE livehelp_users ADD istyping CHAR(1) NOT NULL ";
mysql_query($sql,$conn);

$sql = "ALTER TABLE livehelp_users ADD visits INT(8) NOT NULL ";
mysql_query($sql,$conn);
	
  $sql = "DROP TABLE IF EXISTS livehelp_modules_dep";
  mysql_query($sql,$conn);
 $sql = 
"CREATE TABLE livehelp_modules_dep (
  rec int(10) NOT NULL auto_increment,
  departmentid int(10) NOT NULL default '0',
  modid int(10) NOT NULL default '0',
  ordernum int(8) NOT NULL default '0',
  defaultset char(1) NOT NULL default '',
  PRIMARY KEY  (rec)
) 
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}


$sql = "DROP TABLE IF EXISTS livehelp_autoinvite";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE `livehelp_autoinvite` (
  `idnum` int(10) NOT NULL auto_increment,
   isactive char(1) NOT NULL default '',  
  `department` int(10) NOT NULL default '0',
  `message` text NOT NULL,
  `page` varchar(255) NOT NULL default '',
  `visits` int(8) NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
   PRIMARY KEY  (idnum)
) ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_modules";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_modules (
  id int(10) NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  path varchar(255) NOT NULL default '',
  adminpath varchar(255) NOT NULL default '',
  query_string varchar(255) NOT NULL default '',  
  PRIMARY KEY  (id)
) 
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (1, 'Live Help!', 'livehelp.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (2, 'Contact', 'leavemessage.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path,adminpath) VALUES (3, 'Q & A', 'user_qa.php','qa.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (1, 1, 1, 1, '')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (2, 1, 2, 2, '')",$conn);

}
$installationtype = "upgrade26";
}

if($installationtype == "upgrade26"){
$sql = "UPDATE livehelp_config set speaklanguage='eng',version='2.7'";
mysql_query($sql,$conn);

}

if($installationtype == "newinstall"){
if ($dbtype == "txt-db-api.php"){
if (get_magic_quotes_gpc()) { 
   $txtpath = stripslashes($txtpath);
} else {
   $txtpath = $txtpath;	
}

$filepath = "$txtpath" . "/" . "livehelp";
mkdir ("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_channels.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "id#user_id#statusof#startdate#\n";
$headerfields .= "inc#int#str#int#\n";
$headerfields .= "0#0##0#";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_autoinvite.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "idnum#isactive#department#message#page#visits#referer#\n";
$headerfields .= "inc#str#int#str#str#int#str#\n";
$headerfields .= "0######";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777); 

$openingmessage = ereg_replace("\n"," ",$openingmessage);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_config.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "version#site_title#use_flush#membernum#offset#show_typing#webpath#speaklanguage#\n";
$headerfields .= "str#str#str#int#int#str#str#str#\n";
$headerfields .= "2.7########";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_visits.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "recno#pageurl#dayof#uniquevisits#\n";
$headerfields .= "inc#str#int#int#\n";
$headerfields .= "0#######";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_visits_total.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "recno#pageurl#ctotal#\n";
$headerfields .= "inc#str#int#\n";
$headerfields .= "0######";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_visits.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "recno#pageurl#dayof#uniquevisits#\n";
$headerfields .= "inc#str#int#int#\n";
$headerfields .= "0#######";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_referers.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "recno#camefrom#dayof#uniquevisits#\n";
$headerfields .= "inc#str#int#int#\n";
$headerfields .= "0#######";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_referers_total.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "recno#camefrom#ctotal#\n";
$headerfields .= "inc#str#int#\n";
$headerfields .= "0######";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_departments.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "recno#nameof#onlineimage#offlineimage#qaimage#requirename#messageemail#leaveamessage#opening#offline#qa_topic#qa_enabled#creditline#\n";
$headerfields .= "inc#str#str#str#str#str#str#str#str#str#int#str#str#\n";
$headerfields .= "0#############\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_messages.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "id_num#message#channel#timeof#saidfrom#saidto#\n";
$headerfields .= "inc#str#int#int#int#int#\n";
$headerfields .= "0##0#0#0#0#";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_operator_channels.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "id#user_id#channel#userid#statusof#startdate#bgcolor#\n";
$headerfields .= "inc#int#int#int#str#int#str#\n";
$headerfields .= "0#0#0#0##0##";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_operator_departments.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "recno#user_id#department#extra#\n";
$headerfields .= "inc#int#int#str#\n";
$headerfields .= "0####\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_qa.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "recno#parent#question#typeof#status#username#ordernum#\n";
$headerfields .= "inc#int#str#str#str#str#int#\n";
$headerfields .= "0#######\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777); 

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_quick.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "id#name#typeof#message#visiblity#department#user#\n";
$headerfields .= "inc#str#str#str#str#int#int#\n";
$headerfields .= "0#######\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_modules.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "id#name#path#adminpath#query_string#\n";
$headerfields .= "inc#str#str#str#str#\n";
$headerfields .= "0###\n";
$headerfields .= "1#Live Help!#livehelp.php###\n";
$headerfields .= "2#Contact#leavemessage.php###\n";
$headerfields .= "3#Q & A#user_qa.php#qa.php##\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_modules_dep.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "rec#departmentid#modid#ordernum#defaultset#\n";
$headerfields .= "inc#int#int#int#str#\n";
$headerfields .= "0#####\n";
$headerfields .= "1#1#1#1##\n";
$headerfields .= "2#1#2#2##\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_transcripts.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "recno#who#daytime#transcript#\n";
$headerfields .= "inc#str#int#str#\n";
$headerfields .= "0####\n";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_users.txt";
$fp = fopen ($filepath, "w+");
$headerfields = "user_id#lastaction#username#password#isonline#isoperator#onchannel#isadmin#department#identity#status#isnamed#showedup#email#camefrom#show_arrival#user_alert#auto_invite#istyping#visits#\n";
$headerfields .= "inc#int#str#str#str#str#int#str#str#str#str#str#str#str#str#str#str#str#str#int#\n";
$headerfields .= "0#20030503141153####N##############0#";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_visit_track.txt";
$fp = fopen ($filepath, "w+");
$headerfields = "recno#id#location#page#title#whendone#referrer#\n";
$headerfields .= "inc#int#str#str#str#int#str#\n";
$headerfields .= "0#####0##";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

if (get_magic_quotes_gpc()) { 
 $DB_DIR = stripslashes($txtpath); 
 $API_HOME_DIR = stripslashes($rootpath) . "txt-db-api/";
} else {
 $DB_DIR = $txtpath; 
 $API_HOME_DIR = $rootpath . "txt-db-api/";
}

require "txt-db-api/txt-db-api.php";
$mydatabase = new Database("livehelp");	
$mydatabase->insert($insert_query);
$mydatabase->insert($insert_query2);
$mydatabase->insert($insert_query3);
$mydatabase->insert($insert_query4);

}
if ($dbtype == "MSaccess.php"){
require "MSaccess.php";
$mydatabase = new MS_options();	
$mydatabase->insert($insert_query);
$mydatabase->insert($insert_query2);
$mydatabase->insert($insert_query3);
$mydatabase->insert($insert_query4);
}
if ($dbtype == "MSSQLaccess.php"){

  $conn = new COM("ADODB.Connection") or die("Cannot start ADO");
  $strCon = "Provider=SQLOLEDB;Server=$server;User ID=$user;Password=$pass;Database=$dbase";
  $conn->Open($strCon); 
        

}
if ($dbtype == "mysql_options.php"){

$sql = "DROP TABLE IF EXISTS livehelp_autoinvite";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE `livehelp_autoinvite` (
  `idnum` int(10) NOT NULL auto_increment,
   isactive char(1) NOT NULL default '',  
  `department` int(10) NOT NULL default '0',
  `message` text NOT NULL,
  `page` varchar(255) NOT NULL default '',
  `visits` int(8) NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
   PRIMARY KEY  (idnum)
) ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_modules_dep";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_modules_dep (
  rec int(10) NOT NULL auto_increment,
  departmentid int(10) NOT NULL default '0',
  modid int(10) NOT NULL default '0',
  ordernum int(8) NOT NULL default '0',
  defaultset char(1) NOT NULL default '',
  PRIMARY KEY  (rec)
) 
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_modules";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_modules (
  id int(10) NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  path varchar(255) NOT NULL default '',
  adminpath varchar(255) NOT NULL default '',
  query_string varchar(255) NOT NULL default '',  
  PRIMARY KEY  (id)
)  
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (1, 'Live Help!', 'livehelp.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (2, 'Contact', 'leavemessage.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path,adminpath) VALUES (3, 'Q & A', 'user_qa.php','qa.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (1, 1, 1, 1, '')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (2, 1, 2, 2, '')",$conn);
		
	
$sql = "DROP TABLE IF EXISTS livehelp_channels";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  PRIMARY KEY  (id)
)
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
		
$sql = "DROP TABLE IF EXISTS livehelp_config";
mysql_query($sql,$conn);  
  $sql  =  
"CREATE TABLE livehelp_config (
  version float(3,1) NOT NULL default '1.7',
  site_title varchar(100) NOT NULL default '',
  use_flush varchar(10) NOT NULL default 'YES',
  membernum int(8) NOT NULL default '0',
  offset int(5) NOT NULL default '0',
  show_typing char(1) NOT NULL default '',
  webpath varchar(255) NOT NULL default '',
  speaklanguage varchar(10) not NULL default 'eng'
)
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
 		$results = mysql_query($insert_query,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$sql = "DROP TABLE IF EXISTS livehelp_departments";
mysql_query($sql,$conn); 
$sql  =  "		
CREATE TABLE livehelp_departments (
  recno int(5) NOT NULL auto_increment,
  nameof varchar(30) NOT NULL default '',
  onlineimage varchar(255) NOT NULL default '',
  offlineimage varchar(255) NOT NULL default '',
  qaimage varchar(255) NOT NULL default '',
  requirename char(1) NOT NULL default '',
  messageemail varchar(60) NOT NULL default '',
  leaveamessage varchar(10) NOT NULL default '',
  opening text NOT NULL,
  offline text NOT NULL,
  qa_topic int(10) NOT NULL default '0',
  qa_enabled char(1) NOT NULL default '',
  creditline char(1) NOT NULL default '',
  PRIMARY KEY  (recno)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_messages";
mysql_query($sql,$conn);   
  $sql  =  "	
CREATE TABLE livehelp_messages (
  id_num int(10) NOT NULL auto_increment,
  message text NOT NULL,
  channel int(10) NOT NULL default '0',
  timeof bigint(14) NOT NULL default '0',
  saidfrom int(10) NOT NULL default '0',
  saidto int(10) NOT NULL default '0',
  PRIMARY KEY  (id_num),
  KEY channel (channel),
  KEY timeof (timeof)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_operator_channels";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_operator_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  channel int(10) NOT NULL default '0',
  userid int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  bgcolor varchar(10) NOT NULL default '000000',
  PRIMARY KEY  (id),
  KEY channel (channel),
  KEY user_id (user_id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}	 
  $sql = "DROP TABLE IF EXISTS livehelp_operator_departments";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_operator_departments (
  recno int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  department int(10) NOT NULL default '0',
  extra varchar(100) NOT NULL default '',
  PRIMARY KEY  (recno),
  KEY user_id (user_id),
  KEY department (department)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_qa";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_qa (
  recno int(10) NOT NULL auto_increment,
  parent int(10) NOT NULL default '0',
  question text NOT NULL,
  typeof varchar(10) NOT NULL default '',
  status VARCHAR(20) NOT NULL,
  username varchar(60) NOT NULL,
  ordernum int(10) NOT NULL default '0',   
  PRIMARY KEY  (recno)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

  $sql = "DROP TABLE IF EXISTS livehelp_quick";
mysql_query($sql,$conn); 		
  $sql  =  "	
CREATE TABLE livehelp_quick (
  id int(10) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  typeof varchar(30) NOT NULL default '',
  message text NOT NULL,
  visiblity varchar(20) NOT NULL default '',
  department int(10) NOT NULL default '0',
  user int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$sql = "DROP TABLE IF EXISTS livehelp_referers";
mysql_query($sql,$conn);		
$sql  =  "CREATE TABLE livehelp_referers (
  recno int(11) NOT NULL auto_increment,
  camefrom varchar(255) NOT NULL default '0',
  dayof int(8) NOT NULL default '0',
  uniquevisits int(10) NOT NULL default '0',  
  PRIMARY KEY  (recno),
  KEY camefrom (camefrom),
  KEY dayof (dayof),
  KEY uniquevisits (uniquevisits)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_referers_total";
mysql_query($sql,$conn);		
$sql  =  "CREATE TABLE livehelp_referers_total (
  recno int(11) NOT NULL auto_increment,
  camefrom varchar(255) NOT NULL default '0',
  ctotal int(10) NOT NULL default '0',  
  PRIMARY KEY  (recno),
  KEY camefrom (camefrom),
  KEY ctotal (ctotal)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$sql = "DROP TABLE IF EXISTS livehelp_transcripts";
mysql_query($sql,$conn); 	
  $sql  =  "	
CREATE TABLE livehelp_transcripts (
  recno int(10) NOT NULL auto_increment,
  who varchar(100) NOT NULL default '',
  daytime timestamp(14) NOT NULL,
  transcript text NOT NULL,
  PRIMARY KEY  (recno)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_users";
mysql_query($sql,$conn); 			
  $sql  =  "	
CREATE TABLE livehelp_users (
  user_id int(10) NOT NULL auto_increment,
  lastaction timestamp(14) NOT NULL,
  username varchar(30) NOT NULL default '',
  password varchar(60) NOT NULL default '',
  isonline char(1) NOT NULL default '',
  isoperator char(1) NOT NULL default 'N',
  onchannel int(10) NOT NULL default '0',
  isadmin char(1) NOT NULL default 'N',
  department int(5) NOT NULL default '0',
  identity varchar(255) NOT NULL default '',
  status varchar(30) NOT NULL default '',
  isnamed char(1) NOT NULL default 'N',
  showedup bigint(14) default NULL,
  email varchar(60) NOT NULL default '',
  camefrom varchar(200) NOT NULL default '',
  show_arrival char(1) NOT NULL default 'N',
  user_alert char(1) NOT NULL default '',
  auto_invite CHAR( 1 ) NOT NULL,
  istyping  CHAR( 1 ) NOT NULL,
  visits int(8) NOT NULL, 
  PRIMARY KEY  (user_id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}		
$sql = "DROP TABLE IF EXISTS livehelp_visit_track";
mysql_query($sql,$conn); 	
  $sql  =  "	
CREATE TABLE livehelp_visit_track (
  recno int(10) NOT NULL auto_increment,
  id varchar(30) NOT NULL default '0',
  location varchar(100) NOT NULL default '',
  page bigint(14) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  whendone timestamp(14) NOT NULL,
  referrer varchar(100) NOT NULL default '',
  PRIMARY KEY  (recno),
  KEY id (id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}		
$sql = "DROP TABLE IF EXISTS livehelp_visits";
mysql_query($sql,$conn);
$sql  =  "CREATE TABLE livehelp_visits (
  recno int(11) NOT NULL auto_increment,
  pageurl varchar(255) NOT NULL default '0',
  dayof int(8) NOT NULL default '0',
  uniquevisits int(10) NOT NULL default '0',  
  PRIMARY KEY  (recno),
  KEY pageurl (pageurl),
  KEY dayof (dayof),
  KEY uniquevisits (uniquevisits)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
	
$sql = "DROP TABLE IF EXISTS livehelp_visits_total";
mysql_query($sql,$conn);
$sql  =  "CREATE TABLE livehelp_visits_total (
  recno int(11) NOT NULL auto_increment,
  pageurl varchar(255) NOT NULL default '0',
  ctotal int(10) NOT NULL default '0',  
  PRIMARY KEY  (recno),
  KEY pageurl (pageurl),
  KEY ctotal (ctotal)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

// default stuff...  				
 		$results = mysql_query($insert_query2,$conn);
 		$results = mysql_query($insert_query3,$conn);
		$results = mysql_query($insert_query4,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
}}
}
if($errors != ""){
  print "<font color=990000 size=+3>THERE WAS A FEW PROBLEMS:</font><ul>";	
print "$errors";
print "<a href=javascript:history.go(-1)>CLICK HERE TO TRY AGAIN</a>";
exit;
}

?>
<center>
<table width=400><tr><td bgcolor=FFFFCC> <b><font size=+3>INSTALLATION IS DONE!!</font></td></tr>
<tr><td bgcolor=FFFFEE>You will now need to log into the admin of this livehelp and 
start adding pages. To do this click the link below. <b><font color=990000>Right it down</font></b>
<br><br><center>
<b>username:</b><?= $username ?> <br>
<b>password:</b><?= $password2 ?><br>
 </center>
<br>
<hr>
<a href=http://www.craftysyntax.com/livehelp/registration.php?v=2.7&e=<?= $email ?>&db=<?= $dbtype ?>><font size=+3>CLICK HERE TO BEGIN REGISTRATION</font></a>
<br><br>
<a href=index.php>Skip REGISTRATION</a><br><br>
<SCRIPT>
function gothere(){
  // This opens a window to the Live help News page. It sends in the query string the version 
  // being installed, type of database used (mysql or text based) and referer. This basic
  // info is just to get an idea of how many successful installations of what versions and 
  // what type of databases for the program are made.    
  url = 'http://craftysyntax.com/livehelp/updates.php?v=2.7&d=<?= $dbtype ?>&referer=' + window.location;
  window.open(url, 's872', 'width=590,height=350,menubar=no,scrollbars=1,resizable=1');
}
setTimeout('gothere();',200);
</SCRIPT>
<?
print "<a href=index.php>CLICK HERE to get started.. </a></td></tr></table>";
exit;
}

?>
<script>

function openwindow(theURL,winName,features) {//v1.0 
win2 = window.open(theURL,winName,features); 
window.win2.focus(); 
}
 
</script>

<h2>Crafty Syntax livehelp Installation
<?
$test_dir = getcwd();
?>
</h2>
problems on this page?!? <a href=http://www.craftysyntax.com/support/index.php?c=3 TARGET=_blank>CLICK HERE TO GO TO SUPPORT PAGE</a>
<hr>
<?
if ($errors != ""){
  print "<table width=600><tr><td><font color=990000>ERRORS: $errors</font></td></tr></table>";	
}

// Check to see if we can write to the config file.
if($manualinstall != "YES"){
$fp = fopen ("config.php", "r+");
} else {
$fp = True;	
}

if(!$fp){
?>
<table width=500 bgcolor=FFFFEE><tr><td>
<font color=990000 size=+2><b>Can not open <font color=000099>config.php</font> file for writing:</b></font><br>


<hr>
<font color=007700><b>HOW TO FIX THIS:</b></font><br><br>
In order to configure the Live Help using this web wizard,
 the web server needs to be able to read and write to the
 file named <b><i>config.php</i></b>. if you can not change
 the permissions of this file then there is a manual change 
 option listed at the bottom of this page... 
 if you are planning on 
 using a text based database you will also need to change the
 permissions of the directory <b>txt-database</b>. Directions on doing  this follows:
<br>
Installing and configuring C.S. livehelp is done via a set of web pages.
To enable these web pages you need to log onto your web server using
telnet or (preferably) ssh, go to the livehelp directory and at the
prompt (which usually ends in '%' or '$') type:
<br><br>
chmod 777 txt-database <font color=990000><i>(if using a text based database)</i></font><br>
chmod 777 config.php<br>
<br><br>
After installation you can change the permissions of config.php to chmod 755.
<br><br>
if you can not ssh or telent into your website you can to the same task by
FTP: 
<br><br>	
Using WS_FTP this would be right hand clicking on the 
file config.php , selecting chmod, and then giving all
permissions to that file/directory. 
<br>
<img src=directions.gif>
<br>
<hr>
<br>
<h2><b>Manual config change option:</b></h2>
if you do not have access to, or can not change the permissions of config.php you 
also have the Option to update config.php yourself. 
<a href=setup.php?manualinstall=YES>CLICK HERE TO RUN INSTALLAION BY MANUALLY CHANGING config.php</a><br>
<br>
</td></tr></table><br>
<a href=setup.php>AFTER YOU HAVE CHANGED THE PERMISSIONS OF THE 
FILES HOLD DOWN THE shift KEY and PRESS REFRESH or RELOAD</a>
<br>
<br><br>
<table bgcolor=FFFFFF><tr><td>
<h3>if you can not change the permissions of the files here are Manual Installation Directions:</h3>
<hr>
if you do not have access to, or can not change the permissions of config.php you 
also have the Option to update config.php yourself after the database has been created. 
<a href=setup.php?manualinstall=YES>CLICK HERE TO RUN INSTALLAION BY MANUALLY CHANGING config.php</a><br>

</td></tr></table>

</td></tr></table>
<?
exit;
}


?>
<FORM action=setup.php method=post name=erics>
<input type=hidden name=manualinstall value="<?=$manualinstall?>">
<table width=600 bgcolor=FFFFEE>
<?

if ($site_title == ""){
  $site_title = "My Online Live Help";
}

?>
<tr><td bgcolor=DDDDDD colspan=2><b>LANGUAGE:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
All of the text for CSLH that is shown on the
users side can be in :<br>
</td></tr>
<tr><td>Language:</td>
<td><select name=speaklanguage>
<option value=eng>English </option>
<option value=frn <? if ($speaklanguage == "frn"){ print " SELECTED "; } ?> >French </option>
<option value=ger <? if ($speaklanguage == "ger"){ print " SELECTED "; } ?> >German </option>
<option value=ita <? if ($speaklanguage == "ita"){ print " SELECTED "; } ?> >Italian </option>
<option value=por <? if ($speaklanguage == "por"){ print " SELECTED "; } ?> >Portuguese</option>
<option value=spn <? if ($speaklanguage == "spn"){ print " SELECTED "; } ?> >Spanish</option>
</select>
</td></tr>

<tr><td bgcolor=DDDDDD colspan=2><b>INSTALLATION OPTION:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
You can upgrade to 
the newest version of the live help and not loose any of your data...<br>
</td></tr>
<tr><td>Installation:</td>
<td><select name=installationtype>
<option value=newinstall>NEW INSTLLATION</option>
<option value=upgrade26 <? if ($installationtype == "upgrade26"){ print " SELECTED "; } ?> >UPGRADE from version 2.6 </option>
<option value=upgrade25 <? if ($installationtype == "upgrade25"){ print " SELECTED "; } ?> >UPGRADE from version 2.5 </option>
<option value=upgrade24 <? if ($installationtype == "upgrade24"){ print " SELECTED "; } ?> >UPGRADE from version 2.4 </option>
<option value=upgrade22 <? if ($installationtype == "upgrade22"){ print " SELECTED "; } ?> >UPGRADE from version 2.2 or 2.3 </option>
<option value=upgrade <? if ($installationtype == "upgrade"){ print " SELECTED "; } ?> >UPGRADE from Before version 2.2 </option>
</select>
</td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Title of your livehelp:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the Title of your livehelp and will be shown as the title tag.</td></tr>
<tr><td>Title of your livehelp:</td><td><input type=text name=site_title size=40 value="<?= $site_title ?>"></td></tr>

<?

if ($homepage == ""){
  $dir = dirname($PHP_SELF);
  $homepage  = "http://" . $HTTP_HOST . $dir;
}

?>
<tr><td bgcolor=DDDDDD colspan=2><b>Web path to Livehelp:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the url to the Live help on your server. It will be used 
to access the online help for your site..</td></tr>
<tr><td>Live help URL:</td><td><input type=text name=homepage size=40 value="<?= $homepage ?>"></td></tr>

<tr><td bgcolor=DDDDDD colspan=2><b>Administration user/Password:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
Although you can create multiple Operators for the Live help. There is
one operator that is the main administrator that can create users, edit,
add, delete everything. Create that account here.
The password must only contain number and letters. no spaces or !@#$ characters. </td></tr>
<tr><td>username:</td><td><input type=text name=username size=10 value="<?= $username ?>"></td></tr>
<tr><td>password:</td><td><input type=password name=password size=10 value="<?= $password ?>"></td></tr>
<tr><td>password (again):</td><td><input type=password name=password2 size=10 value="<?= $password2 ?>"></td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Administration e-mail:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the e-mail address for the administrator of this. e-mails on access, lost password, etc.. </td></tr>
<tr><td>email:</td><td><input type=text name=email size=30 value="<?= $email ?>"></td></tr>

<tr><td bgcolor=DDDDDD colspan=2><b>Full Path to livehelp:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
This is the Full Path to livehelp not to be confused with the httpd path. 
This should be a file path like /www/username/public_html/livehelp</td></tr>
<tr><td>Full Path to livehelp:</td><td><input type=text name=rootpath size=55 value="<? if($rootpath != ""){ print stripslashes($rootpath); } else { print getcwd(); } ?>"></td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Opening message:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
When the user first opens up the Live Help they are directed to a
page to enter in their name so that Operators can Identify them
easy. This is the text shown on that opening page.</td></tr>
<tr><td colspan=2>
<b>Opening Message:</b><br>
<textarea cols=45 rows=4 name=opening>
<?
if($openingmessage == ""){
print "Welcome to our Live Help. Please enter in your Name in the input box below to begin.";
} else {
print $openingmessage;
}
?>
</textarea>
</td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Type of Database:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
This is the type of database that you are using. At the moment this is 
only Mysql. Future versions will have more types of databases.. 
if you are having trouble with your database settings you can 
request help at <a href=http://www.craftysyntax.com/livehelp/support.php>Programming and Support Page.</a>
</td></tr>

<tr><td>Database:</td><td><select name=dbtype>
<option value=mysql_options.php <? if ($dbtype == "mysql_options.php"){ print " SELECTED "; } ?> >MySQL </option>
<!--<option value=MSSQLaccess.php <? if ($dbtype == "MSSQLaccess.php"){ print " SELECTED "; } ?> >Microsoft SQL Server </option>-->
<!-- <option value=MSaccess.php <? if ($dbtype == "MSaccess.php"){ print " SELECTED "; } ?> >Microsoft Access </option>-->
<option value=txt-db-api.php <? if ($dbtype == "txt-db-api.php"){ print " SELECTED "; } ?> >txt-db-api (simple Flat text files)</option>
</select></td></tr>
<tr><td colspan=2><ul>
<table bgcolor=FFFFCC>
<tr><td colspan=2>If <b>MySQL</b> is selected above:</td></tr>
<? if($server == ""){ $server = "localhost"; } ?>
<tr><td>SQL server:</td><td><input type=text name=server size=20 value="<?= $server ?>" ></td></tr>
<tr><td>SQL database:</td><td><input type=text name=database size=20 value="<?= $database ?>"></td></tr>
<tr><td>SQL user:</td><td><input type=text name=datausername size=20 value="<?= $datausername ?>"></td></tr>
<tr><td>SQL password:</td><td><input type=text name=mypassword size=20 value="<?= $mypassword ?>"></td></tr>
</table><br>
<table bgcolor=FFFFCC>
<tr><td colspan=2>If <b>txt-db-api (simple Flat text files)</b> is selected above you need to provide a 
full path to the directory where the txt files will be stored. This directory must be writable 
by the web and if you care about security should not be in a web accessable directory. </td></tr>

<tr><td>txt path:</td><td><input type=text name=txtpath size=55 value="<? 
if($txtpath != ""){ print stripslashes($txtpath); } else { 
          print "$test_dir/txt-database";	
 } ?>" ></td></tr>
<!--
<tr><td colspan=2>If <b>Microsoft Access</b> is selected above you need to provide a 
full path to the directory where the mdb Database file is located. This directory must be writable 
by the web and if you care about security should not be in a web accessable directory. </td></tr>
<tr><td>mdb database path:</td><td><input type=text name=dbpath size=55 value="<? 
if($dbpath != ""){ print stripslashes($dbpath); } else { 
          print "$test_dir/MS-access-db/livehelp.mdb";	
 } ?>" ></td></tr>
-->
</table>

</td></tr>
</table></td></tr>
</table>

<input type=SUBMIT name=action value=INSTALL>
</form>