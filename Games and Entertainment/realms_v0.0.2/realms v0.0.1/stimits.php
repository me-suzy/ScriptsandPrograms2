<?php

$stimlevel = $stat[level]+2;

$mhpstimit=getstim(max_hp,$stimlevel,$stat[race]);

$nrgstimit=getstim(max_energy,$stimlevel,$stat[race]);

$mnastimit=getstim(max_mana,$stimlevel,$stat[race]);

$offstim=getstim(offense,$stimlevel,$stat[race]);

$defstim=getstim(defense,$stimlevel,$stat[race]);

$agestimit=getstim(agility,$stimlevel,$stat[race]);

$slssstimit=getstim(smart,$stimlevel,$stat[race]);

$lssstimit=getstim(luck,$stimlevel,$stat[race]);


$lvlmhpstimit=getstim(max_hp,$stat[level],$stat[race]);

$lvlnrgstimit=getstim(max_energy,$stat[level],$stat[race]);

$lvlmnastimit=getstim(max_mana,$stat[level],$stat[race]);

$lvloffstim=getstim(offense,$stat[level],$stat[race]);

$lvldefstim=getstim(defense,$stat[level],$stat[race]);

$lvlagestimit=getstim(agility,$stat[level],$stat[race]);

$lvlslssstimit=getstim(smart,$stat[level],$stat[race]);

$lvllssstimit=getstim(luck,$stat[level],$stat[race]);


if($mhpstimit<20){
$mhpstimit=20;
}

if($nrgstimit<10){
$nrgstimit=10;
}

if($lvlmhpstimit<20){
$lvlmhpstimit=20;
}

if($lvlnrgstimit<10){
$lvlnrgstimit=10;
}

if($lssstimit>50){
$lssstimit=50;
}

if($slssstimit>50){
$slssstimit=50;
}

if($lvllssstimit>50){
$lvllssstimit=50;
}

if($lvlslssstimit>50){
$lvlslssstimit=50;
}

if($lssstimit<0){
$lssstimit=0;
}

if($slssstimit<0){
$slssstimit=0;
}

if($stat[hp]>0){
$increasehp = 0.01 * $stat[level];
$increaseenergy = 0.005 * $stat[level];
$increasemana = 0.002 * $stat[level];
mysql_query("update characters set hp=hp+$increasehp where id=$stat[id]");
mysql_query("update characters set energy=energy+$increaseenergy where id=$stat[id]");
mysql_query("update characters set mana=mana+$increasemana where id=$stat[id]");
}

if($stat[max_hp]>$mhpstimit){
mysql_query("update characters set max_hp='$mhpstimit' where id=$stat[id]");
}

if($stat[max_energy]>$nrgstimit){
mysql_query("update characters set max_energy='$nrgstimit' where id=$stat[id]");
}

if($stat[max_mana]>$mnastimit){
mysql_query("update characters set max_mana='$mnastimit' where id=$stat[id]");
}

if($stat[luck]>$lssstimit){
mysql_query("update characters set luck='$lssstimit' where id=$stat[id]");
}

if($stat[brains]>$slssstimit){
mysql_query("update characters set brains='$slssstimit' where id=$stat[id]");
}

if($stat[attack]>$offstim){
mysql_query("update characters set attack='$offstim' where id=$stat[id]");
}

if($stat[defend]>$defstim){
mysql_query("update characters set defend='$defstim' where id=$stat[id]");
}

if($stat[speed]>$agestimit){
mysql_query("update characters set speed='$agestimit' where id=$stat[id]");
}

if($stat[hp]>$stat[max_hp]){
mysql_query("update characters set hp='$stat[max_hp]' where id=$stat[id]");
}

if($stat[energy]>$stat[max_energy]){
mysql_query("update characters set energy='$stat[max_energy]' where id=$stat[id]");
}

if($stat[mana]>$stat[max_mana]){
mysql_query("update characters set mana='$stat[max_mana]' where id=$stat[id]");
}







if($stat[max_hp]<0){
mysql_query("update characters set max_hp=1 where id=$stat[id]");
}

if($stat[max_energy]<0){
mysql_query("update characters set max_energy=1 where id=$stat[id]");
}

if($stat[max_mana]<0){
mysql_query("update characters set max_mana=1 where id=$stat[id]");
}

if($stat[hp]<0){
mysql_query("update characters set hp=0 where id=$stat[id]");
}

if($stat[energy]<0){
mysql_query("update characters set energy=0 where id=$stat[id]");
}

if($stat[mana]<0){
mysql_query("update characters set mana=0 where id=$stat[id]");
}

if($stat[luck]<0){
mysql_query("update characters set luck='0' where id=$stat[id]");
}

if($stat[brains]<0){
mysql_query("update characters set brains='0' where id=$stat[id]");
}

if($stat[attack]<0){
mysql_query("update characters set attack=1 where id=$stat[id]");
}

if($stat[defend]<0){
mysql_query("update characters set defend=1 where id=$stat[id]");
}

if($stat[speed]<1){
mysql_query("update characters set speed=1 where id=$stat[id]");
}

$stat = mysql_fetch_array(mysql_query("select * from characters where id='$stat[id]'"));


