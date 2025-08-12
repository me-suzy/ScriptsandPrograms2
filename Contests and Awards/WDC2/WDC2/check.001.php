<?php
$tcontest = getwinnumber();
if(!$contest){
$contest = $tcontest;
}

if($contest>$tcontest){
print"That contest is invalid";
return;
}

if($contest=="none"){
print"There are currently no winners.... huh?";
return;
}

$continfo = mysql_fetch_array(mysql_query("select * from contest_contest where id=$contest"));
print"<h1>$continfo[name]</h1>
<p>$continfo[des]</p>";

$ctest = mysql_query("select * from contest_entries where contest=$contest order by id asc");

while($entry = mysql_fetch_array($ctest)){
$popit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='pop'"));
$artit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='art'"));
$conit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='concept'"));

mysql_query("update `contest_entries` set `pop`=pop+$popit where `id`=$entry[id]");
mysql_query("update `contest_entries` set `art`=art+$artit where `id`=$entry[id]");
mysql_query("update `contest_entries` set `con`=con+$conit where `id`=$entry[id]");

mysql_query("delete from `contest_votes` where `entry`=$entry[id] and `type`='pop'");
mysql_query("delete from `contest_votes` where `entry`=$entry[id] and `type`='art'");
mysql_query("delete from `contest_votes` where `entry`=$entry[id] and `type`='concept'");
print"<br>$entry[id] - $popit - $artit - $conit";
}

print"done... hopefully";