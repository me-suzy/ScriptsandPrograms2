<?php require_once('Connections/poll.php'); ?>
<?php
mysql_select_db($database_poll, $poll);
$query_Recordset1 = "SELECT * FROM flash_poll";

$Recordset1 = mysql_query($query_Recordset1, $poll) or die(mysql_error());



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Archive</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>Open/Close Poll </strong></p>
<p>&nbsp; </p>
<?php 
if (mysql_num_rows($Recordset1) > 0) { 
    while($row = mysql_fetch_row($Recordset1)) { 
        echo  $row[1] ;echo "  ";if ($row[19]==1){?><a href="close.php?idf=<?php echo $row[0];?>">Close</a><?php ; echo "<br>"; }
		else {?><a href="open.php?idf=<?php echo $row[0];echo "  " ?>">Open</a><?php ; echo "<br>";}
    } 
} 

?>


</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
