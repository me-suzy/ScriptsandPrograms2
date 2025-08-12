

<?php

$cexp[1]=$lvlmhpstimit-$stat[max_hp];
$cexp[2]=$lvlnrgstimit-$stat[max_energy];
$cexp[3]=$lvlmnastimit-$stat[max_mana];
$cexp[4]=$lvloffstim-$stat[attack];
$cexp[5]=$lvldefstim-$stat[defend];
$cexp[6]=$lvlslssstimit-$stat[brains];
$cexp[7]=$lvllssstimit-$stat[luck];
$cexp[8]=$lvlagestimit-$stat[speed];


$dexp[1]=$mhpstimit-$stat[max_hp];
$dexp[2]=$nrgstimit-$stat[max_energy];
$dexp[3]=$mnastimit-$stat[max_mana];
$dexp[4]=$offstim-$stat[attack];
$dexp[5]=$defstim-$stat[defend];
$dexp[6]=$slssstimit-$stat[brains];
$dexp[7]=$lssstimit-$stat[luck];
$dexp[8]=$agestimit-$stat[speed];

foreach ($dexp as $key => $value) {
$gnexp = $gnexp+$value;
print"<!-- $key : $value - $gnexp --> \n
";
}


        print "Here, you can use stat points to increase your stats. Just click to add. You have <b>$stat[stat]</b> stat points left.";


        print"<form method=post action=\"$GAME_SELF?p=statupgrade&amp;upgrade=yes\">
        <table><tr>";
        print"<th>Stat</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;&nbsp;max&nbsp;&nbsp;</th><th> &nbsp;&nbsp;needed&nbsp;&nbsp; </th>
        </tr><tr>";
        if($dexp[1]>0){
        print"<td>max_hp </td><td> x </td><td><input type=text name=max_hp size=3 maxlength=3></td><td><center>$dexp[1]</center></td><td><center>$cexp[1]</center></td>
        </tr><tr>";
        }
        if($dexp[2]>0){
        print"<td>max_energy </td><td> x </td><td><input type=text name=max_energy size=3 maxlength=3></td><td><center>$dexp[2]</center></td><td><center>$cexp[2]</center></td>
        </tr><tr>";
        }
        if($dexp[3]>0){
        print"<td>max_mana </td><td> x </td><td><input type=text name=max_mana size=3 maxlength=3></td><td><center>$dexp[3]</center></td><td><center>$cexp[3]</center></td>
        </tr><tr>";
        }
        if($dexp[4]>0){
        print"<td>attack </td><td> x </td><td><input type=text name=attack size=3 maxlength=3></td><td><center>$dexp[4]</center></td><td><center>$cexp[4]</center></td>
        </tr><tr>";
        }
        if($dexp[5]>0){
        print"<td>defend </td><td> x </td><td><input type=text name=defend size=3 maxlength=3></td><td><center>$dexp[5]</center></td><td><center>$cexp[5]</center></td>
        </tr><tr>";
        }
        if($dexp[6]>0){
        print"<td>brains </td><td> x </td><td><input type=text name=brains size=3 maxlength=3></td><td><center>$dexp[6]</center></td><td><center>$cexp[6]</center></td>
        </tr><tr>";
        }
        if($dexp[7]>0){
        print"<td>luck </td><td> x </td><td><input type=text name=luck size=3 maxlength=3></td><td><center>$dexp[7]</center></td><td><center>$cexp[7]</center></td>
        </tr><tr>";
        }
        if($dexp[8]>0){
        print"<td>speed </td><td> x </td><td><input type=text name=speed size=3 maxlength=3></td><td><center>$dexp[8]</center></td><td><center>$cexp[8]</center></td>
        </tr><tr>";
        }
        if($gnexp>0){
        print"<td colspan=\"5\"><input type=submit value=go></td>";
        }else{
        print"<td colspan=\"5\"> $gnexp Either no stats to upgrade, or there is an error.... most probably error</td>";
        }
        print"</tr></table></form>";



        if ($upgrade=="yes") {

if($max_hp>$dexp[1]){
$max_hp=$dexp[1];
}

if($max_energy>$dexp[2]){
$max_energy=$dexp[2];
}

if($max_mana>$dexp[3]){
$max_mana=$dexp[3];
}

if($attack>$dexp[4]){
$attack=$dexp[4];
}

if($defend>$dexp[5]){
$defend=$dexp[5];
}

if($brains>$dexp[6]){
$brains=$dexp[6];
}

if($luck>$dexp[7]){
$luck=$dexp[7];
}

if($speed>$dexp[8]){
$speed=$dexp[8];
}

        $tog=$attack+$defend+$speed+$brains+$luck+$max_hp+$max_energy+$max_mana;

        if ($stat[stat] < $tog || $stat[stat] <= 0 || $tog <=0) {
                print "You don't have enough statpoints.";
        }else{

mysql_query("update characters set attack=attack+$attack where id=$stat[id]");
mysql_query("update characters set defend=defend+$defend where id=$stat[id]");
mysql_query("update characters set speed=speed+$speed where id=$stat[id]");
mysql_query("update characters set brains=brains+$brains where id=$stat[id]");
mysql_query("update characters set luck=luck+$luck where id=$stat[id]");
mysql_query("update characters set max_hp=max_hp+$max_hp where id=$stat[id]");
mysql_query("update characters set max_energy=max_energy+$max_energy where id=$stat[id]");
mysql_query("update characters set max_mana=max_mana+$max_mana where id=$stat[id]");



        mysql_query("update characters set stat=stat-$tog where id=$stat[id]");

        print "<meta http-equiv='refresh' content='0; url=$PHP_SELF?p=overview'>";
}
}
?>