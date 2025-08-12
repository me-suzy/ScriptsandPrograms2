<?php
include "header.php";
?>

<html>
<head>
<title>Your Giftlists</title>
</head>
<body>


<?php


if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}



if ("$_SESSION[username]" == "$fileId") {
	die('You can not view your own gift list.');
}







$dbQuery = "SELECT gift_id, gift_name, gift_description, gift_url_store, buyer "; 

$dbQuery .= "FROM gifts WHERE username = '$fileId' AND buyer != 'no'"; 

$dbQuery .= "ORDER BY gift_name ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");



echo "Gifts already bought for ";echo "$fileId";

?>

<br>

<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="70%">

<tr>

<td width="30%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Name</font></b></td>




<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Comments</font></b></td>



<td width="43%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Store / Web site URL</font></b></td>







</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="30%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["gift_name"]; ?> 

</font> 

</td> 








<td width="30%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["gift_description"]; ?> 


</a></font> 

</td> 



<td width="50%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["gift_url_store"]; ?> 


</a></font> 

</td> 



</a></font> 

</td> 




</tr>

<?php 

}

echo "</table>"; 

?>


<?php



?>





</body>
</html>
