<?php
/******************************************************************************
Power Banner Manager 1.5 !
(install.php file)

Copyright Armin Kalajdzija, 2002.
E-Mail: kalajdzija@hotmail.com
Web Site: http://www.ak85.tk
******************************************************************************/

include "pbmadmin/config.inc.php";
if (isset($done)) {
if (isset($hostname) and isset($database) and isset($db_login) and isset($db_pass)) {  // variable check

    if (isset($user_pass) and isset($user_pass2) and ($user_pass == $user_pass2)) {
        $user_pass3 = crypt($user_pass);
    $dbconn = mysql_connect($hostname, $db_login, $db_pass) or die("Could not connect");
    print "<div align='center'>Connected to host!<br>";

    mysql_select_db($database) or die("Could not select database");
    print "Database selected !<br>";
    
   if (isset($install_clean) and !isset($install_upgrade) and isset($user_login) and ($user_login <> "") and isset($user_pass) and ($user_pass <> "") and isset($new_user_name) and ($new_user_name <> "") and isset($new_user_email) and ($new_user_email <> "")) {
    $query = "DROP TABLE powerban";
    $result = mysql_query($query);
    $query = "DROP TABLE powerban_stats";
    $result = mysql_query($query);
    $query = "DROP TABLE powerban_auth";
    $result = mysql_query($query);

    $query = "CREATE TABLE powerban (name varchar(30) , src text , alt text , url text , visits char(3) DEFAULT '0' , id varchar(4) , type char(1) DEFAULT '1' , dis_times varchar(10) , dised_times varchar(10) DEFAULT '0' , added int(3) , uid char(3) , target varchar(10) , dtype varchar(5) , zone varchar(4))";
    $result = mysql_query($query) or die("Query failed");
    print "Main table 'powerban' is created !<br>";

    $query = "CREATE TABLE  powerban_auth (login text , password text , ip text , date datetime , permit char(1) , uid char(3) , language text)";
    $result = mysql_query($query) or die("Query failed");
    print "Table powerban_auth is created !<br>";
    
    $query = "CREATE TABLE powerban_stats_views (id varchar(4) , date date)";
    $result = mysql_query($query) or die("Query failed");
    print "Table powerban_stats_views is created !<br>";
    
    $query = "CREATE TABLE powerban_stats_visits (id int(10) unsigned DEFAULT '0' , host text , address text , agent text , datetime datetime , referer text)";
    $result = mysql_query($query) or die("Query failed");
    print "Table powerban_stats_visits is created !<br>";
    
    $query = "CREATE TABLE powerban_zones (zid varchar(4) , zname varchar(30) , uid varchar(4))";
    $result = mysql_query($query) or die("Query failed");
    print "Table powerban_zones is created !<br>";
    
    $query = "INSERT INTO powerban_auth (login, password, permit, uid, language) VALUES ('$user_login', '$user_pass3', '1', '1', 'english.inc.php')";
    $result = mysql_query($query) or die("Query failed");
    print "Administrator account created !<br>";


    }else if (isset($install_upgrade) and !isset($install_clean) and isset($new_user_name) and ($new_user_name <> "") and isset($new_user_email) and ($new_user_email <> "")) {

      $query = "ALTER TABLE powerban ADD target VARCHAR(10)";
      $result = mysql_query($query) or die("Query failed");
      $query = "ALTER TABLE powerban ADD dtype VARCHAR(5)";
      $result = mysql_query($query) or die("Query failed");
      $query = "ALTER TABLE powerban ADD zone VARCHAR(4)";
      $result = mysql_query($query) or die("Query failed");
      $query = "UPDATE powerban SET dtype='1|0'";
      $result = mysql_query($query) or die("Query failed");
      print "Main table 'powerban' is upgraded !<br>";
      
      $query = "CREATE TABLE powerban_stats_visits SELECT * FROM powerban_stats";
      $result = mysql_query($query) or die("Query failed");
      $query = "DROP TABLE powerban_stats";
      $result = mysql_query($query) or die("Query failed");
      print "Table 'powerban_stats' name changed to 'powerban_stats_visits !<br>";
      
      $query = "ALTER TABLE powerban_auth ADD language TEXT";
      $result = mysql_query($query) or die("Query failed");
      $query = "UPDATE powerban_auth SET language='english.inc.php'";
      $result = mysql_query($query) or die("Query failed");
      print "Table 'powerban_auth' is upgraded !<br>";
      
      $query = "CREATE TABLE powerban_zones (zid varchar(4) , zname varchar(30) , uid varchar(4))";
      $result = mysql_query($query) or die("Query failed");
      print "Table powerban_zones is created !<br>";
      
      $query = "CREATE TABLE powerban_stats_views (id varchar(4) , date date)";
      $result = mysql_query($query) or die("Query failed");
      print "Table powerban_stats_views is created !<br>";
    }
    if (isset($install_upgrade)) {
        $Installation = "Upgrade";
    }else{
        $Installation = "Clean Install";
    }
    $headers = "From: PBM Registration";
    $result = mail("limpman@lsinter.net","New User !","Registration Info:\nFull Name: $new_user_name\nE-Mail: $new_user_email\nInstallation: $Installation",$headers) or print "Registration Info couldn't be sent !";
    print "Registration Info Sent !<br>";
    
    mysql_close($dbconn);
    print "Power Banner Manager is installed :)<br><br>";
    print "<b>DON'T FORGET TO DELETE THIS SCRIPT AFTER INSTALATION !!!</b><br><br>";
    print "<a href='pbmadmin/admin.php'>To continue click here</a></div>";
    die;
    }else{
        print "<font face='Verdana' size='2'>Password doesn't match with repeated one !</font>";
        die;
    }


}else{
   print "<font face='Verdana' size='2'>You need to fill in every textbox !</font>";
   die;
}
}
?>
<html>
<head>
<title>Power Banner Manager Instalation !</title>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="installfrm" method="post" action="install.php">
  <table width="503" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#32587F">
    <tr bgcolor="#32587F">
      <td colspan="2" height="31"><font color="#FFFFFF" face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>
        Power Banner Manager Instalation !</b></font></td>
    </tr>
    <tr bordercolor="#FFFFFF" valign="bottom">
      <td colspan="2" height="32"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Administrator
        Information:</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Admin
        Login:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        <input type="text" name="user_login" size="30">
        </font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Admin
        Password:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        <input type="password" name="user_pass" size="30">
        </font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Admin
        Password (again):</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        <input type="password" name="user_pass2" size="30">
        </font></td>
    </tr>
    <tr valign="bottom" bordercolor="#FFFFFF">
      <td width="221" height="34"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Registration Field:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your Full Name:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="new_user_name" size="30"></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your E-Mail Address:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="new_user_email" size="30"></font></td>
    </tr>
    <tr valign="bottom" bordercolor="#FFFFFF">
      <td colspan="2" height="34"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">MySQL
        Information:</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Hostname:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo $hostname; ?></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Database:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo $database; ?></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Login:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo $db_login; ?></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Password:</font></td>
      <td width="276"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo $db_pass; ?></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221" height="2"><br><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Select installation type</font></td>
      <td width="276" height="2"><br><font face="Verdana, Arial, Helvetica, sans-serif" size="2">(only one selection)</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="221" height="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="checkbox" name="install_clean">Clean Install</font></td>
      <td width="276" height="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="checkbox" name="install_upgrade">Upgrade from 1.0 version <br>(admin account will not be changed)</font></td>
    </tr>

  </table>
  <p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Note:
    If you want to change any data, you will need to edit config.php and refresh
    this index.php :)</font></p>
  <p align="center">
    <input type="hidden" name="done" value="1">
    <input type="submit" name="install" value="Install">
    <input type="reset" name="Submit2" value="Clear Info">
  </p>
</form>
</body>
</html>


