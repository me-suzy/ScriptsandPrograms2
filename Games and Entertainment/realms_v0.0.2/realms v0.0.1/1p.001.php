<?php
if($challenge){
        $sp=mysql_fetch_array(mysql_query("select * from 1p where id='$challenge'"));
        $numfight=mysql_num_rows(mysql_query("select * from 1p where owner='$stat[id]'"));
        $numequip=mysql_num_rows(mysql_query("select * from items where owner='$user[id]' and equip!='0'"));
        if($sp[owner]!=0){
                print "<b>Someone else is already fighting that</b><br><br>";
        }elseif($numfight>0){
                print "<b>You are already fighting something</b><br><br>";
        }elseif($numequip<=0){
                print "<b>You should have some weapons equipped if you are going to fight</b><br><br>";
        }elseif($stat[hp]<=0){
                print "<b>You're already dead, you can't fight anything</b><br><br>";
        }else{
                $winnum=mysql_num_rows(mysql_query("select * from 1p_records where owner='$stat[id]' and opponent='$sp[name]' and outcome='win'"));
                $sp[max_hp]=$sp[max_hp]+($sp[max_hp_increase]*$winnum);
                $sp[attack]=$sp[attack]+($sp[attack_increase]*$winnum);
                $sp[defend]=$sp[defend]+($sp[defend_increase]*$winnum);
                $sp[max_hp]=round($sp[max_hp]);
                $sp[attack]=round($sp[attack]);
                $sp[defend]=round($sp[defend]);
                mysql_query("insert into 1p (name, owner, hp, max_hp, attack, defend, weapons, skills, bp) values ('$sp[name]', '$stat[id]', '$sp[max_hp]', '$sp[max_hp]', '$sp[attack]', '$sp[defend]', '$sp[weapons]', '$sp[skills]', '$sp[bp]')");
        }
}

$fight=mysql_fetch_array(mysql_query("select * from 1p where owner='$stat[id]' limit 1"));
if($fight[id]<=0){
        print "Pick a Fight:<br><br>";
        print "<table border=1 cellspacing=0 cellpadding=2><tr><td>Name</td><td>BP Award</td><td>Your Record</td><td></td>";
        if($user[position]==Admin){
                print "<td>admin only: weapon list</td>";
        }
        print "</tr>";
        $spsel=mysql_query("select * from 1p where owner='0' order by bp asc");
        while($sp=mysql_fetch_array($spsel)){
                $winnum=mysql_num_rows(mysql_query("select * from 1p_records where owner='$stat[id]' and opponent='$sp[name]' and outcome='win'"));
                $losenum=mysql_num_rows(mysql_query("select * from 1p_records where owner='$stat[id]' and opponent='$sp[name]' and outcome='lose'"));
                print "<tr><td>$sp[name]</td><td>";
                print "$sp[bp]</td><td>Win: <b>$winnum</b><br>Lose: $losenum</td><td><a href=$PHP_SELF?p=1p&challenge=$sp[id]>Challenge</a></td>";
                if($user[position]==Admin){
                        print "<td>";
                        $itemid=explode(",",$sp[weapons]);
                        $i=0;
                        while($itemid[$i]>0){
                                $item=mysql_fetch_Array(mysql_query("select * from items where id='$itemid[$i]' limit 1"));
                                print "$item[name], ";
                                $i=$i+1;
                        }
                        print "</td>";
                }
                print "</tr>";
        }
        print "</table>";
}else{
        print "<table border=0 cellspacing=0 cellpadding=2 width=\"100%\">";
        $msg="";

        if($go==1){
                $endfight=0;

                $check=$_POST['check'];
                //get the items that the player used, store the item ids as $used[0],$used[1],$used[2]
                $weapi=0;
                while($weapi<=2){
                        if($check[$weapi]&&$weapi<=2){
                                $eq=mysql_fetch_array(mysql_query("select * from items where owner='$user[id]' and equip='$stat[id]' and id='$check[$weapi]'"));
                                $used[$weapi]=$eq[id];
                                if($eq[used]==1){
                                        $used[$weapi]="";
                                }
                        }
                        $weapi=$weapi+1;
                }
                $skillid=$_POST['skillid'];
                $skill=mysql_fetch_array(mysql_query("select * from skills where id='$skillid' and owner='$stat[id]'"));
                if($skill[uses]!="multi"){
                        $usesleft=$skill[uses]-$skill[used];
                        if($usesleft<=0){
                                $skill[id]=0;
                        }
                }

                $didicons=array(0,0,0,0,0,0);
                $didicon_def=array(0,0,0,0,0,0);
                $skillicons=array(0,0,0,0,0,0);
                $i=0;
                while($i<=2){
                        $eq=mysql_fetch_array(mysql_query("select * from items where id='$used[$i]'"));
                        $icons=explode(",",$eq[icons]);
                        $icon_def=explode(",",$eq[icon_def]);
                        $e=0;
                        while($e<=5){
                                if(strpos($icons[$e],"-")>0){
                                        $iconrange=explode("-",$icons[$e]);
                                        $icons[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                $didicons[$e]=$didicons[$e]+$icons[$e];
                                if(strpos($icon_def[$e],"-")>0){
                                        $iconrange=explode("-",$icon_def[$e]);
                                        $icon_def[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                if($icon_def[$e]=="all"){
                                        $allblocked[$e]=1;
                                }else{
                                        $didicon_def[$e]=$didicon_def[$e]+$icon_def[$e];
                                }

                                $e=$e+1;
                        }
                        $i=$i+1;
                        $iconsimp=implode(",",$icons);
                        $icon_defimp=implode(",",$icon_def);
                        if($eq[id]>0){
                                $msg.= "<tr bgcolor=\"#DFFFDD\"><td><center>";
                                $geticons=geticons("0","0","$icon_defimp","");
                                $msg.="$geticons";
                                if(strpos($eq[heal_min],"%")>0){
                                        if($eq[heal_min]>=0){
                                                $eq[heal_min]=$stat[max_hp]*($eq[heal_min]/100);
                                        }else{
                                                $eq[heal_min]=$fight[max_hp]*($eq[heal_min]/100);
                                        }
                                }
                                if(strpos($eq[heal_max],"%")>0){
                                        if($eq[heal_max]>=0){
                                                $eq[heal_max]=$stat[max_hp]*($eq[heal_max]/100);
                                        }else{
                                                $eq[heal_max]=$fight[max_hp]*($eq[heal_max]/100);
                                        }
                                }
                                $heal=rand($eq[heal_min],$eq[heal_max]);
                                if($heal>0){
                                        $msg.="<br><img src=\"img/icon_heart.gif\">$heal HP";
                                        $stat[hp]=$stat[hp]+$heal;
                                }
                                $msg.= "</center></td><td><center>";
                                $newphrase=$eq[phrase2];
                                $newphrase=str_replace("item","<b>$eq[name]</b>",$newphrase);
                                $newphrase=str_replace("Opponent","$fight[name]",$newphrase);
                                if(!$newphrase){
                                        $newphrase="You attack with <b>$eq[name]</b>";
                                }
                                $msg.= "$newphrase";
                                if($heal>0){
                                        $msg.="<br>$eq[name] heals you";
                                }elseif($heal<0){
                                        $msg.="<br>$eq[name] drains $fight[name]";
                                }
                                $msg.= "</center></td><td><center>";
                                $geticons= geticons("0","$iconsimp","0","");
                                $msg.="$geticons";
                                if($heal<0){
                                        $msg.="<br><img src=\"img/icon_greenheart.gif\">$heal HP";
                                        $fight[hp]=$fight[hp]+$heal;
                                }
                                $msg.= "</center></td></tr>";

                                if($eq[uses]=="once"){
                                        mysql_query("update items set used='1' where id='$eq[id]'");
                                }elseif($eq[uses]=="once_ever"){
                                        mysql_query("delete from items where id='$eq[id]'");
                                }
                        }
                }
                if($skill[id]>0){
                        $icons=explode(",",$skill[icons]);
                        $icon_def=explode(",",$skill[icon_def]);
                        $e=0;
                        while($e<=5){
                                if(strpos($icons[$e],"-")>0){
                                        $iconrange=explode("-",$icons[$e]);
                                        $icons[$e]=rand($iconrange[0],$iconrange[1]);
                                }elseif(strpos($icons[$e],"%")>0){
                                        $icons[$e]=$didicons[$e]*($icons[$e]/100);
                                        $icons[$e]=ceil($icons[$e]);
                                }
                                $didicons[$e]=$didicons[$e]+$icons[$e];
                                $skillicons[$e]=$skillicons[$e]+$icons[$e];
                                if(strpos($icon_def[$e],"-")>0){
                                        $iconrange=explode("-",$icon_def[$e]);
                                        $icon_def[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                if($icon_def[$e]=="all"){
                                        $allblocked[$e]=1;
                                }else{
                                        $didicon_def[$e]=$didicon_def[$e]+$icon_def[$e];
                                }

                                $e=$e+1;
                        }
                        $iconsimp=implode(",",$icons);
                        $icon_defimp=implode(",",$icon_def);
                        $msg.= "<tr bgcolor=\"#C5FFC1\"><td><center>";
                        $geticons=geticons("0","0","$icon_defimp","");
                        $msg.="$geticons";
                        if(strpos($skill[heal_min],"%")>0){
                                if($skill[heal_min]>=0){
                                        $skill[heal_min]=$stat[max_hp]*($skill[heal_min]/100);
                                }else{
                                        $skill[heal_min]=$fight[max_hp]*($skill[heal_min]/100);
                                }
                        }
                        if(strpos($skill[heal_max],"%")>0){
                                if($skill[heal_max]>=0){
                                        $skill[heal_max]=$stat[max_hp]*($skill[heal_max]/100);
                                }else{
                                        $skill[heal_max]=$fight[max_hp]*($skill[heal_max]/100);
                                }
                        }
                        $heal=rand($skill[heal_min],$skill[heal_max]);
                        if($heal>0){
                                $msg.="<br><img src=\"img/icon_heart.gif\">$heal HP";
                                $stat[hp]=$stat[hp]+$heal;
                        }
                        $msg.= "</center></td><td><center>";
                        $newphrase="You use <b>$skill[name]</b>";
                        if($skill[name]=="Run"){
                                $success=rand(1,2);
                                if($success==1){
                                        $newphrase.=" ... and it worked!";
                                        $endfight=1;
                                }else{
                                        $newphrase.=" ... but it didn't work";
                                }
                        }
                        $msg.= "$newphrase";
                        if($heal>0){
                                $msg.="<br>$skill[name] heals you";
                        }elseif($heal<0){
                                $msg.="<br>$skill[name] drains $fight[name]";
                        }
                        $msg.= "</center></td><td><center>";
                        $geticons= geticons("0","$iconsimp","0","");
                        $msg.="$geticons";
                        if($heal<0){
                                $msg.="<br><img src=\"img/icon_greenheart.gif\">$heal HP";
                                $fight[hp]=$fight[hp]+$heal;
                        }
                        $msg.= "</center></td></tr>";
                        if($skill[uses]!="multi"){
                                mysql_query("update skills set used=used+1 where id='$skill[id]'");
                        }
                }
                $j=1;
                $didicons2=$didicons[0];
                $didicon_def2=$didicon_def[0];
                while($j<=5){
                        $didicons2.=",$didicons[$j]";
                        $didicon_def2.=",$didicon_def[$j]";
                        $j=$j+1;
                }
                //print geticons("0","$didicons2","$didicon_def2","");

                $fweapons=explode(",",$fight[weapons]);
                $weapi=0;
                while($weapi<=2){
                        $chosen=array_rand($fweapons);
                        $fused[$weapi]=$fweapons[$chosen];//picks one random value from the array
                        $testfused=mysql_fetch_array(mysql_query("select * from items where id='$fused[$weapi]'"));
                        if($testfused[uses]=="once_ever" || $testfused[uses]=="once"){
                                unset($fweapons[$chosen]);
                                array_values($fweapons);
                                $newfweapons=implode(",",$fweapons);
                                mysql_query("update 1p set weapons='$newfweapons' where id='$fight[id]'");
                                $fight[weapons]=$newfweapons;
                        }
                        $weapi=$weapi+1;
                }
                $fskills=explode(",",$fight[skills]);
                $skchosen=array_rand($fskills);
                $fskillid=$fskills[$skchosen];//picks one random value from the array
                $testfskillid=mysql_fetch_array(mysql_query("select * from skills where id='$fskillid'"));
                if($testfskillid[uses]!="multi"&&$testfskillid[uses]<=1){
                        unset($fskills[$skchosen]);
                        array_values($fskills);
                        $newfskills=implode(",",$fskills);
                        mysql_query("update 1p set skills='$newfskills' where id='$fight[id]'");
                        $fight[skills]=$newfskills;
                }
                $fskill=mysql_fetch_array(mysql_query("select * from skills where id='$fskillid'"));

                $fdidicons=array(0,0,0,0,0,0);
                $fdidicon_def=array(0,0,0,0,0,0);
                $i=0;
                while($i<=2){
                        $eq=mysql_fetch_array(mysql_query("select * from items where id='$fused[$i]'"));
                        $icons=explode(",",$eq[icons]);
                        $icon_def=explode(",",$eq[icon_def]);
                        $e=0;
                        while($e<=5){
                                if(strpos($icons[$e],"-")>0){
                                        $iconrange=explode("-",$icons[$e]);
                                        $icons[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                $fdidicons[$e]=$fdidicons[$e]+$icons[$e];
                                if(strpos($icon_def[$e],"-")>0){
                                        $iconrange=explode("-",$icon_def[$e]);
                                        $icon_def[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                if($icon_def[$e]!="all"){
                                        $fdidicon_def[$e]=$fdidicon_def[$e]+$icon_def[$e];
                                }else{
                                        $fallblocked[$e]=1;
                                }

                                $e=$e+1;
                        }
                        $iconsimp=implode(",",$icons);
                        $icon_defimp=implode(",",$icon_def);
                        $i=$i+1;

                        if($eq[id]>0){
                                $msg.= "<tr bgcolor=\"#FFE6E6\"><td><center>";
                                $geticons= geticons("0","$iconsimp","0","");
                                $msg.="$geticons";
                                if(strpos($eq[heal_min],"%")>0){
                                        if($eq[heal_min]>=0){
                                                $eq[heal_min]=$fight[max_hp]*($eq[heal_min]/100);
                                        }else{
                                                $eq[heal_min]=$stat[max_hp]*($eq[heal_min]/100);
                                        }
                                }
                                if(strpos($eq[heal_max],"%")>0){
                                        if($eq[heal_max]>=0){
                                                $eq[heal_max]=$fight[max_hp]*($eq[heal_max]/100);
                                        }else{
                                                $eq[heal_max]=$stat[max_hp]*($eq[heal_max]/100);
                                        }
                                }
                                $heal=rand($eq[heal_min],$eq[heal_max]);
                                if($heal<0){
                                        $msg.="<br><img src=\"img/icon_greenheart.gif\">$heal HP";
                                        $stat[hp]=$stat[hp]+$heal;
                                }
                                $msg.= "</center></td><td><center>";
                                $newphrase=$eq[phrase];
                                $newphrase=str_replace("Opponent","$fight[name]",$newphrase);
                                $newphrase=str_replace("item","<b>$eq[name]</b>",$newphrase);
                                $msg.= "$newphrase";
                                if($heal>0){
                                        $msg.="<br>$eq[name] heals $fight[name]";
                                }elseif($heal<0){
                                        $msg.="<br>$eq[name] drains you";
                                }
                                $msg.= "</center></td><td><center>";
                                $geticons= geticons("0","0","$icon_defimp","");
                                $msg.="$geticons";
                                if($heal>0){
                                        $msg.="<br><img src=\"img/icon_heart.gif\">$heal HP";
                                        $fight[hp]=$fight[hp]+$heal;
                                }
                                $msg.= "</center></td></tr>";
                        }
                }
                if($fskill[id]>0){
                        $icons=explode(",",$fskill[icons]);
                        $icon_def=explode(",",$fskill[icon_def]);
                        $e=0;
                        while($e<=5){
                                if(strpos($icons[$e],"-")>0){
                                        $iconrange=explode("-",$icons[$e]);
                                        $icons[$e]=rand($iconrange[0],$iconrange[1]);
                                }elseif(strpos($icons[$e],"%")>0){
                                        $icons[$e]=$fdidicons[$e]*($icons[$e]/100);
                                        $icons[$e]=ceil($icons[$e]);
                                }
                                $fdidicons[$e]=$fdidicons[$e]+$icons[$e];
                                if(strpos($icon_def[$e],"-")>0){
                                        $iconrange=explode("-",$icon_def[$e]);
                                        $icon_def[$e]=rand($iconrange[0],$iconrange[1]);
                                }
                                if($icon_def[$e]!="all"){
                                        $fdidicon_def[$e]=$fdidicon_def[$e]+$icon_def[$e];
                                }else{
                                        $fallblocked[$e]=1;
                                }
                                $e=$e+1;
                        }
                        $iconsimp=implode(",",$icons);
                        $icon_defimp=implode(",",$icon_def);

                        $msg.= "<tr bgcolor=\"#FFCECE\"><td><center>";
                        $geticons= geticons("0","$iconsimp","0","");
                        $msg.="$geticons";
                        if(strpos($fskill[heal_min],"%")>0){
                                if($fskill[heal_min]>=0){
                                        $fskill[heal_min]=$fight[max_hp]*($fskill[heal_min]/100);
                                }else{
                                        $fskill[heal_min]=$stat[max_hp]*($fskill[heal_min]/100);
                                }
                        }
                        if(strpos($fskill[heal_max],"%")>0){
                                if($fskill[heal_max]>=0){
                                        $fskill[heal_max]=$fight[max_hp]*($fskill[heal_max]/100);
                                }else{
                                        $fskill[heal_max]=$stat[max_hp]*($fskill[heal_max]/100);
                                }
                        }
                        $heal=rand($fskill[heal_min],$fskill[heal_max]);
                        if($heal<0){
                                $msg.="<br><img src=\"img/icon_greenheart.gif\">$heal HP";
                                $stat[hp]=$stat[hp]+$heal;
                        }
                        $msg.= "</center></td><td><center>";
                        $newphrase="$fight[name] uses <b>$fskill[name]</b>";
                        $msg.= "$newphrase";
                        if($heal>0){
                                $msg.="<br>$fskill[name] heals $fight[name]";
                        }elseif($heal<0){
                                $msg.="<br>$fskill[name] drains you";
                        }
                        $msg.= "</center></td><td><center>";
                        $geticons= geticons("0","0","$icon_defimp","");
                        $msg.="$geticons";
                        if($heal>0){
                                $msg.="<br><img src=\"img/icon_heart.gif\">$heal HP";
                                $fight[hp]=$fight[hp]+$heal;
                        }
                        $msg.= "</center></td></tr>";
                }
                $j=1;
                $fdidicons2=$fdidicons[0];
                $fdidicon_def2=$fdidicon_def[0];
                while($j<=5){
                        $fdidicons2.=",$fdidicons[$j]";
                        $fdidicon_def2.=",$fdidicon_def[$j]";
                        $j=$j+1;
                }
                //print geticons(0,$fdidicons2,$fdidicon_def2,"");

                $round=3;


                $ones=(35/1000);
                $tens=(4/10);
                $fifties=(21/10);
                $hundreds=(45/10);
                $attack=0;
                $statattack=$stat[attack];
                while($statattack>=100){
                        $attack=$attack+$hundreds;
                        $statattack=$statattack-100;
                }
                while($statattack>=50){
                        $attack=$attack+$fifties;
                        $statattack=$statattack-50;
                }
                while($statattack>=10){
                        $attack=$attack+$tens;
                        $statattack=$statattack-10;
                }
                while($statattack>=1){
                        $attack=$attack+$ones;
                        $statattack=$statattack-1;
                }
                $attack=round($attack,$round);
                if($attack<.5){
                        $attack=.5;
                }

                $fattack=0;
                $fightattack=$fight[attack];
                while($fightattack>=100){
                        $fattack=$fattack+$hundreds;
                        $fightattack=$fightattack-100;
                }
                while($fightattack>=50){
                        $fattack=$fattack+$fifties;
                        $fightattack=$fightattack-50;
                }
                while($fightattack>=10){
                        $fattack=$fattack+$tens;
                        $fightattack=$fightattack-10;
                }
                while($fightattack>=1){
                        $fattack=$fattack+$ones;
                        $fightattack=$fightattack-1;
                }
                $fattack=round($fattack,$round);
                if($fattack<.5){
                        $fattack=.5;
                }

                $defend=0;
                $statdefend=$stat[defend];
                while($statdefend>=100){
                        $defend=$defend+$hundreds;
                        $statdefend=$statdefend-100;
                }
                while($statdefend>=50){
                        $defend=$defend+$fifties;
                        $statdefend=$statdefend-50;
                }
                while($statdefend>=10){
                        $defend=$defend+$tens;
                        $statdefend=$statdefend-10;
                }
                while($statdefend>=1){
                        $defend=$defend+$ones;
                        $statdefend=$statdefend-1;
                }
                $defend=round($defend,$round);
                if($defend<.5){
                        $defend=.5;
                }

                $fdefend=0;
                $fightdefend=$fight[defend];
                while($fightdefend>=100){
                        $fdefend=$fdefend+$hundreds;
                        $fightdefend=$fightdefend-100;
                }
                while($fightdefend>=50){
                        $fdefend=$fdefend+$fifties;
                        $fightdefend=$fightdefend-50;
                }
                while($fightdefend>=10){
                        $fdefend=$fdefend+$tens;
                        $fightdefend=$fightdefend-10;
                }
                while($fightdefend>=1){
                        $fdefend=$fdefend+$ones;
                        $fightdefend=$fightdefend-1;
                }
                $fdefend=round($fdefend,$round);
                if($fdefend<.5){
                        $fdefend=.5;
                }

//                ok now we have $attack, $fattack, $defend, and $fdefend
//                $attack is how much damage per icon the player does
//                $fattack is how much damage per icon the opponent does
//                $defend is how much damage per def_icon the player takes away from $fattack
//                $fdefend is how much damage per def_icon the opponent takes away from $attack


                $damage=0;
                $fdamage=0;
                $subdamage=0;
                $fsubdamage=0;

                $icons=explode(",",$didicons2);
                $ficons=explode(",",$fdidicons2);
                $icon_def=explode(",",$didicon_def2);
                $ficon_def=explode(",",$fdidicon_def2);
                $i=0;
                while($i<=5){
                        if($ficon_def[$i]>=$icons[$i] || $fallblocked[$i]==1){
                                $icons[$i]=0;//more def than attack, icon attack is 0
                                $ficon_def[$i]=0;//cannot defend more than attacked
                        }
                        if($icon_def[$i]>=$ficons[$i] || $allblocked[$i]==1){
                                $ficons[$i]=0;
                                $icon_def[$i]=0;
                        }
                        $multiplier=1;

                        $enpericon=$stat[level]*0.005;
                        $mnpericon=$stat[level]*0.005;
                        if($i==0 || $i==1 || $i==2){ //0=stab, 1=slash, 2=arrow, 3=fire, 4=water, 5=lightning
                                $energyused[$i]=$enpericon*($icons[$i]-$skillicons[$i]);
                                mysql_query("update characters set energy=energy-$energyused[$i] where id='$stat[id]'");
                                $stat[energy]=$stat[energy]-$energyused[$i];
                                if($stat[energy]<=0){
                                        $multiplier=0.6;
                                        $note_noenergy=1;
                                }
                        }elseif($i==3 || $i==4 || $i==5){
                                $manaused[$i]=$mnpericon*($icons[$i]-$skillicons[$i]);
                                mysql_query("update characters set mana=mana-$manaused[$i] where id='$stat[id]'");
                                $stat[mana]=$stat[mana]-$manaused[$i];
                                if($stat[mana]<=0){
                                        $multiplier=0.5;
                                        $note_nomana=1;
                                }
                        }
                        $damagea[$i]=($icons[$i]*$attack*$multiplier)-($ficon_def[$i]*$fdefend);
                        if($damagea[$i]<0){
                                $damagea[$i]=0;
                        }
                        $damage=$damage+$damagea[$i];
                        $fdamagea[$i]=($ficons[$i]*$fattack)-($icon_def[$i]*$defend);
                        if($fdamagea[$i]<0){
                                $fdamagea[$i]=0;
                        }
                        $fdamage=$fdamage+$fdamagea[$i];
//                        $subdamage=$subdamage+($icon_def[$i]*$defend);
//                        $fsubdamage=$fsubdamage+($ficon_def[$i]*$fdefend);

                        $i=$i+1;
                }
//                $damage=$damage-$fsubdamage;
                if($damage<0){$damage=0;}
//                $fdamage=$fdamage-$subdamage;
                if($fdamage<0){$fdamage=0;}

                $damage=rand(($damage*0.8),($damage*1.2));
                $fdamage=rand(($fdamage*0.8),($fdamage*1.2));
                $damage=ceil($damage);
                $fdamage=ceil($fdamage);

                if($note_noenergy){
                        $msg.= "<tr><td></td><td><center><b>You are out of Energy!</b> <br><img src=\"img/icon_stab.gif\">, <img src=\"img/icon_slash.gif\">, and <img src=\"img/icon_arrow.gif\"> are weakened</td><td></td></tr>";
                }
                if($note_nomana){
                        $msg.= "<tr><td></td><td><center><b>You are out of Mana!</b> <br><img src=\"img/icon_fire.gif\">, <img src=\"img/icon_water.gif\">, and <img src=\"img/icon_lightning.gif\"> are weakened</td><td></td></tr>";
                }
                $msg.= "<tr><td><center>Damage: <b>$fdamage</b></center></td><td> </td><td><center>Damage: <b>$damage</b></center></td></tr>";

                $fight[hp]=$fight[hp]-$damage;
                $stat[hp]=$stat[hp]-$fdamage;
        }
        $stat[attack]=ceil($stat[attack]);
        $stat[defend]=ceil($stat[defend]);
        $stat[hp]=ceil($stat[hp]);
        $fight[hp]=ceil($fight[hp]);
        $stat[max_hp]=ceil($stat[max_hp]);
        $fight[max_hp]=ceil($fight[max_hp]);
        if($stat[hp]>$stat[max_hp]){
                $stat[hp]=$stat[max_hp];
        }
        if($fight[hp]>$fight[max_hp]){
                $fight[hp]=$fight[max_hp];
        }

        mysql_query("update 1p set hp='$fight[hp]' where id='$fight[id]'");
        mysql_query("update characters set hp='$stat[hp]' where id='$stat[id]'");
        $fpct=100*($fight[hp]/$fight[max_hp]);
        $fopct=100-$fpct;
        if($fpct>100){$fpct=100;}
        if($fopct>100){$fopct=100;}
        print "<tr><td width=\"33%\"><center><b>$fight[name]</b>";
        print "<br><table width=100 height=5 border=1 cellspacing=0 cellpadding=0><tr><td width=$fpct bgcolor=\"#D31212\"><img src=\"img/small_blank.gif\" width=\"$fpct\" height=5></td><td width=\"$fopct\" bgcolor=\"#FFFFFF\"><img src=\"img/small_blank.gif\" width=$fopct height=5></td></tr></table>";
        print "<br>$fight[hp]/$fight[max_hp]<br>Atk:$fight[attack] -- Def:$fight[defend]</center></td><td width=\"34%\"> </td><td width=\"33%\"><center><b>$stat[name]</b>";
        $pct=100*($stat[hp]/$stat[max_hp]);
        $opct=100-$pct;
        if($pct>100){$pct=100;}
        if($opct>100){$opct=100;}
        print "<br><table width=100 height=5 border=1 cellspacing=0 cellpadding=0><tr><td width=\"$pct\" bgcolor=\"#D31212\"><img src=\"img/small_blank.gif\" width=\"$pct\" height=5></td><td width=\"$opct\" bgcolor=\"#FFFFFF\"><img src=\"img/small_blank.gif\" width=\"$opct\" height=5></td></tr></table>";
        print "<br>$stat[hp]/$stat[max_hp]<br>Atk:$stat[attack] -- Def:$stat[defend]</center></td></tr>";
        print "$msg";
        print "</table>";

        if($fight[hp]<=0&&$stat[hp]<=0 || $endfight==1){
                print "<br><br><center><font size=6><b>It was a draw, there is no winner</b></font>";
                print "<br><a href=$PHP_SELF?p=1p>Click here to continue</a></center>";
                mysql_query("delete from 1p where id='$fight[id]' limit 1");
                mysql_query("update items set used='0' where used='1' and equip='$stat[id]'");
                mysql_query("update skills set used='0' where owner='$stat[id]'");
        }elseif($fight[hp]<=0){
                print "<br><br><center><font size=6><b>You have defeated $fight[name]!</b></font>";
                print "<br>You got <b>$fight[bp]</b> Battle Points";
                print "<br><a href=$PHP_SELF?p=1p>Click here to continue</a></center><br><br>";
                mysql_query("insert into 1p_records (`owner`,`opponent`, `outcome`) values('$stat[id]', '$fight[name]', 'win')");
                mysql_query("update characters set battle=battle+$fight[bp] where id='$stat[id]'");
                mysql_query("delete from 1p where id='$fight[id]' limit 1");
                mysql_query("update items set used='0' where used='1' and equip='$stat[id]'");
                mysql_query("update skills set used='0' where owner='$stat[id]'");
        }elseif($stat[hp]<=0){
                print "<br><br><center><font size=6><b>You have been defeated by $fight[name]!</b></font>";
                print "<br><a href=$PHP_SELF?p=1p>Click here to continue</a></center>";
                mysql_query("insert into 1p_records (`owner`,`opponent`, `outcome`) values('$stat[id]', '$fight[name]', 'lose')");
                mysql_query("delete from 1p where id='$fight[id]' limit 1");
                mysql_query("update items set used='0' where used='1' and equip='$stat[id]'");
                mysql_query("update skills set used='0' where owner='$stat[id]'");
        }else{

                print "<br><hr><br><b>Choose Your Weapons: <font size=1>(3 max)</font></b><br>";
                print "<table border=0 cellspacing=10 cellpadding=0>";
                $tdwidth=120;
                $tdheight=100;
                $tdwidth2=$tdwidth+5;
                $tdheight2=$tdheight+5;
                print "<tr>";
                print "<form method=post action=$PHP_SELF?p=1p>";
                print "<input type=\"hidden\" name=p value=1p>";
                $jump=5;
                $jump2=$jump;
                $weapnum=1;
                $weapsel=mysql_query("select * from items where owner='$user[id]' and equip='$stat[id]'");
                while($weap=mysql_fetch_array($weapsel)){
                        print "<td height=$tdheight2 width=$tdheight2 valign=top>";
                        print "<table border=1 cellspacing=0 cellpadding=0 width=$tdwidth><tr><td width=$tdwidth height=$tdheight valign=top>";
                        print "<center>";
                        print "<label>";
                        print "$weap[name]<br>";
                        if($weap[used]==1){
                                print "<i>Used</i>";
                        }else{
                                print "<INPUT TYPE=\"checkbox\" NAME=check[] value=$weap[id]";
                                if($check[$weap[id]]){print " CHECKED";}
                                print ">";
                        }
                        print "<br>";
                        print geticons($weap[id],0,0,"");
                        print "</label>";
                        print "</center>";
                        print "</td></tr></table>";
                        print "</td>";
                        if($weapnum==$jump){
                                print "</tr><tr>";
                                $jump=$jump+$jump2;
                        }
                        $weapnum=$weapnum+1;
                }
                //print_r($check);
                print "</tr></table><br>";
                print "<select name=\"skillid\">";
                $sksel=mysql_query("select * from skills where owner='$stat[id]' order by levelreq asc");
                while($sk=mysql_fetch_array($sksel)){
                        if($sk[uses]!="multi"){
                                $usesleft=$sk[uses]-$sk[used];
                        }
                        if($usesleft>0||$sk[uses]=="multi"){
                                print "<option value='$sk[id]'";
                                if($skillid==$sk[id]){
                                        print " SELECTED";
                                }
                                print ">$sk[name]";
                                if($sk[uses]!="multi"){
                                        print " ($usesleft uses)";
                                }
                                print "</option>";
                        }
                }
                print "</select> ";
                print "<INPUT TYPE=submit value=\"Go\"><INPUT TYPE=\"hidden\" name=\"go\" value=\"1\">";
                print "</form>";
        }
}