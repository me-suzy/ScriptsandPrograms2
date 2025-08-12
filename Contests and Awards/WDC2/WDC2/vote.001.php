<?php

   if($user[position]=="Guest"){
   print"Guests cannot vote.";
   return;
   }

$contest = getvotenumber();

if($contest=="none"){
print"There is currently no active contest to vote for";
return;
}


if($_POST[act]=="Vote"){

mysql_query("delete from contest_votes where user=$user[id] and contest=$contest");

$ip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
foreach($_POST as $key => $value){
if($key!="act"){
$votes = explode("_",$key);

mysql_query("insert into contest_votes (`contest`, `entry`, `user`, `ip`, `type`)
values ('$contest', '$value', '$user[id]', '$ip', '$votes[0]')") or print("<br>Could not add $votes[0] vote for $value.");

}
}

}


print"<form name=\"vote\" action=\"$GAME_SELF?p=vote\" method=\"post\">";

$continfo = mysql_fetch_array(mysql_query("select * from contest_contest where id=$contest"));
print"<h1>$continfo[name]</h1>
<p>$continfo[des]</p>";



$ctest = mysql_query("select * from contest_entries where contest=$contest order by id asc");
print"<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";
$jump = 0;
$i = 0;
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
print"<td><center><input name=\"pop_$i\" type=\"checkbox\" value=\"$entry[id]\"><br>Popular</center></td>";
print"<td><center><input name=\"art_$i\" type=\"checkbox\" value=\"$entry[id]\"><br>Art</center></td>";
print"<td><center><input name=\"concept_$i\" type=\"checkbox\" value=\"$entry[id]\"><br>Concept</center></td>";
print"<tr>";
$popit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='pop'"));
$artit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='art'"));
$conit = @mysql_num_rows(mysql_query("select * from `contest_votes` where `entry`=$entry[id] and `type`='concept'"));
$comit = @mysql_num_rows(mysql_query("select * from `contest_comments` where `entry`=$entry[id]"));

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
$i++;
}

print"</tr><tr><td colspan=2><center><input type=\"submit\" name=\"act\" value=\"Vote\"></center></td><td><input type=\"reset\" value=\"Reset\"></td>";

print"</tr></table></center></form>";