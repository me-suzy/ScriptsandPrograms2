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

include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";

$t = new Template;
$t->set_file("templates/".$template_name."/index.html");
$t->set_var("W_TOP_WOMAN", W_TOP_WOMAN);
$t->set_var("W_WELCOME", W_WELCOME);
$t->set_var("W_TOP_MAN", W_TOP_MAN);

$sql = "SELECT * FROM ".$mysql_hits." where gender = '2' and pic != '' order by hits DESC limit 3";
$result = mysql_query($sql) or die(mysql_error());
unset($tmp);
while ($i = mysql_fetch_array($result)) 
{
$tmp = "<a href=view.php?l=".$l."&id=".$i[id]."><img src=".$i[pic]." width=140 border=0></a><br><span class=mes>".$i[user].", ".$i[city]."<br>".W_POPULARITY." : ".$i[hits]."<br><br>";
$t->set_var("TOPWOMANS", $tmp);
$t->parse("1Cycle", "1Cycle", true);
}
if (!isset($tmp))
{
$t->set_var("TOPWOMANS", W_NO_POP_W);
$t->parse("1Cycle", "1Cycle", true);
}
$t->set_var("W_WELCOME_MES", W_WELCOME_MES);
$t->parse("1part", "1part", true);

$sql1 = "SELECT count(id) as total1 FROM ".$mysql_table." where gender = '1'";
$sql2 = "SELECT count(id) as total2 FROM ".$mysql_table." where gender = '2'";
$result1 = mysql_query($sql1) or die(mysql_error());
$result2 = mysql_query($sql2) or die(mysql_error());

// counting
$trows = mysql_fetch_array($result1);
$malenum = $trows[total1];
$trows = mysql_fetch_array($result2);
$femalenum = $trows[total2];
$total = $malenum + $femalenum;

if ($total != 0)
{
$procm = $malenum * 160 / $total; 
$procf = $femalenum * 160 / $total; 
SetType($procm,"integer");
SetType($procf,"integer");

$t->set_var("W_SOME_STAT", W_SOME_STAT);
$t->set_var("W_STATISTIC", W_STATISTIC);
$t->set_var("W_GENDER", W_GENDER);
$t->set_var("W_DESCRIPTION", W_DESCRIPTION);
$t->set_var("W_GRAPHIC", W_GRAPHIC);
$t->set_var("W_QUANTITY", W_QUANTITY);
$t->set_var("W_TOTAL_MEMBERS", W_TOTAL_MEMBERS);
$t->set_var("TOTAL", $total);
$t->set_var("MANSGRAPH", $langgender[1]);
$t->set_var("PROCM", $procm);
$t->set_var("MANSNUM", $malenum);
$t->set_var("WOMANSGRAPH", $langgender[2]);
$t->set_var("PROCF", $procf);
$t->set_var("WOMANSNUM", $femalenum);
$t->parse("statistic", "statistic", true);
}

/* Begin Last users statistic */
if (($total >= $last_reg)&&($last_reg != "0"))
{
$t->set_var("W_LAST", W_LAST);
$t->set_var("C_LAST_REG", $last_reg);
$t->set_var("W_REGISTERED", W_REGISTERED);
$t->set_var("W_USERNAME", W_USERNAME);
$t->set_var("W_CATEGORY", W_CATEGORY);
$t->set_var("W_CITY", W_CITY);
$t->set_var("W_PHOTO", W_PHOTO);

$sql = "SELECT * FROM ".$mysql_table." order by imgtime DESC limit ".$last_reg;
$result = mysql_query($sql)  or die(mysql_error());
$sayi = 0;
while ($i = mysql_fetch_array($result)) {
if ($i[pic] == "") $picav = W_NONE;
else $picav = "<a href=view.php?l=".$l."&id=".$i[id].">".W_YES."</a>";
$sayi++;
$ulink = "<a href=view.php?l=".$l."&id=".$i[id].">".$i[user]."</a>";
$cat = $langgender[$i[gender]]." ".$langpurposes[$i[purposes]];

$t->set_var("SAYI", $sayi);
$t->set_var("ULINK", $ulink);
$t->set_var("CAT", $cat);
$t->set_var("CITY", $i[city]);
$t->set_var("PICAV", $picav);
$t->parse("Cycle", "Cycle", true);
}
$t->parse("lastusers", "lastusers", true);
}

$sql = "SELECT * FROM ".$mysql_hits." where gender = '1' and pic != '' order by hits DESC limit 3";
$result = mysql_query($sql) or die(mysql_error());

unset($tmp);
while ($i = mysql_fetch_array($result)) 
{
$tmp = "<a href=view.php?l=".$l."&id=".$i[id]."><img src=".$i[pic]." width=140 border=0></a><br><span class=mes>".$i[user].", ".$i[city]."<br>".W_POPULARITY." : ".$i[hits]."<br><br>";
$t->set_var("TOPMANS", $tmp);
$t->parse("3Cycle", "3Cycle", true);
}
if (!isset($tmp))
{
$t->set_var("TOPMANS", W_NO_POP_M);
$t->parse("3Cycle", "3Cycle", true);
}
$t->parse("2part", "2part", true);
$t->pparse();
include "templates/footer.php";
?>