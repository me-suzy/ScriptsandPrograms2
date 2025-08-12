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
<title>Conga Line Script Admin</title>
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
    <td bgcolor="#999999"><div align="center"><strong>Conga Line Script Admin</strong></div></td>
  </tr>
  <tr>
    <td height="107" align="center" valign="middle" bgcolor="#CCCCCC"><p>
	
	<?php
	if ($password == $adminpass) {
	?>
	<b>Edit users</b></p>
      <form name="form1" method="post" action="edituser2.php">
        Choose a user to edit:<br>        
        <select name="id">
          <option>--Choose a user--</option>
        
     
      <p>	  <?php
		$query="SELECT * FROM conga_users ORDER BY `id` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$username = $row['name'];
		$status = $row['status'];
		if ($status == 0) {
		$status1 = "Needs referrals";
		}
		if ($status == 1) {
		$status1 = "Pending";
		}
		if ($status == 2) {
		$status1 = "Completed";
		}
		echo "<option value=\"$id\">$username - $status1</option>";
	}
	?>
	</select><br><input type="hidden" value="<?php echo "$password"; ?>" name="password">
	<input type="submit" name="Submit" value="Submit">
      </form><br>
      <a href="adminlogin2.php?password=<?php echo "$password"; ?>">Main</a>
	  <?php
	} else {
	echo "<b>Incorrect password.</b>";
	}
	?>
	  
	
	  
	
	    </p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>

</body>
</html>