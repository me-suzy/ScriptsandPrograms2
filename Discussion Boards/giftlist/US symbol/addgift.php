<?php
include "header.php";
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}
?>

<html>
<head>
<title>Add Gift</title>
</head>
<body>


<form action="addtodb.php" method="post">

Gift Name</td><br>
<input type="text" name="giftname" size=60 /></td><br><br>

Gift Price</td><br>
$<input type="decimal" name="giftprice" size=10 /></p></td><br>

Store/Web site URL</td><br>
<input type="text" name="gift_url_store" size=50 /></p></td><br>

Gift Comments</td><br>
<td><textarea rows=8 cols=50 name="gift_description"></textarea></td>
<br><br>
<? 
echo "Do you want to give people the option to mark gift as 'bought'? (something like money should be set to NO and it will remain on your list)"; 
?> <br><br> <?
echo "<select name=\"buyable\">"; 
echo "<option value=\"yes\">YES</option> "; 
echo "<option value=\"no\">NO</option>"; 
echo "</select> "; 

?> 






 <p><input type="submit" /></p>
</form>



</body>
</html>
