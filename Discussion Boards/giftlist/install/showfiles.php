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


// Define your username and password

$access = "password";

if ($_POST['txtPassword'] != $access) {

?>

<h2>Users Gift Lists</h2>

<p>This section is password protected.</p>



<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">


<p><label for="txtpassword">Enter Password:</label>
<br /><input type="password" title="Enter your password" name="txtPassword" /></p>

<p><input type="submit" name="Submit" value="Login" /></p>

</form>

<?php

}
else {

?>


<?php






$dbQuery = "SELECT DISTINCT username "; 

$dbQuery .= "FROM gifts WHERE username != ('$_SESSION[username]') AND del_gift='no' "; 

$dbQuery .= "ORDER BY username ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="70%">

<tr>

<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Users With Gift Lists</font></b></td>


<td width="18%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Wanted List</font></b></td>

 


<td width="18%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Bought List</font></b></td>

</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="20%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="1"> 

<?php echo $row["username"]; ?> 

</font> 

</td> 






<td width="13%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="1"> 

<a href="showusergifts.php?fileId=<?php echo $row["username"]; ?>"> 

Wanted List 

</a></font> 

</td> 


<td width="18%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="1"> 

<a href="boughtlist.php?fileId=<?php echo $row["username"]; ?>"> 

Bought List 

</a></font> 

</td> 


</tr>

<?php 

}

echo "</table>"; 

?>


<?php

}

?>





</body>
</html>
