<?php


$ctest = mysql_query("select * from characters order by level desc");
print"<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";
$jump = 0;
while($char = mysql_fetch_array($ctest)){
$view = mysql_fetch_array(mysql_query("select * from users where id=$char[owner]"));
if($jump>=3){
$jump=0;
print"</tr><tr>";
}

print"<TD>";



if($char[id]!=$view[activechar]){
print"$char[name]";
}else{
print"$char[name] (active)";
}


print"<br>Owner : <a href=\"$GAME_SELF?p=view&amp;view=$char[owner]\">$view[username]</a>
<br>Level : $char[level]
<br>race : $char[race]
<br>class : ";

$charlookup=$char[id];
include("charclass.001.php");
$charlookup=$stat[id];



if($char[sex]=="female"){
print"<br>sex: Female";
}else{
print"<br>sex: Male";
}

if($char[id]!=$view[activechar]){
$time=time();
$mins=60;
$yayb="0";
$yaya="0";
$yayd="0";
$ctime = time();
$yay = $ctime-$char[lastseen];
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

print"<br>Last Active: ";
if(!$char[lastseen]||$char[lastseen]=="0"||$char[lastseen]==""){
print"never";
}elseif($yayd>0){
print "$yayd days and $yaya hours and $yayb minutes ago.<br>";
}elseif($yaya>0){
print "$yaya hours and $yayb minutes ago.<br>";
}elseif($yayb>0){
print "$yayb minutes ago.<br>";
}else{
print "$yay seconds ago.<br>";
}
}



print"</td>";
$jump++;
}

print"</tr></table></center>";