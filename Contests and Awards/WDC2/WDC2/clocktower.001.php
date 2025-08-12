<?php

$testtime[contestweek]="0";
$ncontestweek="0";

$rtime = time();
$testtime = mysql_fetch_array(mysql_query("select * from time"));


$acontestweek=7*24*60*60;
$gmt = $user[gmt]*60*60;
$ncontestweek=$testtime[contestweek]+$acontestweek;


if($showclock=="yes"){
$nowish = gmdate('D jS M - G:i:s',$rtime + $gmt);
$endish = gmdate('D jS M - G:i:s',$testtime[contestweek] + $gmt);


print"<table>
<tr>
<td>Now:</td><td>$nowish</td>
</tr>
<tr>
<td>Entries end:</td><td>$endish</td>
</tr>
</table>";
}

$nrvh=0;


if($testtime[contestweek]<=$rtime){
mysql_query("update `contest_contest` set `end`=2 where `end`=1");
mysql_query("update `time` set `contestweek`=$ncontestweek");
mysql_query("update `contest_contest` set `active`=2 where `active`=1");
mysql_query("update `contest_contest` set `end`=1 where `vote`=1");
mysql_query("update `contest_contest` set `vote`=2 where `vote`=1");
}



$showclock="yes";