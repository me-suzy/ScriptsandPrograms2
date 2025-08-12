<?php
////////////////////////////////////////////////////////
// Conga Line Script v1.2.1
// ©2005 Nathan Bolender www.nathanbolender.com
// Licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License
// located at http://creativecommons.org/licenses/by-nc-nd/2.0/
////////////////////////////////////////////////////////

 include "config.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo "$name"; ?> Conga Line</title>
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
    <td bgcolor="#999999"><div align="center"><strong><?php echo "$name"; ?> Conga Line</strong></div></td>
  </tr>
  <tr>
    <td height="107" align="left" valign="top" bgcolor="#CCCCCC"><?php echo "$info"; ?>&nbsp;
    <p><a href="addlink.php">Add your link</a> | <a href="editlink.php">Edit your link</a><strong> <br>
        <br>
        Use the first link below to sign up for the offer <br>
        <br>
        Users in need</strong><br>These users still need referrals. Be sure to sign up with the first link, so newer people can move up the list.<br><Br><?php
		$query="SELECT * FROM conga_users WHERE status = '0' ORDER BY `id` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$username = $row['name'];
		$link = $row['link'];
		echo "<b>$username -</b> <a href=\"$link\" target =\"_blank\">$link</a><br>";
	}
	?><br><strong>Pending users</strong><br>These people have completed all needed referrals, but have yet to recieve the promised prize.<br><br>
	<?php
		$query="SELECT * FROM conga_users WHERE status = '1' ORDER BY `id` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$username = $row['name'];
		echo "<b>$username</b><br>";
	}
	?><br><strong>Completed Offers</strong><br>These people have recieved their prize from the offer.<br><br>
	<?php
		$query="SELECT * FROM conga_users WHERE status = '2' ORDER BY `id` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$username = $row['name'];
		echo "<b>$username</b><br>";
	}
	?><br>
    </p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>
</body>
</html>
