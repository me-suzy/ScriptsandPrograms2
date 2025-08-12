<?php
$view = mysql_fetch_array(mysql_query("select * from users where id=$view"));
if (empty ($view[id])) {
        print "No such player.";
}else{

$time=time();
$mins=60;
$yayb="0";
$yaya="0";
$yayd="0";
$ctime = time();
$yay = $ctime-$view[lastseen];
$yayb = $yay/$mins;
$yayb = floor($yayb);

while($yayb>=60){
$yayb=$yayb-60;
$yaya=$yaya+1;
}

while($yaya>=24){
$yaya=$yaya-24;
$yayd=$yayd+1;
}

print "<center><b><u>$view[username]</b></u> <font size=1>($view[id])</font></center><br>";
print"<br>";
if($yayd>0){
print "Last Seen: <a href=$GAME_SELF?p=$view[page]>$view[page]</a>, $yayd days and $yaya hours and $yayb minutes ago.<br>";
}elseif($yaya>0){
print "Last Seen: <a href=$GAME_SELF?p=$view[page]>$view[page]</a>, $yaya hours and $yayb minutes ago.<br>";
}elseif($yayb>0){
print "Last Seen: <a href=$GAME_SELF?p=$view[page]>$view[page]</a>, $yayb minutes ago.<br>";
}else{
print "Last Seen: <a href=$GAME_SELF?p=$view[page]>$view[page]</a>,  $yay seconds ago.<br>";
}


$ctest = mysql_query("select * from contest_entries where user=$view[id] order by id asc");
print"<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";
$jump = 0;
while($entry = mysql_fetch_array($ctest)){
if($jump>=3){
$jump=0;
print"</tr><tr>";
}

print"<TD>";
$contest = mysql_fetch_array(mysql_query("select * from contest_contest where id=$entry[contest]"));


print"<br>Contest : <a href=\"$GAME_SELF?p=contest&amp;contest=$entry[contest]\">$contest[name]</a>";

print"<br><a href=\"$GAME_SELF?p=entry&amp;view=$entry[id]\" alt=\"\"><img src=\"$entry[thumbnail]\" border=\"0\"></a>";

print"<center><table border=1 width=140><tr>";
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
}

print"</tr></table></center>";



print"<br>More information soon.<br>";


print"<br><a href=$GAME_SELF?p=mail&view=write&to=$view[id]>Send Message</a>";
}
?>