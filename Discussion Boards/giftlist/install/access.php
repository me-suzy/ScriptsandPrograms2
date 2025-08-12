<?php
require('db_connect.php');	// database connect script.
?>

<html> 
<head> 
<title>Post News</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
</head> 
<body bgcolor="#FFFFFF" text="#000000"> 
<form name="post news" method="post" action="showusergifts.php"> 
<p>Please enter password <input type="text" name="title"> 
<br> 
<p><input type="submit" name="Submit" value="Submit"></p> 
</form> 
</body> 
</html> 

<?php
if ("submit" == "adults") {
	die('You are not logged in so you can not view giftlists');
}


?>




</body>
</html>
