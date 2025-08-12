<?php
if(!$contest){
$contest = getcontestnumber();
}

if($contest=="none"){
print"There is currently no active contest";
return;
}

$continfo = mysql_fetch_array(mysql_query("select * from contest_contest where id=$contest"));
print"<h1>$continfo[name]</h1>
<p>$continfo[des]</p>";

$ctest = mysql_query("select * from contest_entries where contest=$contest order by id asc");
print"<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";
$jump = 0;
while($entry = mysql_fetch_array($ctest)){
$view = mysql_fetch_array(mysql_query("select * from users where id=$entry[user]"));
if($jump>=3){
$jump=0;
print"</tr><tr>";
}

print"<TD>";



print"<br>Artist : <a href=\"$GAME_SELF?p=view&amp;view=$entry[user]\">$view[username]</a>";

print"<br><a href=\"$GAME_SELF?p=entry&amp;view=$entry[id]\" alt=\"\"><img src=\"$entry[thumbnail]\" border=\"0\"></a>";
print"<center><table border=1 width=140><tr>";
$popit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='pop'"));
$artit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='art'"));
$conit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='concept'"));
$comit = @mysql_num_rows(mysql_query("select * from `contest_comments` where `entry`=$entry[id]"));

$popit = $popit + $entry[pop];
$artit = $artit + $entry[art];
$conit = $conit + $entry[con];


if(!$popit) { $popit=0; }
if(!$artit) { $artit=0; }
if(!$conit) { $conit=0; }

print"<td><center>$popit</center></td>";
print"<td><center>$artit</center></td>";
print"<td><center>$conit</center></td>";

print"<tr><td colspan=3>$comit Comments</td>";

print"</tr></table></center>";
print"</td>";
$jump++;
}

print"</tr></table></center>";