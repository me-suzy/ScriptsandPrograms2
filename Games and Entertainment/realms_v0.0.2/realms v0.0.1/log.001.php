<br>
 <table border=1>
<?php

if($clear==yes){
print"<tr><td>&nbsp;</td><td>CLEARED <br><td>&nbsp;</td></tr>";
        mysql_query("delete from `log` where `owner`='$user[id]'");
}

if($dellog){
print"<tr><td>&nbsp;</td><td>CLEARED id$dellog<br><td>&nbsp;</td></tr>";
        mysql_query("delete from `log` where `owner`='$user[id]' and `id`='$dellog'");
}

$lsel = mysql_query("select * from `log` where `owner`='$user[id]' order by id desc limit 500");

while ($log = mysql_fetch_array($lsel)) {

$year = substr("$log[whenwas]", 0, 4);
$month = substr("$log[whenwas]", 5, 2);
$day = substr("$log[whenwas]", 8, 2);
$hour = substr("$log[whenwas]", 11, 2);
$minute = substr("$log[whenwas]", 14, 2);
$second = substr("$log[whenwas]", 17, 2);
//$when = "[$day-$month-$year] $hour:$minute";

//2005-02-16 22:51:34



$when = date("l dS of F Y h:i:s A", mktime ($hour  ,$minute ,$second ,$month ,$day ,$year));

        if($log[read]=='F'){
        print "<tr><td><b>$log[log]</td><td>($when)</b></td><td><a href=$GAME_SELF?p=log&dellog=$log[id]>Delete</a></td></tr>";
        mysql_query("update log set read='T' where id='$log[id]' and owner=$user[id]");
                }else{
        print "<tr><td>$log[log]</td><td>($when)</td><td><a href=$GAME_SELF?p=log&dellog=$log[id]>Delete</a></td></tr>";

        }
}
$lognum = mysql_num_rows(mysql_query("select * from `log` where `owner`='$user[id]'"));
if($lognum>1){
print"<tr><td>&nbsp;</td><td><a href=$GAME_SELF?p=log&clear=yes>CLEAR ALL</a><td>&nbsp;</td></tr>";
}
?>


</table>