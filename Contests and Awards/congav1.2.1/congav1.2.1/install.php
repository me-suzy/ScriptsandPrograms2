<?php
////////////////////////////////////////////////////////
// Conga Line Script v1.2.1
// ©2005 Nathan Bolender www.nathanbolender.com
// Licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License
// located at http://creativecommons.org/licenses/by-nc-nd/2.0/
////////////////////////////////////////////////////////

?>
<form action="install2.php" method="post" name="congainstall" id="congainstall"><?php
include ("config.php");
?>
<?php include "config.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Conga Line Script Install</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #FF0000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF0000;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #FF0000;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: small;
	color: #000000;
}
body {
	background-color: #FFFFFF;
}
-->
</style></head>

<body>
<table width="99%"  border="3" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td bgcolor="#999999"><div align="center"><strong>Conga Line Script Install</strong></div></td>
  </tr>
  <tr>
    <td height="107" align="left" valign="top" bgcolor="#CCCCCC"><p><br>
      Please fill out the following fields:</p>
      
	  
	  <table width="671"  border="2" cellpadding="1" cellspacing="0" bordercolor="#000000">
        <tr>
          <td width="115"><strong>Admin password</strong></td>
          <td width="357">
            <input name="password" type="password" id="password" maxlength="10">
          </td>
        </tr>
        <tr>
          <td><strong>Conga Name </strong></td>
          <td><input name="name" type="text" id="name" maxlength="30"> 
          Conga Line </td>
        </tr>
        <tr>
          <td height="60" align="left" valign="top"><p><strong>Conga Info<br>
          </strong><strong> (HTML permitted) </strong></p>          </td>
          <td><textarea name="info" cols="90" rows="10" id="info"></textarea></td>
        </tr>
      </table>      
	  <br>
	  <input type="submit" name="Submit" value="Submit">
	  <br>
	  <p>&nbsp; </p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>

</body>
</html>

<?php
/*
$result1 = mysql_query("CREATE TABLE `currentdj` (
  `dj` int(11) NOT NULL auto_increment,
  `active` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `password` text NOT NULL,
  `address` text NOT NULL,
  `aim` text NOT NULL,
  `msn` text NOT NULL,
  `yim` text NOT NULL,
  `icq` text NOT NULL,
  `alias1` text NOT NULL,
  `alias2` text NOT NULL,
  `alias3` text NOT NULL,
  PRIMARY KEY  (`dj`)
) TYPE=MyISAM AUTO_INCREMENT=1 ; ");

$result2 = mysql_query("CREATE TABLE `currentdj_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `setting` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ; ");

$result3 = mysql_query("INSERT INTO `currentdj_settings` VALUES (1, 'Mode', '0'); ");
$result4 = mysql_query("INSERT INTO `currentdj_settings` VALUES (2, 'Display how DJ was set', '0'); ");


if (($result1 == TRUE) && ($result2 == TRUE) && ($result3 == TRUE) && ($result4 == TRUE)) {

echo "Congratulations! The script was successfully installed!<br><b>Make sure you delete the admin/install folder before continuing to use the script.</b><br>Please click continue to enter the administration panel and add your DJs. <br><a href=\"../index.php\">Continue...</a>";

} else {

	echo "Sorry but the tables could not be created, please try again.";
} */
	?></form>