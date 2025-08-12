<?php require_once('Connections/poll.php'); ?>
<?php
mysql_select_db($database_poll, $poll);
$optiune="count$opt";
$query = "SELECT * FROM flash_poll WHERE flash_poll.status=1"; 
$result = mysql_query($query,$poll) or die ("Error in query: $query. " . mysql_error());

if (mysql_num_rows($result) > 0) { 
    while($row = mysql_fetch_row($result)) {

if ($row[0]==$idf){$query1= "UPDATE flash_poll SET flash_poll.$optiune=flash_poll.$optiune+1 WHERE ID=$idf";} 
$result1 = mysql_query($query1) or die ("Error in query: $query1. " . mysql_error());



} 
}

mysql_close($connection); 
?> 