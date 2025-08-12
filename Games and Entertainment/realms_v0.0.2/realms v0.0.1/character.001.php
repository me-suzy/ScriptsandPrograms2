<?php

if($activate){
$ctest = mysql_fetch_array(mysql_query("select * from characters where owner=$user[id] and id=$activate limit 1"));
if($ctest[id]>=1){
mysql_query("update users set activechar=$ctest[id] where id=$user[id]");
print"$ctest[name] was made the active character<br>";
}
}elseif($delete){
$ctest = mysql_fetch_array(mysql_query("select * from characters where owner=$user[id] and id=$delete limit 1"));
if($ctest[id]>=1&&$ctest[id]!=$user[activechar]&&$user[position]!="Guest"){

print"<a href=\"$GAME_SELF?p=character&amp;delete=$ctest[id]&amp;sure=yes\">Are you sure you wish to delete $ctest[name]? click to delete</a>";

if($sure==yes){
mysql_query("delete from characters where id=$ctest[id]");
print"$ctest[name] was deleted forever<br>";
}

}else{
print"error";
}
}else{

if(!$stat[id]){
$ctest = mysql_fetch_array(mysql_query("select * from characters where owner=$user[id] limit 1"));
if($ctest[id]>=1){
mysql_query("update users set activechar=$ctest[id] where id=$user[id]");
print"$ctest[name] was made the active character<br>";
}
}else{


global $name;
global $race;
global $sex;

$ctest = mysql_num_rows(mysql_query("select * from characters where owner=$user[id]"));
if($ctest>=1){
$cost=10*$ctest;
}else{
$cost="0";
}
if($create){
if($user[credits]<$cost){
print"You need more account credits, it costs $cost to make another character<br>";
}else{
        if($cost>=1){
print"It will cost $cost account credits to create another character<BR>
<form method=post action=$GAME_SELF?p=character&make=yes&made=yes&amp;create=yes>
name:<input type=text name=\"name\"><br>
race:<select name=\"race\">
<option value=human>Human</option>
<option value=reptile>Reptile</option>
</select><br>
race:<select name=\"sex\">
<option value=male>Male</option>
<option value=female>Female</option>
</select><br>";
print"
<input type=submit value=GO>";

        }else{
print"Hello, Welcome to $gametitle.<br>
<form method=post action=$GAME_SELF?p=character&make=yes&made=yes&amp;create=yes>
name:<input type=text name=\"name\"><br>";
print"
<input type=submit value=GO>";
}
}
}else{
print"<a href=\"$GAME_SELF?p=character&amp;create=yes\">Create a character</a><br>";
}


if($made=="yes"){

$testname = mysql_fetch_array(mysql_query("select * from characters where name='$name'"));

if(!$name){
print"<br> Failed, please fill out all sections";
}elseif($testname[id]>0){
print"the name $name is taken... please try a different one";
}else{
if(!$race){ $race="Human"; }

$newestid=1;
$testat = mysql_fetch_array(mysql_query("select * from characters where id='$newestid'"));
while($testat[id]){
$newestid=$newestid+1;
$testat = mysql_fetch_array(mysql_query("select * from characters where id='$newestid'"));
}

mysql_query("INSERT INTO `characters` (`id`,`owner`,`name`,`race`,`sex`)VALUES('$newestid','$user[id]','$name','$race','$sex')") or die("<br>Could not register.");
mysql_query("update users set credits=credits-$cost where id=$user[id]");
print "<br><B>Character $name created</B><br>";
}
}
}


$ctest = mysql_query("select * from characters where owner=$user[id]");
print"<br>Click a name to activate character<br><center><table border=1 cellpadding=5 cellspacing=5><tr>";
$jump = 0;
while($char = mysql_fetch_array($ctest)){
if($jump>=3){
$jump=0;
print"</tr><tr>";
}

print"<TD>";
if($char[id]!=$user[activechar]){
print"<a href=\"$GAME_SELF?p=character&amp;activate=$char[id]\">$char[name]</a>";
}else{
print"$char[name] (active)";
}
print"<br>Level : $char[level]
<br>race : $char[race]
<br>cash : $char[cash]
";
if($char[sex]=="female"){
print"<br>sex: Female";
}else{
print"<br>sex: Male";
}

if($char[id]!=$user[activechar]){
print"<br><a href=\"$GAME_SELF?p=character&amp;delete=$char[id]\">Delete $char[name]</a>";
}

print"</td>";
/*<BR>hp : $char[max_hp]
<br>energy : $char[max_energy]
<br>mana : $char[max_mana]
<br>attack : $char[attack]
<br>defend : $char[defend]
<br>speed : $char[speed]
<br>brains : $char[brains]
<br>luck : $char[luck]
<br>ore : $char[ore]
<br>gold : $char[gold]
<br>gems : $char[gems]
*/
$jump++;
}

print"</tr></table></center>";


}