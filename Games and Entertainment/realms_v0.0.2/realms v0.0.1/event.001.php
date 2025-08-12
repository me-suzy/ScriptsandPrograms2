<?php

        print "<center><br><table border=1 width=98% cellpadding=3 cellspacing=0>
<tr><td class=event>
you see $p
";

$decs = mysql_fetch_array(mysql_query("select * from `pages` where `page`='$p' and `realm`='$stat[world]' order by id limit 1"));
if(!$decs[id]>0){
$decs = mysql_fetch_array(mysql_query("select * from `pages` where `page`='$p' and `realm`='all' order by id limit 1"));
}

if($decs[id]>0){
print", $decs[description]";
}



$numofevents=mysql_num_rows(mysql_query("select * from events where timesperday>'0'"));
$chanceperevent=50;
$event=rand(1,$chanceperevent*$numofevents);

if($stat[rank]==Admin&&$_GET[event]>=1){
$event="$_GET[event]";
}

if($event==1){
print"<br>you found a credit";
mysql_query("update users set credits=credits+1 where id='$stat[id]'");
}

elseif($event==2){
print"<br>Whoa, you find some free health (<b>+10 max HP</b>)";
mysql_query("update users set max_hp=max_hp+10 where id='$stat[id]'");
}

elseif($event==3){
print"<br>In your travels you stumble upon an old mage, he tells you of a story of his powers he once had in 1.*, you of course don't believe him, everyone knows 1.* is a myth...<br>";
$mage=rand(1,10);
if($mage==3||$mage==9){
print"The mage is Angered by your insolence, he casts poison on you, you feel a lack of energy, and your health is failing.... what will become of you? ....";
mysql_query("INSERT INTO `effects` (`player` , `timeleft` , `effect` , `power`) VALUES ('$stat[id]', '100', 'poison', '40')");
}elseif($mage==7){
print"The mage chuckles, and says in a condescending tone \"Of course 1.* was never real hahaha\", he hits you with his club and you feel... odd... somhow... invulnerable...";
mysql_query("INSERT INTO `effects` (`player` , `timeleft` , `effect` , `power`) VALUES ('$stat[id]', '20', 'invulnerability', '40')");
}else{
print"You walk away mumbling about the amount of bums in the realm";
}
}

elseif($event==4){
print"<br>you find a BANDAID";
        mysql_query("insert into health (owner, name, power, cost) values($stat[id],'Bandaid',1,10)") or die("<br>Could not add weapon.");
}

elseif($event==5){
print"<br>you find a COOKIE";
        mysql_query("insert into energy (owner, name, power, cost) values($stat[id],'cookie',1,10)") or die("<br>Could not add weapon.");
}


elseif($event==6){

$da=50;
$do=50;
$dd=50;
$de=50;
$dl=50;
$dc=1000;
$dhp=500;

$ha=1;
$ho=1;
$hd=1;
$he=1;
$hl=1;
$hc=1;
$hhp=10;






$na=$stat[agility];
$no=$stat[offense];
$nd=$stat[defense];
$ne=$stat[energy];
$nl=$stat[level];
$nc=$stat[credits];
$nhp=$stat[hp];


$ra=rand($ha,$da);
$ro=rand($ho,$do);
$rd=rand($hd,$dd);
$re=rand($he,$de);
$rl=rand($hl,$dl);
$rc=rand($hc,$dc);
$rhp=rand($hhp,$dhp);


$ra=$ra*$rl;
$ro=$ro*$rl;
$rd=$rd*$rl;
$re=$re*$rl;

$rhp=$rhp*$rl;


$round=1;

$ndamageb=$no;
$rdamageb=$ro;
$ndamaged=$nd;
$rdamaged=$rd;
$rdamage=$rdamageb-$ndamaged;
$ndamage=$ndamageb-$rdamaged;
if($ndamage<=0){
$ndamage=1;
}
if($rdamage<=0){
$rdamage=1;
}
print"<br>you come accross a beast, half man half oni, it is level $rl, has $rhp HP and $re Energy... is $ro in offense and $rd in defense and has $ra agility.. <br><br>";
$first=1;
while($nhp>0&&$rhp>0 && $ne>0.4 && $re >0.4&&$round<499){


$ground=1;

if($ra>$na&&$first==1){
$rrepeat = ($ra / $na);
$rattackstr = ceil($rrepeat);
if ($rattackstr <= 0) {
$rattackstr = 1;
}
$nrepeat = ($na / $ra);
$nattackstr = ceil($nrepeat);
if ($nattackstr <= 0) {
$nattackstr = 1;
}
$hittime="0";
while($ground <= $rattackstr && $nhp>0 && $rhp >0 && $ne>0.4 && $re >0.4){
$nhp=$nhp-$rdamage;
$ground=$ground+1;
$hittime=$hittime=+1;
$hitfora=$hitfora+$rdamage;
}

$hitfora="0";
$round=$round+1;
$hittime="0";
$ground=1;
while($ground <= $nattackstr && $nhp>0 && $rhp >0 && $ne>0.4 && $re >0.4){
$rhp=$rhp-$ndamage;
$hitforb=$hitforb+$ndamage;
$hittime=$hittime=+1;
$ground=$ground+1;
}
$hitforb="0";
$round=$round+1;
$hittime="0";
$first=2;
}else{
$rrepeat = ($ra / $na);
$rattackstr = ceil($rrepeat);
if ($rattackstr <= 0) {
$rattackstr = 1;
}
$nrepeat = ($na / $ra);
$nattackstr = ceil($nrepeat);
if ($nattackstr <= 0) {
$nattackstr = 1;
}
$hittime="0";
while($ground <= $nattackstr && $nhp>0 && $rhp >0 && $ne>0.4 && $re >0.4){
$rhp=$rhp-$ndamage;
$hitforb=$hitforb+$ndamage;
$ground=$ground+1;
$hittime=$hittime+1;
}

$hitforb="0";
$round=$round+1;
$ground=1;
$hittime="0";
while($ground <= $rattackstr && $nhp>0 && $rhp >0 && $ne>0.4 && $re >0.4){
$nhp=$nhp-$rdamage;
$hitfora=$hitfora+$rdamage;
$ground=$ground+1;
$hittime=$hittime+1;
}

$hitfora="0";
$hittime="0";
$round=$round+1;

$first=1;
}
$re=$re-0.5;
$ne=$ne-0.5;
}

mysql_query("update users set hp=$nhp where id=$stat[id]");
mysql_query("update users set energy=$ne where id=$stat[id]");

if($rhp<=0||$re<=0.4){
$randi=rand(1,20);
$randy=rand(1,20);
$randr=$randi*$randy;
$expgain = ($randr * $rl);
$creditgain = ($rc / 10);
print "You slay the foul beast, gain <b>$expgain</b> EXP and <b>$creditgain</b> credits.<br>";
mysql_query("update users set hp=$nhp where id=$stat[id]");
mysql_query("update users set credits=credits+$creditgain where id=$stat[id]");
mysql_query("update users set exp=exp+$expgain where id=$stat[id]");
mysql_query("update users set hp=hp-$whpla where type='W' and status='1' and owner=$stat[id]");
mysql_query("update users set hp=hp-$whplb where type='W' and status='2' and owner=$stat[id]");
mysql_query("update users set hp=hp-$ahpla where type='A' and status='1' and owner=$stat[id]");
mysql_query("update users set hp=hp-$ahplb where type='A' and status='2' and owner=$stat[id]");
}elseif($nhp<=0){
print "You died...<br>";
}elseif($ne<=0.4){
print"You used your last bit of energy to flee<br>";
}elseif($round>=499){
mysql_query("update users set exp=exp+$round where id=$stat[id]");
print"The fight lasts a long time, you are both tired, you look at each other, shrug and walk away .....<br><i>you find a card that says \"here have $round exp for your trouble\" ";
}else{
print"error<br>";
}


}


elseif($event==666){
print"THE DRAGONS ARE ATTACKING!!!!! you hear the alarm, people are running and screaming, a man walks up to you, he says \"this realm needs a hero, and you... are it. Ready your attack, we're going in...\" and drags you towards the fight<br>";
$dragon=rand(1,50);
if($dragon==5){
print"You manage to kill 1 baby dragon, but then its parents burn and eat you";
mysql_query("update users set exp=exp+500 where id='$stat[id]'");
mysql_query("update users set hp='0' where id='$stat[id]'");
}elseif($dragon==21){
print"You kill a baby dragon, then you are attacked by its parents, luckily you are saved by another hero, who is wearing the latest in fireproof armour, you then go on to kill another baby dragon, and another, until finaly you meet your doom when you trip over and a giant elder dragon falls on top of you dead... killed by the same hero that saved you earlier";
mysql_query("update users set exp=exp+1500 where id='$stat[id]'");
mysql_query("update users set hp='0' where id='$stat[id]'");
}elseif($dragon==42){
$dragkill=rand(1,10);
$dragexp=500*$dragkill;
$dragexp=$dragexp+1500;
print"You kill a baby dragon, then you are attacked by its parents, luckily you are saved by another hero, who is wearing the latest in fireproof armour, you then go on to kill another baby dragon, and another, you go on to kill $dragkill more baby dragons, you helped fend off this attack well and recieved a high ammount of EXP .... good work";
mysql_query("update users set exp=exp+$dragexp where id='$stat[id]'");
}else{
print"You get squashed under a dragons foot before you even face your first target";
mysql_query("update users set hp='0' where id='$stat[id]'");
}
}

print"</tr></table></center>";