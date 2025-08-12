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




$dbQuery = "SELECT gift_id, gift_name, gift_price, gift_description, gift_url_store, buyer, buyable ";

$dbQuery .= "FROM gifts WHERE username = '$fileId' AND buyer = 'no' AND del_gift = 'no' AND buyable = 'yes' ";

$dbQuery .= "ORDER BY gift_name ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>
<div align="center">Click `Buy` next to the item you inted giving. If you later change your mind, return the item  </div>
<div align="center">to the wanted list by clicking `My Buy List' then clicking `Deselect'. </div>
<div align="center">Remember, keeping something on your buy list will prevent others from seeing it! </div>
<br>
<?
echo "<b>These gifts are still on</b>  ";echo "<b>$fileId</b>"; echo "<b>'s wanted list</b>"
?>


<br>

<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="90%">

<tr>

<td width="30%" bgcolor="#FF9900" height="21">

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


<td width="43%" bgcolor="#FF9900" height="21">

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF">

Buy ?</font></b></td>




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

<a href="date.php?fileId=<?php echo $row["gift_id"]; ?>">
Buy



</a></font>

</td>




</tr>

<?php

}

echo "</table>";

?>

<br><br><br>



<?php



$dbQuery = "SELECT gift_id, gift_name, gift_price, gift_description, gift_url_store, buyer, buyable ";

$dbQuery .= "FROM gifts WHERE username = '$fileId' AND buyer = 'no' AND del_gift = 'no' AND buyable = 'no' ";

$dbQuery .= "ORDER BY gift_name ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");



echo "<b>The following are gifts that can be purchased multiple times, so no `buy` option is available</b>"

?>

<br>

<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="90%">

<tr>

<td width="30%" bgcolor="#FF9900" height="21">

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

<td width="30%" bgcolor="#FFDCA8" height="21">

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







</tr>

<?php

}

echo "</table>";

?>














</body>
</html>