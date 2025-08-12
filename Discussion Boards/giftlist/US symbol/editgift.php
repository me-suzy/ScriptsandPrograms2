<?php
include "header.php";
// require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");




$query="SELECT * FROM gifts WHERE gift_id = '$fileId'";
$result=mysql_query($query);
$num=mysql_numrows($result); 
mysql_close();

$i=0;
while ($i < $num) {
$gift_name=mysql_result($result,$i,"gift_name");
$gift_price=mysql_result($result,$i,"gift_price");
$gift_description=mysql_result($result,$i,"gift_description");
$gift_url_store=mysql_result($result,$i,"gift_url_store");



?>

<form action="giftupdated.php" method="post">
<input type="hidden" name="ud_gift_id" value="<? echo $fileId; ?>">

<br><tr><td>Gift Name:</td><br>
 <input type="text" name="ud_gift_name" size=40 value="<? echo $gift_name; ?>"><br>

<br><tr><td>Gift Price:</td><br>
 $<input type="text" name="ud_gift_price" size=10 value="<? echo $gift_price; ?>"><br>

<br><tr><td>Store/Web site URL:</td><br>
 <input type="text" name="ud_gift_url_store" size=50 value="<? echo $gift_url_store; ?>"><br>

<br><tr><td>Gift Comments</td><br>
<td><textarea rows=4 cols=40 name="ud_gift_description"><? echo $gift_description ?></textarea></td>

<br><br>
<? 
echo "Do you want to give people the option to mark gift as 'bought'? (something like money should be set to NO and it will remain on your list)"; 
?> <br><br> <?
echo "<select name=\"buyable\">"; 
echo "<option value=\"yes\">YES</option> "; 
echo "<option value=\"no\">NO</option>"; 
echo "</select> "; 

?> 


<br><br><br><br>
<input type="Submit" value="Update">
</form>

<br>
Click `Delete Record` below to remove record<br><br>
<a href="delgift.php?fileId=<?php echo "$fileId"; ?>">
<font color='#ffffff'>DELETE RECORD !
<br><br>
To restore item, go to view giftlist, then click to view deleted items. <br>




<?
++$i;
} 
?>