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

$ptest=mysql_fetch_array(mysql_query("select * from contest_entries where contest=$contest order by pop desc limit 1"));
$poptest = mysql_query("select * from contest_entries where pop=$ptest[pop] and contest=$contest order by id asc");

$atest=mysql_fetch_array(mysql_query("select * from contest_entries where contest=$contest order by art desc limit 1"));
$arttest = mysql_query("select * from contest_entries where art=$atest[art] and contest=$contest order by id asc");

$ctest=mysql_fetch_array(mysql_query("select * from contest_entries where contest=$contest order by con desc limit 1"));
$contest = mysql_query("select * from contest_entries where con=$ctest[con] and contest=$contest order by id asc");

print"<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";

$jump = 1;
print"<TD>POPULAR WINNER(S)</TD>";
while($pentry = mysql_fetch_array($poptest)){
$view = mysql_fetch_array(mysql_query("select * from users where id=$pentry[user]"));
if($jump>=3){
$jump=0;
print"</tr><tr>";
}
print"<TD>";
print"<br>Artist : <a href=\"$GAME_SELF?p=view&amp;view=$pentry[user]\">$view[username]</a>";
print"<br><a href=\"$GAME_SELF?p=entry&amp;view=$pentry[id]\" alt=\"\"><img src=\"$pentry[thumbnail]\" border=\"0\"></a>";
print"<center><table border=1 width=140><tr>";
$comit = @mysql_num_rows(mysql_query("select * from `contest_comments` where `entry`=$pentry[id]"));
print"<td><center>$pentry[pop]</center></td>";
print"<td><center>$pentry[art]</center></td>";
print"<td><center>$pentry[con]</center></td>";

print"<tr><td colspan=3>$comit Comments</td>";

print"</tr></table></center>";
print"</td>";
$jump++;
}

print"</tr>";

$jump = 1;
print"<TD>ART WINNER(S)</TD>";
while($aentry = mysql_fetch_array($arttest)){
$view = mysql_fetch_array(mysql_query("select * from users where id=$aentry[user]"));
if($jump>=3){
$jump=0;
print"</tr><tr>";
}
print"<TD>";
print"<br>Artist : <a href=\"$GAME_SELF?p=view&amp;view=$aentry[user]\">$view[username]</a>";
print"<br><a href=\"$GAME_SELF?p=entry&amp;view=$aentry[id]\" alt=\"\"><img src=\"$aentry[thumbnail]\" border=\"0\"></a>";
print"<center><table border=1 width=140><tr>";
$comit = @mysql_num_rows(mysql_query("select * from `contest_comments` where `entry`=$aentry[id]"));
print"<td><center>$aentry[pop]</center></td>";
print"<td><center>$aentry[art]</center></td>";
print"<td><center>$aentry[con]</center></td>";

print"<tr><td colspan=3>$comit Comments</td>";

print"</tr></table></center>";
print"</td>";
$jump++;
}

print"</tr>";

$jump = 1;
print"<TD>CONCEPT WINNER(S)</TD>";
while($centry = mysql_fetch_array($contest)){
$view = mysql_fetch_array(mysql_query("select * from users where id=$centry[user]"));
if($jump>=3){
$jump=0;
print"</tr><tr>";
}
print"<TD>";
print"<br>Artist : <a href=\"$GAME_SELF?p=view&amp;view=$centry[user]\">$view[username]</a>";
print"<br><a href=\"$GAME_SELF?p=entry&amp;view=$centry[id]\" alt=\"\"><img src=\"$centry[thumbnail]\" border=\"0\"></a>";
print"<center><table border=1 width=140><tr>";
$comit = @mysql_num_rows(mysql_query("select * from `contest_comments` where `entry`=$centry[id]"));
print"<td><center>$centry[pop]</center></td>";
print"<td><center>$centry[art]</center></td>";
print"<td><center>$centry[con]</center></td>";

print"<tr><td colspan=3>$comit Comments</td>";

print"</tr></table></center>";
print"</td>";
$jump++;
}

print"</tr></table></center>";

