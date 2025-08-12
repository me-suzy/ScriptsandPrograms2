<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
$t = new Template;

$t->set_file("templates/".$template_name."/stat.html");

$sql1 = "SELECT count(*) as total1 FROM ".$mysql_table." where gender = '1'";
$sql2 = "SELECT count(*) as total3 FROM ".$mysql_table." where gender = '1' and pic != ''";
$sql3 = "SELECT count(*) as total2 FROM ".$mysql_table." where gender = '2'";
$sql4 = "SELECT count(*) as total4 FROM ".$mysql_table." where gender = '2' and pic != ''";
$r1 = mysql_query($sql1) or die(mysql_error());
$r2 = mysql_query($sql2);
$r3 = mysql_query($sql3) or die(mysql_error());
$r4 = mysql_query($sql4);

// counting
$trows = mysql_fetch_array($r1);
$num3 = $trows[0];
$trows = mysql_fetch_array($r2);
$num4 = $trows[0];
$trows = mysql_fetch_array($r3);
$num5 = $trows[0];
$trows = mysql_fetch_array($r4);
$num6 = $trows[0];

$num1 = $num3 + $num5;
$num2 = $num4 + $num6;
$width[1]=C_WIDTH;
for ($i=2;$i<=6;$i++)
{
$n="num".$i;
$width[$i] = $$n * C_WIDTH / $num1;
SetType($width[$i],"integer");
} 

$descr[1]=W_TOTAL_MEMBERS;
$descr[2]=W_WITH_PHOTO;
$descr[3]=$langgender[1];
$descr[4]=W_WITH_PHOTO;
$descr[5]=$langgender[2];
$descr[6]=W_WITH_PHOTO;

///////// Templating /////////
$t->set_var("W_STATISTIC", W_STATISTIC);
$t->set_var("W_DESCRIPTION", W_DESCRIPTION);
$t->set_var("W_GRAPHIC", W_GRAPHIC);
$t->set_var("W_QUANTITY", W_QUANTITY);

$t->set_var("TYPE", W_GENDER);
$t->set_var("URL", $url);

for ($i=1;$i<=6;$i++)
{
$t->set_var("DESCR", $descr[$i]);
$t->set_var("C_WIDTH", C_WIDTH);
$t->set_var("G", $i);
$t->set_var("WIDTH", $width[$i]);
$n="num".$i;
$t->set_var("NUM", $$n);
$t->parse("stat_cycle");
} 
$t->pparse("stat");

?>