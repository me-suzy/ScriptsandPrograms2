<?php

if ($stat[hp]<$stat[max_hp]) {
$nrgneed = ($stat[max_hp] - $stat[hp]);
$crneed = ($nrgneed*42.3 + 100);
if ($crneed > $stat[cash]) {
        print "You cannot afford to heal here. You need <b>$crneed</b> credits.";
}elseif (!$action) {
        print "Hey nurse, <a href=$GAME_SELF?p=hospital&amp;action=heal>heeeeaaaalll</a> me!";
        print "<br>Yeah, sure, it'll be <b>$crneed</b> credits, though.";
}elseif ($action == heal) {

$nrgneed = ($stat[max_hp] - $stat[hp]);
$crneed = ($nrgneed*42.3 + 100);
if ($crneed > $stat[cash]) {
   print "You cannot afford to be healed here.";
}else{
mysql_query("update characters set hp=max_hp where id=$stat[id]");
mysql_query("update characters set cash=cash-$crneed where id=$stat[id]");
print "<br>You are now healed.";
}

}
} else {
print"Go away healthy fool";
}

?>