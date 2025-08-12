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

$sql1 = "SELECT count(*) as t1 FROM ".$mysql_table." where age < '18'";
$sql2 = "SELECT count(*) as t2 FROM ".$mysql_table." where age >= '18' and age <= '27'";
$sql3 = "SELECT count(*) as t3 FROM ".$mysql_table." where age >= '28' and age <= '37'";
$sql4 = "SELECT count(*) as t4 FROM ".$mysql_table." where age >=  '38' and age <= '47'";
$sql5 = "SELECT count(*) as t5 FROM ".$mysql_table." where age > '47'";
$r1 = mysql_query($sql1) or die(mysql_error());
$r2 = mysql_query($sql2) or die(mysql_error());
$r3 = mysql_query($sql3) or die(mysql_error());
$r4 = mysql_query($sql4) or die(mysql_error());
$r5 = mysql_query($sql5) or die(mysql_error());

// counting

$trows1 = mysql_fetch_array($r1);
$num2 = $trows1[0];
$trows2 = mysql_fetch_array($r2);
$num3 = $trows2[0];
$trows3 = mysql_fetch_array($r3);
$num4 = $trows3[0];
$trows4 = mysql_fetch_array($r4);
$num5 = $trows4[0];
$trows5 = mysql_fetch_array($r5);
$num6 = $trows5[0];
$num1 = $num2 + $num3 + $num4 + $num5 + $num6;
for ($i=2;$i<=6;$i++)
{
$n="num".$i;
$width[$i] = $$n * C_WIDTH / $num1;
SetType($width[$i],"integer");
} 
$width[1]=C_WIDTH;

$descr[1]=W_TOTAL_MEMBERS;
$descr[2]=W_AGE.' < 18';
$descr[3]=W_AGE.' 18-27';
$descr[4]=W_AGE.' 28-37';
$descr[5]=W_AGE.' 38-47';
$descr[6]=W_AGE.' > 48';

///////// Templating /////////
$t->set_var("W_STATISTIC", W_STATISTIC);
$t->set_var("W_DESCRIPTION", W_DESCRIPTION);
$t->set_var("W_GRAPHIC", W_GRAPHIC);
$t->set_var("W_QUANTITY", W_QUANTITY);

$t->set_var("TYPE", W_AGE);
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