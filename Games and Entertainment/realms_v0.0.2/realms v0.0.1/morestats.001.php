<br> <center>
<?php

$onlinenowa=usersonline(10,on);
$onlineday=usersonline(1440,on);
$offlinefortnite=usersonline(50400,off);

print" <br><BR>
Online users : $onlinenowa";


print" <br><BR>
users online in the last 24 hours : $onlineday";


print" <br><BR>
users offline for the last 14 days : $offlinefortnite";





$nump = mysql_num_rows(mysql_query("select * from users"));
$numprealm = mysql_num_rows(mysql_query("select * from characters where realm='$stat[realm]'"));
print "<br><b>$numprealm</b>";

if($numprealm>1){
print" people in ";
}else{
print" person in ";
}


if(file_exists("realms.$stat[world].php")){
include("realms.$stat[world].php");
}else{
include("realms.001.php");
}
$new=rand(1,99999);
print"<br><b>$nump</b> people Play...<br>";



$totalgold="0";
$atpsel = mysql_query("select * from `users` where `position`!='Admin'");
while ($apl = mysql_fetch_array($atpsel)) {
                $csel=mysql_query("select * from `characters` where `owner`='$apl[id]'");
                while($c=mysql_fetch_array($csel)){
                $totalgold=$totalgold+$c[cash];
                }
$totalgold=$totalgold+$apl[bank];
$totalgold=$totalgold+$atuser[cash];
}
$avggold = $totalgold/$nump;
$avggold = round($avggold,2);

print"<br>There is $totalgold total credits in this game at this time<br>that is an average of $avggold per player<BR>";

$batpsel = mysql_query("select * from `users` where `position`!='Admin'");
$totalgold="0";
while ($bapl = mysql_fetch_array($batpsel)) {
                $csel=mysql_query("select * from `characters` where `owner`='$bapl[id]'");
                while($c=mysql_fetch_array($csel)){
                $totalgold=$totalgold+$c[cash];
                }
$totalgold=$totalgold+$bapl[bank];
$totalgold=$totalgold+$batuser[cash];
if($totalgold>$lastgold){
$biggest=$bapl[id];
$lastgold=$totalgold;
}

$totalgold="0";
}
$biggested = mysql_fetch_array(mysql_query("select * from users where id=$biggest"));

print "the richest player <b><u><a href=\"$GAME_SELF?p=view&amp;view=$biggested[id]\">$biggested[username]</a></u></b> has $lastgold total gold";









?>