<?php require_once('Connections/poll.php'); ?>
<?php
mysql_select_db($database_poll, $poll);
$optiune="count$opt";
$query = "SELECT * FROM flash_poll WHERE flash_poll.status=1"; 
$result = mysql_query($query,$poll) or die ("Error in query: $query. " . mysql_error());


    while($row = mysql_fetch_row($result)) {



echo "&count_no=" ;echo $row[2];echo "&";
        for ($i=11;$i<11+$row[2];$i++) {echo "count";echo $i-10;echo "=";echo $row[$i];if ($i<10+$row[2]){echo "&";}}
echo "&";

    } 
 



?> 