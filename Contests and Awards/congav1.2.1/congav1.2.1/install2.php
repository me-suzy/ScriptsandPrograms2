<?php
////////////////////////////////////////////////////////
// Conga Line Script v1.2.1
// ©2005 Nathan Bolender www.nathanbolender.com
// Licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License
// located at http://creativecommons.org/licenses/by-nc-nd/2.0/
////////////////////////////////////////////////////////

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
    <td height="107" align="left" valign="top" bgcolor="#CCCCCC"><p><?php

if ($password == $adminpass) {

$result1 = mysql_query("CREATE TABLE `conga_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=InnoDB AUTO_INCREMENT=2 ; ");

$result2 = mysql_query("CREATE TABLE `conga_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `link` text NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB; ");

$result3 = mysql_query("INSERT INTO `conga_settings` (`id`, `name`, `info`) VALUES (1, '$name', '$info'); ");


if (($result1 == TRUE) && ($result2 == TRUE) && ($result3 == TRUE)) {

echo "Congratulations! The script was successfully installed!<br><b>Make sure you delete install.php and install2.php before continuing to use the script.</b>";

} else {

	echo "Sorry but the tables could not be created, please try again. (Note--check the front page for your info. It may have gone through regardless of this message.)";
}

} else {
echo "<b>Incorrect password.</b>";
}
	?></p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>

</body>
</html>