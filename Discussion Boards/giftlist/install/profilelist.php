<?php
include "header.php";
// require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");

if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}
?>

<html>
<head>
<title>User Giftlists</title>
</head>
<body>

<?php




$dbQuery = "SELECT username, email, movies "; 

$dbQuery .= "FROM users WHERE username != ('$_SESSION[username]') "; 

$dbQuery .= "ORDER BY username ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


Select which user profile you would like to see
<br><br>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="50%">

<tr>

<td width="14%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

User Name</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Click To Select</font></b></td>

</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="14%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="1"> 

<?php echo $row["username"]; ?> 

</font> 

</td> 






<td width="13%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="1"> 

<a href="otherprofiles.php?fileId=<?php echo $row["username"]; ?>"> 

Select User 

</a></font> 

</td> 

</tr>

<?php 

}

echo "</table>"; 

?>


</body>
</html>