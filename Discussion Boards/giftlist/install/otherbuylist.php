<?php
include "header.php";
//require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");

?>

<html>
<head>
<title>Buy list</title>
List of gifts other people are buying:
<br><br>
</head>
<body>


<?php






$dbQuery = "SELECT username, gift_id, gift_name, gift_price, gift_description, gift_url_store, buyer "; 

$dbQuery .= "FROM gifts WHERE buyer!=('$_SESSION[username]') AND buyer!='no' AND username!=('$_SESSION[username]')  "; 

$dbQuery .= "ORDER BY username ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="80%">

<tr>

<td width="16%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Buyer</font></b></td>


<td width="14%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Name</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Price</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Comments</font></b></td>



<td width="23%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Is For:</font></b></td>




</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="11%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

 
<?php echo $row["buyer"]; ?> 

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



<td width="23%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["username"]; ?> 


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
