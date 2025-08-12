<?php

if($hidelog){
        mysql_query("update `log` set `read`='T' where `id`='$hidelog' and `owner`='$user[id]'");
        }

        if($hideall){
        mysql_query("update `log` set `read`='T' where `owner`='$user[id]'");
        }

$lsel = mysql_query("select * from `log` where `owner`=$user[id] and `read`='F' order by id desc limit 5");

$ecount=mysql_num_rows($lsel);
if($ecount>0){
print"<table border=1>";
}


while ($log = mysql_fetch_array($lsel)) {

$year = substr("$log[whenwas]", 0, 4);
$month = substr("$log[whenwas]", 5, 2);
$day = substr("$log[whenwas]", 8, 2);
$hour = substr("$log[whenwas]", 11, 2);
$minute = substr("$log[whenwas]", 14, 2);
$second = substr("$log[whenwas]", 17, 2);
//$when = "[$day-$month-$year] $hour:$minute";
$when = date("l dS of F Y h:i:s A", mktime ($hour  ,$minute ,$second ,$month ,$day ,$year));

        print "<tr><td><b>$log[log]</b></td><td><b>($when)</b></td><td><a href=\"$GAME_SELF?p=$p&amp;hidelog=$log[id]\">HIDE</a></td></tr>";

}

$ulognum = mysql_num_rows(mysql_query("select * from `log` where `owner`='$user[id]' and `read`='F'"));
if($ulognum>1){
print"<tr><td>&nbsp;</td><td><a href=\"$GAME_SELF?p=$p&amp;hideall=yes\">HIDE ALL</a><td>&nbsp;</td></tr>";
              }
if($ecount>0){
print"</table>";
}

?>