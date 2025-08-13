<? 
//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
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

include "config.php";
$version = "1.7";

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

$fcontents2 = implode ('', file ('livehelp.js'));

$fcontents = ereg_replace("installed=false","installed=true",$fcontents);
$fcontents = ereg_replace("INPUT-DBTYPE","$dbtype",$fcontents);
$fcontents = ereg_replace("INPUT-SERVER","$server",$fcontents);
$fcontents = ereg_replace("INPUT-DATABASE","$database",$fcontents);
$fcontents = ereg_replace("INPUT-DATAUSERNAME","$datausername",$fcontents);
$fcontents = ereg_replace("INPUT-PASSWORD","$mypassword",$fcontents);
$lastchar = substr($homepage,-1);
  if($lastchar != "/"){ $homepage .= "/"; }
$lastchar = substr($txtpath,-1);
  if($lastchar != "/"){ $txtpath .= "/"; }
$lastchar = substr($rootpath,-1);  
  if($lastchar != "/"){ $rootpath .= "/"; }
$lastchar = substr($dbpath,-1);  
  if($lastchar != "/"){ $dbpath .= "/"; }

$fcontents = ereg_replace("WEB-PATH","$homepage",$fcontents);
$fcontents2 = ereg_replace("WEB-PATH","$homepage",$fcontents2);
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
$insert_query = "INSERT INTO livehelp_config (site_title,version,opening,needname,leaveamessage,messageemail,use_flush,membernum) VALUES ('Live Help!', '1.7','<blockquote>Welcome to Live Help. <br> Please Enter in your Name at the bottom of this screen to begin.</blockquote>','YES','YES','$email','YES','0')";
$insert_query2 = "INSERT INTO livehelp_users (username,password,isonline,isoperator,isadmin,isnamed,email) VALUES ('$username','$password','N','Y','Y','Y','$email')";
$onlineimage = $homepage . "online.gif";
$offlineimage = $homepage . "leavemessage.gif"; 
$insert_query3 = "INSERT INTO livehelp_departments (nameof,onlineimage,offlineimage,messageemail) VALUES ('default','$onlineimage','$offlineimage','$email')";

// update the config file.
$fp = fopen ("config.php", "w+");
fwrite($fp,$fcontents);
fclose($fp);

// update the js file.
$fp = fopen ("livehelp.js", "w+");
fwrite($fp,$fcontents2);
fclose($fp);


// build the database.

if($installationtype == "upgrade"){
 // upgrades 
 require "config.php";
 if ($version == "1.0") { 	  
  $alt_q = "ALTER TABLE livehelp_operator_channels ADD bgcolor varchar (10) DEFAULT '000000' not null ";
  $mydatabase->sql_query($alt_q);
  $version = "1.2"; 
 }
 if ($version == "1.2") {  
  $alt_q = "ALTER TABLE livehelp_config ADD use_flush VARCHAR( 10 ) DEFAULT 'YES' NOT NULL ";
  $mydatabase->sql_query($alt_q);
  $alt_q = "ALTER TABLE livehelp_config ADD membernum int( 8 )";
  $mydatabase->sql_query($alt_q);
  $version == "1.5";
 }

 if($version == "1.5"){
  $alt_q = "ALTER TABLE livehelp_config ADD alert_visit CHAR( 1 ) DEFAULT 'N' NOT NULL ";	
  $mydatabase->sql_query($alt_q);
  $alt_q = "ALTER TABLE livehelp_users ADD showedup BIGINT( 14 ) NULL ";	
  $mydatabase->sql_query($alt_q);
  $version = "1.6";
 }

 if($version == "1.6"){
  $sql = "DROP TABLE livehelp_departments";
  $mydatabase->sql_query($alt_q);
  
   $sql  =  "		
CREATE TABLE livehelp_departments (
  recno int(5) NOT NULL auto_increment,
  nameof varchar(30) NOT NULL default '',
  onlineimage varchar(100) NOT NULL default '',
  offlineimage varchar(100) NOT NULL default '',
  requirename char(1) NOT NULL default '',
  PRIMARY KEY  (recno)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$onlineimage = $homepage . "online.gif";
$offlineimage = $homepage . "leavemessage.gif"; 
$insert_query3 = "INSERT INTO livehelp_departments (nameof,onlineimage,offlineimage) VALUES ('default','$onlineimage','$offlineimage')";
$results = mysql_query($insert_query3,$conn);

$sql = "ALTER TABLE livehelp_users ADD email VARCHAR(60) NOT NULL";
$mydatabase->sql_query($alt_q);

$sql = "ALTER TABLE livehelp_config ADD offset INT(5) NOT NULL default '0'";
$mydatabase->sql_query($alt_q);

  }

 $alt_q = "UPDATE livehelp_config set version='1.7'";
 $mydatabase->sql_query($alt_q);

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

$openingmessage = ereg_replace("\n"," ",$openingmessage);

$filepath2 = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_config.txt";
$fp2 = fopen ("$filepath2", "w+");
$headerfields = "version#site_title#opening#needname#leaveamessage#messageemail#use_flush#membernum#alert_visit#offset#\n";
$headerfields .= "str#str#str#str#str#str#str#int#str#int#\n";
$headerfields .= "1.7#######0#N#0#";
fwrite($fp2,$headerfields);
fclose($fp2);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_messages.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "id_num#message#channel#timeof#saidfrom#saidto#\n";
$headerfields .= "inc#str#int#int#int#int#\n";
$headerfields .= "0##0#0#0#0#";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_departments.txt";
$fp = fopen("$filepath", "w+");
$headerfields = "recno#nameof#onlineimage#offlineimage#requirename#messageemail#\n";
$headerfields .= "inc#str#str#str#str#str#\n";
$headerfields .= "0######";
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

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_quick.txt";
$fp = fopen ("$filepath", "w+");
$headerfields = "id#name#typeof#message#\n";
$headerfields .= "inc#str#str#str#\n";
$headerfields .= "0####";
fwrite($fp,$headerfields);
fclose($fp);
chmod("$filepath", 0777);

$filepath = "$txtpath" . "/" ."livehelp" . "/" ."livehelp_users.txt";
$fp = fopen ($filepath, "w+");
$headerfields = "user_id#lastaction#username#password#isonline#isoperator#onchannel#isadmin#department#identity#status#isnamed#showedup#email#\n";
$headerfields .= "inc#int#str#str#str#str#int#str#str#str#str#str#str#str#\n";
$headerfields .= "0#20030503141153####N#########";
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

}
if ($dbtype == "MSaccess.php"){
require "MSaccess.php";
$mydatabase = new MS_options();	
$mydatabase->insert($insert_query);
$mydatabase->insert($insert_query2);
$mydatabase->insert($insert_query3);
}

if ($dbtype == "mysql_options.php"){
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
		

  $sql  =  
"CREATE TABLE livehelp_config (
  version float(3,1) NOT NULL default '1.7',
  site_title varchar(100) NOT NULL default '',
  opening text NOT NULL,
  needname varchar(30) NOT NULL default '',
  leaveamessage varchar(10) NOT NULL default '',
  messageemail varchar(60) NOT NULL default '',
  use_flush varchar(10) NOT NULL default 'YES',
  alert_visit CHAR( 1 ) DEFAULT 'N' NOT NULL,  
  membernum int (8) NOT NULL default '0', 
  offset INT(5) NOT NULL default '0'  
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
  $sql  =  "		
CREATE TABLE livehelp_departments (
  recno int(5) NOT NULL auto_increment,
  nameof varchar(30) NOT NULL default '',
  onlineimage varchar(100) NOT NULL default '',
  offlineimage varchar(100) NOT NULL default '',
  requirename char(1) NOT NULL default '',
  messageemail varchar(60) NOT NULL default '',
  PRIMARY KEY  (recno)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
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
$sql  =  "	
CREATE TABLE livehelp_operator_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  channel int(10) NOT NULL default '0',
  userid int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  bgcolor varchar(10) NOT NULL default '000000',
  PRIMARY KEY  (id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}	 

  $sql  =  "	
CREATE TABLE livehelp_quick (
  id int(10) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  typeof varchar(30) NOT NULL default '',
  message text NOT NULL,
  PRIMARY KEY  (id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
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
  showedup BIGINT( 14 ) NULL,
  email varchar(60) NOT NULL default '',  
  PRIMARY KEY  (user_id)
)";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}						
 		$results = mysql_query($insert_query2,$conn);
 		$results = mysql_query($insert_query3,$conn);
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
   <FORM ACTION=http://craftysyntax.com/installation.php Method=GET name=mine TARGET=_blank>
     <input type=hidden name=pr value=livehelp >
     <input type=hidden name=v value=<?= $version ?> >
     <input type=hidden name=d value=<?= $dbtype ?> >
     <input type=hidden name=i value=<?= $installationtype ?> >
     </FORM>
<SCRIPT>
  document.mine.submit();
</SCRIPT>
<?
print "<a href=index.php>CLICK HERE to get started.. </a></td></tr></table>";
exit;
}

?>
<script>
function tellme(){
 openwindow('http://craftysyntax.com/livehelp/bugs.php?from=livehelp','image460','scrollbars=yes,resizable=yes,width=500,height=400');
}

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
$fp = fopen ("config.php", "r+");
$fp2 = fopen ("livehelp.js", "r+");
if( (!$fp) || (!$fp2)){
?>
<table width=500 bgcolor=FFFFEE><tr><td>
<font color=990000 size=+2><b>Can not open <font color=000099>config.php</font> file and or <font color=000099>livehelp.js</font> for writing:</b></font><br>


<hr>
<font color=007700><b>HOW TO FIX THIS:</b></font><br><br>
In order to configure the Live Help using this web wizard,
 the web server needs to be able to read and write to the
 file named <b><i>config.php</i></b> and 
 file named <b><i>livehelp.js</i></b>. if you are planning on 
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
chmod 777 livehelp.js<br>
<br><br>
After installation you can change the permissions of config.php and 
livehelp.js to chmod 755.
<br><br>
if you can not ssh or telent into your website you can to the same task by
FTP: 
<br><br>	
Using WS_FTP this would be right hand clicking on the 
file config.php , selecting chmod, and then giving all
permissions to that file/directory. Then do the same
for the file named livehelp.js.
<br>
<img src=directions.gif>
<br>
NOTE: if you are on a windows HOST chmod will not do anything.
you will need to change the permissions of the file on the 
server by right hand clicking on the file and selecting permissions. 
<br>
</td></tr></table><br>
<a href=setup.php>AFTER YOU HAVE CHANGED THE PERMISSIONS OF THE 
FILES HOLD DOWN THE shift KEY and PRESS REFRESH or RELOAD</a>
<br>
<br><br>
<table bgcolor=FFFFFF><tr><td>
<h3>if you can not change the permissions of the files here are Manual Installation Directions:</h3>
<hr>
<br>
<pre>
------------------------------------------------------   
MANUAL INSTALLATION DIRECTIONS:
------------------------------------------------------
DO THIS ONLY IF YOU CAN NOT RUN THE SETUP.php and 
Plan on using MySQL as the database. if you want to
use txt-db-api you need to run the setup.php

1) Open up livehelp.js and change the line that 
   reads var WEBPATH = "WEB-PATH"; to be 
   the path to your livehelp installation.
   
2) Open up config.php and change the configuration 
   settings to match your configuration (mysql user, admin etc..)

3) Create a database on your Mysql server and 
   import tables.sql
   if you want to use the text based database you need to  

4) Log in as 
   username: admin
   password: admin
   
   YOU SHOULD CHANGE THIS AFTER LOGGING IN BY CLICKING PREFERENCES
   
   
If you have any problems you can visit the support pages:
http://www.craftysyntax.com/support/
</pre>
</td></tr></table>

</td></tr></table>
<?
exit;
}


?>
<FORM action=setup.php method=post name=erics>
<table width=600 bgcolor=FFFFEE>
<?

if ($site_title == ""){
  $site_title = "My Online Live Help";
}

?>
<tr><td bgcolor=DDDDDD colspan=2><b>INSTALLATION OPTION:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
You can upgrade to 
the newest version of the live help and not loose any of your data...<br>
</td></tr>
<tr><td>Installation:</td>
<td><select name=installationtype>
<option value=newinstall>NEW INSTLLATION</option>
<option value=upgrade <? if ($installationtype == "upgrade"){ print " SELECTED "; } ?> >UPGRADE from OLD Version</option>
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

It is highly recomended that you use a Mysql database to store the data. However,
if you do not have accesss to one, or can not install one then you can use 
text files to store the data (slower and more buggy)
</td></tr>

<tr><td>Database:</td><td><select name=dbtype>
<option value=mysql_options.php <? if ($dbtype == "mysql_options.php"){ print " SELECTED "; } ?> >MySQL </option>
<!-- <option value=MSaccess.php <? if ($dbtype == "MSaccess.php"){ print " SELECTED "; } ?> >Microsoft Access (Windows only) </option>-->
<option value=txt-db-api.php <? if ($dbtype == "txt-db-api.php"){ print " SELECTED "; } ?> >txt-db-api (simple Flat text files)</option>
</select></td></tr>
<tr><td colspan=2><ul>
<table bgcolor=FFFFCC>
<tr><td colspan=2>If <b>MySQL</b> is selected above:</td></tr>
<tr><td>MySQL server:</td><td><input type=text name=server size=20 value="<?= $server ?>" ></td></tr>
<tr><td>MySQL database:</td><td><input type=text name=database size=20 value="<?= $database ?>"></td></tr>
<tr><td>MySQL user:</td><td><input type=text name=datausername size=20 value="<?= $datausername ?>"></td></tr>
<tr><td>MySQL password:</td><td><input type=text name=mypassword size=20 value="<?= $mypassword ?>"></td></tr>
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
<tr><td colspan=2>If <b>Microsoft Access (WINDOWS ONLY)</b> is selected above you need to provide a 
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