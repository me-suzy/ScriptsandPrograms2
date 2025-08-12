<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);
$ADLINK="";

include "auth.php";

$do=$_GET["do"];

if ($do=="reset") {
	if ($_GET["gin"]=="on") $r=mysql_query("UPDATE ".$db["prefix"]."main SET gin=0;") or die(mysql_error());
	if ($_GET["gout"]=="on") $r=mysql_query("UPDATE ".$db["prefix"]."main SET gout=0;") or die(mysql_error());
	if ($_GET["moder"]=="on") $r=mysql_query("UPDATE ".$db["prefix"]."main SET moder_vote=0;") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=counters.php'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}

if ($do=="delete") {
	$r=mysql_query("DELETE FROM ".$db["prefix"]."main WHERE type=2;") or die(mysql_error());
	$r=mysql_query("OPTIMIZE table ".$db["prefix"]."main;") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=counters.php'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}

if ($do=="sync") {
	sync_names();
	sync();
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=counters.php'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}


include "_top.php";

print "<h1>".$LANG["sync"]."</h1>";
?>

<table border=0 cellspacing=1 cellpadding=4 width=600>
<tr><th colspan=2><?=$LANG["syncjumps"];?></th></tr>
<form action=counters.php>
<input type=hidden name='do' value='reset'>
<tr><td class=t2 colspan=2>
<table>
<tr><td><input class=checkbox type=checkbox name=gin></td><td><?=$LANG["syncgin"];?></td></tr>
<tr><td><input class=checkbox type=checkbox name=gout></td><td><?=$LANG["syncgout"];?></td></tr>
<tr><td><input class=checkbox type=checkbox name=moder></td><td><?=$LANG["modervote"];?></td></tr>
</table>
</td></tr>
<tr><td colspan=2>
<input type=submit value='<?=$LANG["syncreset"];?>'><br><br>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>
</form></table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=600>
<tr><th colspan=2><?=$LANG["synccount"];?></th></tr>
<form action=counters.php>
<input type=hidden name='do' value='sync'>
<tr><td colspan=2>
<input type=submit value='<?=$LANG["syncz"];?>'><br><br>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>
</form></table>
<br>

<table border=0 cellspacing=1 cellpadding=4 width=600>
<tr><th colspan=2><?=$LANG["syncdelall"];?></th></tr>
<form action=counters.php>
<input type=hidden name='do' value='delete'>
<tr><td colspan=2>
<?
$r=mysql_query("SHOW TABLE STATUS");
$r1=mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type=2;") or die(mysql_error());
while ($a=mysql_fetch_array($r)) {
	if ($a["Name"]==$db["prefix"]."main") {
		print $LANG["syncavgrowisize"].": ".$a["Avg_row_length"]." ".$LANG["bytes"]."<br>\n";
		print $LANG["syncdelrows"].": <B>".mysql_result($r1,0,0)."</B> ".$LANG["syncfrom"]." ".$a["Rows"]."<br>\n";
		print "<B>".$LANG["syncmustfree"].": ~".(mysql_result($r1,0,0)*$a["Avg_row_length"])." ".$LANG["bytes"]."</B><br>\n";
		}
	}
?>
</td></tr>
<tr><td class=t1 colspan=2>
<input type=submit value='<?=$LANG["delete"];?>'>
</td></tr>
</form></table>
</center>

<?
include "_bottom.php";
?>
