<?php

if ($stat[energy]<$stat[max_energy]) {
$nrgneed = ($stat[max_energy] - $stat[energy]);
$crneed = ($nrgneed*30.2 + 100);
if ($crneed > $stat[cash]) {
        print "You cannot afford to sleep here. You need <b>$crneed</b> credits.";
}elseif (!$action) {
        print "Hey innkeeper lemme <a href=$GAME_SELF?p=inn&amp;action=heal>sleeeeep!</a>";
        print "<br>Yeah, sure, it'll be <b>$crneed</b> credits, though.";
}elseif ($action == heal) {

$nrgneed = ($stat[max_energy] - $stat[energy]);
$crneed = ($nrgneed*30.2 + 100);
if ($crneed > $stat[cash]) {
   print "You cannot afford to sleep here.";
}else{
mysql_query("update characters set energy=max_energy where id=$stat[id]");
mysql_query("update characters set cash=cash-$crneed where id=$stat[id]");
print "<br>You are now rested.";
}

}
} else {
print"Go away energetic fool";
}

?>