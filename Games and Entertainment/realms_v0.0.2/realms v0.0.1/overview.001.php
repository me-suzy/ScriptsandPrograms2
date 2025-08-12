 <?php
if($stat[attack]<1){
        mysql_query("update characters set attack='1' where id='$stat[id]'");
        $stat[attack]=1;
}
if($stat[defend]<1){
        mysql_query("update characters set defend='1' where id='$stat[id]'");
        $stat[defend]=1;
}
if($stat[max_hp]<15){
        mysql_query("update characters set max_hp='15' where id='$stat[id]'");
        $stat[max_hp]=15;
}

print "<table border=1 cellspacing=0 cellpadding=2>";
print "<tr><td colspan=3>Active Character info:</td></tr>";
print "<tr><td>Name: <b>$stat[name]</b> </td>";
print "<td>level: <b>$stat[level]</b> </td>";
print "<td>realm: <b>$stat[realm]</b> </td></tr>";
print "<tr><td>race: <b>$stat[race]</b> </td>";
print "<td>job: <b>$stat[job]</b> </td>";
print "<td>job level: <b>$stat[job_level]</b> </td></tr>";
print "<tr><td>magic level: <b>$stat[magic_level]</b> </td>";
print "<td>magic type: <b>$stat[magic_type]</b> </td>";
$karma=$stat[karma]-50;
if($karma>0){
        $karmaword="<font color=\"#00CC00\">Good</font>";
}elseif($karma<0){
        $karmaword="<font color=\"#FF0000\">Evil</font>";
}else{
        $karmaword="<font color=\"#676767\">Neutral</font>";
}
$karma=abs($karma);
print "<td>karma: <b>$karma</b> ($karmaword) </td></tr>";
print "<tr><td>cash: <b>$stat[cash]</b> </td>";
print "<td>gems: <b>$stat[gems]</b> </td>";
print "<td>gold: <b>$stat[gold]</b> </td>";
print "<tr><td>ore: <b>$stat[ore]</b> </td></tr>";
print "<tr><td>Attack: <b>$stat[attack]</b> <i>($offstim)</i></td>";
print "<td>Defend: <b>$stat[defend]</b> <i>($defstim)</i><br></td>";
print "<td>Speed: <b>$stat[speed]</b> <i>($agestimit)</i></td></tr>";
print "<tr><td>Brains: <b>$stat[brains]</b> <i>($slssstimit)</i></td>";
print "<td>Luck: <b>$stat[luck]</b> <i>($lssstimit)</i></td></tr>";
print "<tr><td>HP: <b>$stat[hp]/$stat[max_hp]</b> <i>($mhpstimit)</i></td>";
print "<td>Energy: <b>$stat[energy]/$stat[max_energy]</b> <i>($nrgstimit)</i></td>";
print "<td>Mana: <b>$stat[mana]/$stat[max_mana]</b> <i>($mnastimit)</i><br></td></tr>";
print "</table><br>";

print "<table border=0 width=\"90%\">";
print "<tr><td width=\"50%\"><center>";

        print "<b>Stat points:</b> $stat[stat] (<a href=\"$GAME_SELF?p=statupgrade\">use</a>)<br>";
        print "<b>Battle points:</b> $stat[battle] <br>";
        print "<b>Forum points:</b> $user[forump] <br>";
        print "<b>Vote points:</b> $user[vp] <br>";
        print "<b>Quest points:</b> $stat[quest] <br>";
        print "<b>Referral points:</b> $user[glomps] <br>";
        print "<b>Account credits:</b> $user[credits] <br>";
        print "<b><a href=\"$GAME_SELF?p=pswap\">Convert Points</a></b><br>";

print "</center></td><td width=\"50%\"><center>";
include("skills.001.php");
print "</centeR></td></tr></table>";
print "<br><Br>";

include("character.001.php");


        ?>