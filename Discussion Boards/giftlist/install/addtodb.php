<?php
include "header.php";
// require('db_connect.php');	// database connect script.
?>

<html>
<head>
<title>Add a gift</title>
</head>
<body>

<?php



if (!$_POST['giftname']) {
		die('You must specify a gift name');
	}





$query = "INSERT INTO gifts(username, gift_name, gift_price, gift_url_store, gift_description, buyable) 
VALUES ('$_SESSION[username]', '$giftname', '$giftprice', '$gift_url_store', '$gift_description', '$buyable') ";
mysql_query($query); 
echo "$giftname"; echo " added to database.";


// mysql_query ("INSERT INTO gifts (gift_name, gift_price) VALUES '$giftname','$giftprice')");
 

?>
</body>
</html>
<br><br><br>
<a href="displaymylist.php"><font color="#ffffff">Click to view your Gift List</font></a><font color="#ffffff">
