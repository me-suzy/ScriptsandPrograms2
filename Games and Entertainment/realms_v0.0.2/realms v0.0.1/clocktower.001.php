<?php
$checktime = mysql_fetch_array(mysql_query("SELECT hour FROM gametime"));
if($showclock==yes){
if($checktime[hour]<='6'||$checktime[hour]>='18'){
print"<br>it is nighttime<br><br>";
}else{
print"<br>it is daytime<br><br>";
}
}




$rtime = time();
$testtime = mysql_fetch_array(mysql_query("select * from time"));
$NPCid=-1;


//if($showclock==yes){ print"Countdowns:<br>"; }


$reset=24*60*60;
$revive=60*60;
$clock=16*60;
$restock=rand((60*5),(60*15)); //between 5 and 15 minutes

$nreset=$testtime[reset]+$reset;
$nrevive=$testtime[revive]+$revive;
$nclock=$testtime[clock]+$clock;

$nrvh=0;
if($testtime[reset]>$rtime){
        $vtthm=$testtime[reset]-$rtime;
        $nrvm=$vtthm/60;
        $nrvh=0;
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');
        while($nrvm>=60){
        $nrvh=$nrvh+1;
        $nrvm=$nrvm-60;
        }
        if($nrvm==60){
        $nrvh=$nrvh+1;
        $nrvm=$nrvm-60;
        }
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');
        if($nrvm<10){
        $nrvm="0$nrvm";
        }
        if($nrvh<10){
        $nrvh="0$nrvh";
        }
        if($showclock==yes){ print"Reset:<b>$nrvh:$nrvm</b>"; }
}else{

        mysql_query("update time set reset=$nreset");

        mysql_query("update characters set mana=max_mana");
        mysql_query("update characters set energy=max_energy");
        mysql_query("update characters set hp=max_hp");



        $totcash=0;
        $totplayers=0;
        $timenum=time();

        $psel=mysql_query("select * from users where position!='Admin'"); //I don't care, Admins arent counted in rank
        while($p=mysql_fetch_array($psel)){
                $csel=mysql_query("select * from characters where owner='$p[id]'");
                while($c=mysql_fetch_array($csel)){
                        $totcash=$totcash+$c[cash];
                }
                $totplayers=$totplayers+1;
                $totcash=$totcash+$p[bank];
        }
        $avcash=$totcash/$totplayers;
        $avcash=round($avcash,2);

        mysql_query("INSERT INTO `economy` (`timenum` , `totcash` , `totplayers`)VALUES ('$timenum','$totcash','$totplayers')");

        mysql_query("update events set timesperday=timesperdaymax");

        print"Reset: <b>NOW</b>";
}

if($showclock==yes){ print"<br>"; }




if($testtime[clock]>$rtime){
        $vtthm=$testtime[clock]-$rtime;
        $nrvm=$vtthm/60;
        $nrvh=0;
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');
        while($nrvm>=60){
        $nrvh=$nrvh+1;
        $nrvm=$nrvm-60;
        }
        if($nrvm==60){
        $nrvh=$nrvh+1;
        $nrvm=$nrvm-60;
        }
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');
        if($nrvm<10){
        $nrvm="0$nrvm";
        }
        if($nrvh<10){
        $nrvh="0$nrvh";
        }
        if($showclock==yes&&$staff==yes){ print"game hour:<b>$nrvh:$nrvm</b>"; }
}else{

mysql_query("update time set clock=$nclock");
{
        mysql_query("update gametime set hour=hour+1 where hour<23");
        mysql_query("update gametime set hour=0 where hour>22");

        if($showclock==yes&&$staff==yes){ print"game hour: <b>NOW</b>"; }
}
}
if($showclock==yes&&$staff==yes){ print"<br>"; }







if($forcerestock==1&&$user[position]==Admin){
        $testtime[restock]=$rtime;
}
if($testtime[restock]>$rtime){
        $vtthm=$testtime[restock]-$rtime;
        $nrvm=$vtthm/60;

        $nrvh=0;
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');
        while($nrvm>60){
        $nrvm=$nrvm-60;
        $nrvh=$nrvh+1;
        }
        if($nrvm==60){
        $nrvh=$nrvh+1;
        $nrvm=$nrvm=0;
        }
        $nrvm=round($nrvm,'0');
        $nrvh=round($nrvh,'0');

        if($nrvm<10){
        $nrvm="0$nrvm";
        }
        if($nrvh<10){
        $nrvh="0$nrvh";
        }
        if($showclock=="yes"&&$user[position]=="Admin"){//important: players are not supposed to see restock time 
                print"Restock:<b>$nrvh:$nrvm</b>";
        }
}else{

        $nrestock=$rtime+$restock;
        if($showclock==yes){
                print"<br>Restock:<b>NOW</b>";
        }
        mysql_query("update time set restock=$nrestock");

        $itemsel=mysql_query("select * from items where owner='0'");
        while($item=mysql_fetch_array($itemsel)){
                $maxsnum=150;
                $snum=rand(1,$maxsnum);
                if($snum<=(100-$item[rarity])){
                        $stocked=0;
                        if($item[rarity]>=90){
                                $stocked=rand(0,1);
                        }elseif($item[rarity]>=80){
                                $stocked=rand(0,2);
                        }elseif($item[rarity]>=70){
                                $stocked=rand(0,3);
                        }elseif($item[rarity]>=60){
                                $stocked=rand(0,4);
                        }elseif($item[rarity]>=50){
                                $stocked=rand(0,5);
                        }elseif($item[rarity]>=40){
                                $stocked=rand(0,6);
                        }elseif($item[rarity]>=30){
                                $stocked=rand(0,7);
                        }elseif($item[rarity]>=20){
                                $stocked=rand(0,8);
                        }elseif($item[rarity]>=10){
                                $stocked=rand(0,9);
                        }else{
                                $stocked=rand(0,20);
                        }
                        $instock=mysql_num_rows(mysql_query("select * from items where owner='$NPCid' and name='$item[name]'"));
                        if($instock<$stocked){
                                $Q1=$item[price]-($item[price]*0.5);
                                $Q3=$item[price]+($item[price]*0.5);
                                $newprice=rand($Q1,$Q3);
                                while($stocked>0){
                                        mysql_query("INSERT INTO `items` (`name` , `type` , `owner` , `image` , `price` , `icons` , `icon_def` , `heal_min`, `heal_max` , `rarity` , `phrase`, `phrase2`, `effect`, `effect_power`, `uses`) VALUES ('$item[name]', '$item[type]', '$NPCid', '$item[image]', '$newprice', '$item[icons]', '$item[icon_def]', '$item[heal_min]', '$item[heal_max]', '$item[rarity]', '$item[phrase]', '$item[phrase2]', '$item[effect]', '$item[effect_power]', '$item[uses]')");
                                        $stocked=$stocked-1;
                                }
                        }
                }elseif($snum>=50){
                        mysql_query("delete from items where owner='$NPCid' and name='$item[name]'"); // chance to clear stock
                }
        }
}


$showclock=yes;

?>