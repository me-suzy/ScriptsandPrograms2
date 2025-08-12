<?php
include "header.php";
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}
 

?>

<html>
<head>

</head>

<br>
<center>
  <table border="2" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFFFFF" width="75%" id="AutoNumber1" height="144">
    <tr>
      <td width="100%" height="144">
      <p align="center"><font color="#FFFFFF" size="4">Welcome</font>&nbsp&nbsp<font size='4' color='#ffffff'><? echo "$_SESSION[username]" ?></p>
      
<p align="center"><font size="4" color="#FFFFFF">Points to remember:</font></p>
<p align="center"><font size="3" color="#FFFFFF"> Always keep your gift list up to date - delete unwanted items !</p>
<p align="center"><font size="3" color="#FFFFFF"> Keep an eye on your `buy` list, deselect items you no longer intend to buy, this will return them to the users wanted list.</p>


      <p align="center"></td>
    </tr>
  </table>
  </center>
</div>

</body>

<br>

<p align="center"><font size="4" color="#FFFFFF"> Latest additions to users gift lists:</p>


<?php


$dbQuery = "SELECT gift_id, username, gift_name, gift_price, gift_description, gift_url_store, give_date, buyer, del_gift, buyable "; 

$dbQuery .= "FROM gifts WHERE del_gift = 'no' AND buyer='no' "; 

$dbQuery .= "ORDER BY gift_id DESC LIMIT 0,10";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="90%">

<tr>

<td width="16%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift List Owner</font></b></td>


<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Name</font></b></td>


<td width="10%" bgcolor="#FF9900" height="21"> 

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

<?php echo $row["username"]; ?> 

</font> 

</td> 



<td width="20%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["gift_name"]; ?> 

</font> 

</td> 




<td width="10%" bgcolor="#FFDCA8" height="21"> 

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




</tr>

<?php 

}

echo "</table>"; 

?>




