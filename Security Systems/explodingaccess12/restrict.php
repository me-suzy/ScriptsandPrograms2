<?php // restrict.php

/***************************************************************************
 *                                ExplodingAccess 1.2
 *                            -------------------
 *   created:                : Friday, Jan 16th 2004
 *   copyright               : (C) 2004 Blue-Networks / Exploding Panda
 *   email                   : admin@blue-networks.net
 *   web                     : http://blue-networks.net
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

include("common.php");
include("conf.php");

session_start();

if(!isset($uid)) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- this is the login html -->
<head>
<title>Please Login:</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-size: 12px;
}
.style2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style4 {font-size: 10px}
-->
</style></head>

<body>
<div align="center">
  <table width="600" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#000000">
    <tr>
      <td height="18" valign="top" bgcolor="#003366"><div align="left"><strong><span class="style1">: Hold it right there! </span></strong></div></td>
    </tr>
    <tr>
      <td height="253" valign="top" bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="24%"><img src="padlock.jpg" width="140" height="195"></td>
          <td width="76%" valign="top"><div align="left">
            <p class="style2">This are of the site is protected with ExplodingAccess. If you do not have access, please contact the site administrator, who will hook you up if necessary. If you do, please login below.</p>
            <p class="style2"><form method="post" action="<?=$_SERVER['PHP_SELF']?>">
                <table width="285" border="0" cellpadding="2" cellspacing="1">
                  <tr> 
                    <td width="91"><span class="style3"><font size="2">User 
                      ID:</font></span></td>
                    <td width="187"><span class="style3"><font size="2"> 
                      <input type="text" name="uid" size="10">
                      </font></span></td>
                  </tr>
                  <tr> 
                    <td><span class="style3"><font size="2">Password: 
                      </font></span></td>
                    <td class="style1"><span class="style3"><font size="2"> 
                      <input type="password" name="pwd" size="10">
                      </font></span></td>
                  </tr>
                  <tr> 
                    <td><span class="style3"><font size="1">
                      <input name="Submit" value="Submit" type="submit" id="Submit">
                      </font> </span></td>
                    <td><span class="style3"></span></td>
                  </tr>
                </table>
              </form> </p>
              <font face="arial, tahoma" color="#000000" size="2"><a href="<?php echo($forumurl);?>">Forgot Password</a></font> </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p class="style3 style4">Powered By ExplodingAccess V1.2 </p>
</div>
</body>
</html>
<?php
exit;
}

session_register("uid");
session_register("pwd");

$pwd = str_replace( '$', "$", $pwd);
 
if ( get_magic_quotes_gpc() )
{
 $pwd = stripslashes($pwd);
}

$pwd = preg_replace( "/\\\(?!&#|?#)/", "\\", $pwd );

$md5p = md5($pwd);
$luser = strtolower($uid);
dbConnect($dbname);

$sqlgroup = "SELECT g_id FROM ibf_groups WHERE g_title = '$allowedgroup'";
$resultgroup = mysql_query($sqlgroup);
if (!$resultgroup) {
  error("Error:".            
  "Group $allowedgroup not found.");
}
$allowgroup = mysql_result($resultgroup,0);

  
$sql = "SELECT * FROM ibf_members WHERE        
		name = '$luser' AND password = '$md5p' AND mgroup in (4,$allowgroup) ";
$result = mysql_query($sql);

if (!$result) {
  error("A database error occurred while checking your ".        
  "login details.\\nIf this error persists, please ".        
  "contact the site admin.");
}

if (mysql_num_rows($result) == 0) {  
  session_unregister("uid");  
  session_unregister("pwd");
  
  unset($uid);
  unset($pwd);
  
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- html for if the pass is wrong -->
<head>
<title>Error:</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-size: 12px;
}
.style2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.style3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
-->
</style></head>

<body>

<div align="center">
  <table width="600" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#000000">
    <tr>
      <td height="18" valign="top" bgcolor="#003366"><div align="left"><strong><span class="style1">: Hold it right there! </span></strong></div></td>
    </tr>
    <tr>
      <td height="253" valign="top" bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="24%"><img src="padlock.jpg" width="140" height="195"></td>
          <td width="76%" valign="top"><div align="left">
            <p align="center" class="style2">Fatal Error!</p>
            <p align="left" class="style2">Your username / password combination was not recognised. </p>
            <p align="left" class="style2">Click <a href="<?=$PHP_SELF?>">here</a> to try again.</p>
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p class="style3">Powered By ExplodingAccess V1.2 </p>
</div>
</body>
</html>
<?php
  exit;
}
?>