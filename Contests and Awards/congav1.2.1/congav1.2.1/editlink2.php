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
<title><?php echo "$name"; ?> Conga Line :: Edit Link</title>
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
	<b>Edit link</b></p>
	<?php  
$query="SELECT * FROM conga_users WHERE name = '$sname'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$link = $row['link'];
		$username = $row['name'];
		$pass = $row['password'];
	}
if ($password == $pass) {
?><form name="form1" method="post" action="editlink3.php">
    <table width="41%"  border="3" cellpadding="0" cellspacing="0" bordercolor="#000000">
      <tr>
        <td width="24%"><strong>Name</strong></td>
        <td width="76%"><input name="nname" type="text" id="nname" value="<?php echo "$username"; ?>"></td>
      </tr>
      <tr>
        <td><strong>Link</strong></td>
        <td><input name="nlink" type="text" id="nlink" value="<?php echo "$link"; ?>"></td>
      </tr>
      <tr>
        <td><strong>Status</strong></td>
        <td><select name="nstatus" id="nstatus">
            <option value="<?php echo "$status"; ?>">Keep Current</option>
            <option value="0">Need Referrals</option>
            <option value="1">All referrals done</option>
            <option value="2">Prize recieved</option>
        </select></td>
      </tr>
    </table>
    
    <p><input type="hidden" name="id" value="<?php echo "$id"; ?>">
      <input type="submit" name="Submit" value="Submit">
    </p>
	</form>    <?php
} else {
echo "<b>Incorrect password.</b>";
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