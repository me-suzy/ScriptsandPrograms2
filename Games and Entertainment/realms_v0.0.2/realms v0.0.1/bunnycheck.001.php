<?php




$charclass = mysql_fetch_array(mysql_query("select * from `charclass` where `character`='$stat[id]' limit 1"));

if($charclass[id]){

$bunnykilled=mysql_num_rows(mysql_query("select * from 1p_records where owner='$stat[id]' and opponent='Rabbit' and outcome='win'"));

$bunnypaid=mysql_num_rows(mysql_query("select * from 1p_records where owner='$stat[id]' and opponent='Rabbit' and outcome='paid'"));

$bunnypaid=$bunnypaid*3;

$bunnypaid=$bunnypaid+3;

if($bunnykilled>=$bunnypaid){
mysql_query("INSERT INTO `1p_records` (`owner`,`opponent`,`outcome`)
                VALUES
                ('$stat[id]','Rabbit','paid')") or die("<br>Could not register charclass.");

mysql_query("update `charclass` set  `bunnyslayer`=`bunnyslayer`+1 where `character`='$stat[id]'");
}

}