<?php

$max_hp_check = maxstatbar(hp,$stat[id]);

$max_energy_check = maxstatbar(energy,$stat[id]);

$max_mana_check = maxstatbar(mana,$stat[id]);

print"$max_hp_check
$max_energy_check
$max_mana_check
";

$olevel = $stat[level]-1;

$omhpstimit=getstim(max_hp,$olevel,$stat[race]);

$onrgstimit=getstim(max_energy,$olevel,$stat[race]);

$omnastimit=getstim(max_mana,$olevel,$stat[race]);

$ooffstim=getstim(offense,$olevel,$stat[race]);

$odefstim=getstim(defense,$olevel,$stat[race]);

$oagestimit=getstim(agility,$olevel,$stat[race]);

$oslssstimit=getstim(smart,$olevel,$stat[race]);

$olssstimit=getstim(luck,$olevel,$stat[race]);


$fexp = $ooffstim+$odefstim+$oagestimit+$oslssstimit+$olssstimit+$omhpstimit+$onrgstimit+$omnastimit;
$gexp = $lvloffstim+$lvldefstim+$lvlagestimit+$lvlslssstimit+$lvllssstimit+$lvlmhpstimit+$lvlnrgstimit+$lvlmnastimit;


if($lvloffstim<$stat[attack]){
$sta[1]=$lvloffstim;
}else{
$sta[1]=$stat[attack];
}
if($lvldefstim<$stat[defend]){
$sta[2]=$lvldefstim;
}else{
$sta[2]=$stat[defend];
}
if($lvlagestimit<$stat[speed]){
$sta[3]=$lvlagestimit;
}else{
$sta[3]=$stat[speed];
}
if($lvlslssstimit<$stat[brains]){
$sta[4]=$lvlslssstimit;
}else{
$sta[4]=$stat[brains];
}
if($lvllssstimit<$stat[luck]){
$sta[5]=$lvllssstimit;
}else{
$sta[5]=$stat[luck];
}
if($lvlmhpstimit<$stat[max_hp]){
$sta[6]=$lvlmhpstimit;
}else{
$sta[6]=$stat[max_hp];
}
if($lvlnrgstimit<$stat[max_energy]){
$sta[7]=$lvlnrgstimit;
}else{
$sta[7]=$stat[max_energy];
}
if($lvlmnastimit<$stat[max_mana]){
$sta[8]=$lvlmnastimit;
}else{
$sta[8]=$stat[max_mana];
}

foreach ($sta as $key => $value) {
print"<!-- $key : $hexp + $value =";
$hexp = $hexp+$value;
print" $hexp --> \n
";
}



$iexp = $gexp - $fexp;

$jexp = $hexp - $fexp;


$pct = (($jexp/$iexp) * 100);
$pct = round($pct,"0");


print"
\n <!-- fexp + $fexp --> \n
<!-- gexp + $gexp --> \n
<!-- hexp + $hexp --> \n
<!-- iexp + $iexp --> \n
<!-- jexp + $jexp --> \n
<!-- pct + $pct --> \n
";



if($pct>100){
$pct=100;
}
if($pct<0){
$pct=0;
}

$nexp = $iexp - $jexp;

$opct = 100 - $pct;

if($lvloffstim>$stat[attack]){
$notlvlup = "sadly";
print"<!-- fails attack -->";
}

if($lvldefstim>$stat[defend]){
$notlvlup = "sadly";
print"<!-- fails defend -->";
}

if($lvlagestimit>$stat[speed]){
$notlvlup = "sadly";
print"<!-- fails speed -->";
}

if($lvlslssstimit>$stat[brains]){
$notlvlup = "sadly";
print"<!-- fails brains $lvlslssstimit>$stat[brains] -->";
}

if($lvllssstimit>$stat[luck]){
$notlvlup = "sadly";
print"<!-- fails luck $lvllssstimit>$stat[luck] -->";
}

if($lvlmhpstimit>$stat[max_hp]){
$notlvlup = "sadly";
print"<!-- fails hp -->";
}

if($lvlnrgstimit>$stat[max_energy]){
$notlvlup = "sadly";
print"<!-- fails energy -->";
}

if($lvlmnastimit>$stat[max_mana]){
$notlvlup = "sadly";
print"<!-- fails mana -->";
}

if($notlvlup!="sadly"){
$randap=rand(1,5);
$randcash=rand(1,50);
mysql_query("update characters set stat=stat+$randap where id=$stat[id]");
mysql_query("update characters set level=level+1 where id=$stat[id]");
$credga=$stat[level]*$randcash;
mysql_query("update characters set cash=cash+$credga where id=$stat[id]");
mysql_query("insert into log (owner, log) values($user[id],'You gained a level and $randap stat points and $credga cash')");
print"<br><b>level up!</b><br>";
}

//print"next&nbsp;level&nbsp;:&nbsp;<table width=\"100\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" height=5><tr><td width=\"$pct\" class=\"barfull\"><img src=\"img/small_blank.gif\" width=\"$pct\" height=5></td><td width=\"$opct\" class=\"barempty\"><img src=\"img/small_blank.gif\" width=\"$opct\" height=5></td></tr></table>";

print"<table width=\"160\"><tr><td width=\"60\">
Level: </td><td width=\"100\"><table width=\"100\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" height=5><tr><td width=\"$pct\" class=\"barfull\"><a href=\"javascript:;\" onmouseover=\"return escape('$jexp/$iexp')\"><img src=\"img/small_blank.gif\" width=\"$pct\" height=8 border=0></a></td><td width=\"$opct\" class=\"barempty\"><a href=\"javascript:;\" onmouseover=\"return escape('$jexp/$iexp')\"><img src=\"img/small_blank.gif\" width=\"$opct\" height=8 border=0></a></td></tr></table></td></tr></table>";