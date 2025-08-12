<?php

if($go=="back"){

$negmap = $stat[map] - 1;
$mapneg = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$negmap' limit 1"));

if($mapneg[id]){
mysql_query("update characters set map=map-1 where id=$stat[id]");
}

}elseif($go=="forward"){

$plusmap = $stat[map] + 1;
$mapplus = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$plusmap' limit 1"));

if($mapplus[id]){
mysql_query("update characters set map=map+1 where id=$stat[id]");
}

}


$stat = mysql_fetch_array(mysql_query("select * from characters where id='$user[activechar]'"));
$negmap = $stat[map] - 1;
$plusmap = $stat[map] + 1;
$mapneg = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$negmap' limit 1"));
$mapplus = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$plusmap' limit 1"));



$mapit = mysql_fetch_array(mysql_query("select * from `map` where `realm`='$stat[realm]' and `tile`='$stat[map]' limit 1"));

print"<center><table><tr>";

if($mapneg[id]){
print"<td><font size=10><a href=$GAME_SELF?p=map&amp;go=back>&laquo;</a></font></td>";
}

print"<td><img src=mapimg.php></td>";

if($mapplus[id]){
print"<td><font size=10><a href=$GAME_SELF?p=map&amp;go=forward>&raquo;</a></font></td>";
}

print"</tr></table></center>";