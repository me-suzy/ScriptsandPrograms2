<?
include "header.php";
// require('db_connect.php');	// database connect script.




$query="UPDATE gifts SET buyer='no', give_date='2090-01-01', giver='NULL'  WHERE gift_id='$fileId'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
echo "Record Updated";
mysql_close();
?>
<br><br>
Item Returned to users giftlist <BR><BR>
