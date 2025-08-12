<?php
include "header.php";
// require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");



// mysql_query("DELETE FROM gifts WHERE gift_id='$fileId'")
// or die(mysql_error());

$query="UPDATE gifts SET del_gift='no' WHERE gift_id='$fileId'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);


?>

<br><br>
Record Restored






