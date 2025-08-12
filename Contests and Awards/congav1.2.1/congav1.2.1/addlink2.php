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
<title><?php echo "$name"; ?> Conga Line :: Add Link</title>
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
    <td bgcolor="#999999"><div align="center"><strong><?php echo "$name"; ?> Conga Line :: Add Link</strong></div></td>
  </tr>
  <tr>
    <td height="107" align="center" valign="middle" bgcolor="#CCCCCC"><p>
	<b>Add link</b></p>
	  
	<?php
$npass1 = $_POST['npass1'];
$npass2 = $_POST['npass2'];


	if ($npass1 == $npass2) {
	
	$query="SELECT * FROM conga_users WHERE link = '$nlink' ORDER BY `id` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$linkm = $row['link'];
	}
	
	if (empty($linkm)) {
	
	$resultID = mysql_query("INSERT INTO conga_users (name, password, link, status) VALUES ('$nname', '$npass1', '$nlink', '0')") or die(mysql_error());
	if ($resultID == TRUE) {
		print "Your link has been added!";
	} else {
		print "Sorry, but your link was not added to the database. Please try again.";
	}
	
	
	} else {
	echo "<b>This link already exists!</b>";
	}
	
	
	
	
	} else {
	echo "<B>Passwords do not match.</b>";
	}
	?>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>

</body>
</html>