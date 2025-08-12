<?php
include "header.php";
//require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}
?>

<html>
<head>
<title>My Giftlists</title>
My Gift List
<br>
<a href="deleted.php"><font color="#D68960">Click to view deleted gifts</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
<a href="receiveditems.php"><font color="#D68960">Click to view received gifts</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp

</font>
<br><br>
</head>
<body>


<?php


$date=date("Y-m-d");




$dbQuery = "SELECT gift_id, gift_name, gift_price, gift_description, gift_url_store, give_date, del_gift, buyable "; 

$dbQuery .= "FROM gifts WHERE username=('$_SESSION[username]') AND give_date >= '$date' AND del_gift = 'no' "; 

$dbQuery .= "ORDER BY gift_name ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="90%">

<tr>

<td width="19%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Edit/Delete Gift</font></b></td>


<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Name</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Price</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Comments</font></b></td>



<td width="43%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Store / Web site URL</font></b></td>


<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Option to buy?</font></b></td>



</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="19%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

 
<a href="editgift.php?fileId=<?php echo $row["gift_id"]; ?>"> 
Edit/Delete Gift

</font> 

</td> 



<td width="20%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["gift_name"]; ?> 

</font> 

</td> 




<td width="13%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo '$',$row["gift_price"]; ?> 


</a></font> 

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


<td width="50%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["buyable"]; ?> 


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
