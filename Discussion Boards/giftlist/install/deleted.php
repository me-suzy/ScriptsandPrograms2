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
Deleted Gifts - Click `Restore Gift` to return item to your Gift List
<br><br>
</head>
<body>


<?php


$date=date("Y-m-d");




$dbQuery = "SELECT gift_id, gift_name, gift_price, gift_description, gift_url_store, give_date, del_gift "; 

$dbQuery .= "FROM gifts WHERE username=('$_SESSION[username]') AND give_date >= '$date' AND del_gift = 'yes' "; 

$dbQuery .= "ORDER BY gift_name ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="80%">

<tr>

<td width="11%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Restore Gift</font></b></td>


<td width="14%" bgcolor="#FF9900" height="21"> 

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



</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="16%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

 
<a href="restore.php?fileId=<?php echo $row["gift_id"]; ?>"> 
Restore Gift

</font> 

</td> 



<td width="14%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["gift_name"]; ?> 

</font> 

</td> 




<td width="13%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo '£',$row["gift_price"]; ?> 


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



</tr>

<?php 

}

echo "</table>"; 

?>


<?php



?>





</body>
</html>
