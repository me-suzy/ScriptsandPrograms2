
<?php require_once('Connections/poll.php'); ?>
<?php
mysql_select_db($database_poll, $poll);

$query = "SELECT * FROM flash_poll WHERE flash_poll.status=1"; 
$result = mysql_query($query,$poll) or die ("Error in query: $query. " . mysql_error());

if (mysql_num_rows($result) > 0) { 
    while($row = mysql_fetch_row($result)) {
echo "&ID=" ;echo $row[0];echo "&";
        echo   "question=";echo $row[1];echo "&";
        echo "answer_no="; echo $row[2];echo "&";
        for ($i=3;$i<3+$row[2];$i++) {echo "answer";echo $i-2;echo "=";echo $row[$i];if ($i<2+$row[2]){echo "&";}};
echo "&skin=".$row[20]."&";


    } 
} 



?> 