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
include "_top.php";

function GetCat($id) {
	GLOBAL $db;

	$r=mysql_query("SELECT name FROM ".$db["prefix"]."cat_linear WHERE cid='$id'");
	if (mysql_num_rows($r)==0) return($LANG["no"]);
	else return(mysql_result($r,0,0));
	}

$start=intval($_GET["start"]);
$type=intval($_GET["type"]);

$total=mysql_result(mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type=$type;"),0,0);
$pp=10;

if ($type==0) print "<H1>".$LANG["new"]."</H1>";
elseif ($type==1) print "<H1>".$LANG["submited"]."</H1>";
else print "<H1>".$LANG["deleted"]."</H1>";


print "<form action=moveto.php>";
$r=mysql_query("SELECT * FROM ".$db["prefix"]."main WHERE type=$type ORDER BY lid DESC LIMIT $start,$pp");
$cnt=0;
while ($R=mysql_fetch_array($r)) {
	$cnt++;
	$url=$R["url"];
	print "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td valign=\"top\">\n";
	print $R["lid"]."<B>: ".$R["title"]."</B><br><img src=../cat/none.gif width=280 height=4><br>\n";
	print "URL: <a href=\"".$url."\" target=_blank>".$url."</a><br><img src=../cat/none.gif width=1 height=4><br>\n";
	print "E-Mail: <a href='mailto:".$R["email"]."'>".$R["email"]."</a>\n";
	if ($i<$cat["duptestcount"] && $type==0) {
		$url1=$url[strlen($url)-1]=="/"?$url:$url."/";
		$r1=mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE url='".$url1."' AND type=1") or die(mysql_error());
		if (mysql_result($r1,0,0)!=0) {
			print "<br><a href=dups.php?".$url."><small><font color=red>".$LANG["alreadyexists"]."</font></small></a>\n";
			}
		}

	print "</td><td width=\"100%\" valign=\"top\">\n";
	print $R["description"]."<br>\n";
	if (!empty($cat["resfield1"])) print "<br><B>".$cat["resfield1"].":</B> ".$R["resfield1"]."<br>\n";
	if (!empty($cat["resfield2"])) print "<B>".$cat["resfield2"].":</B> ".$R["resfield2"]."<br>\n";
	if (!empty($cat["resfield3"])) print "<B>".$cat["resfield3"].":</B> ".$R["resfield3"]."<br>\n";
	print "<br><B>".$LANG["category"].":</B> ".GetCat($R["cat1"]);
	print "</td></tr><tr><td colspan=2><br><table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td>";

	print "<input type=checkbox name=id_".$R["lid"]." class=checkbox>&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=100%>";

	if ($type!=2) print "<a href=moveto.php?to=2&lid=".$R["lid"]."&type=$type&start=$start>".$LANG["delete"]."</a> |\n";
	else print $LANG["delete"]." |\n";

	if ($type!=0) print "<a href=moveto.php?to=0&lid=".$R["lid"]."&type=$type&start=$start>".$LANG["tonew"]."</a> |\n";
	else print $LANG["tonew"]." |\n";

	if ($type!=1) print "<a href=moveto.php?to=1&lid=".$R["lid"]."&type=$type&start=$start>".$LANG["asubmit"]."</a> |\n";
	else print $LANG["asubmit"]." |\n";

	print "<a href=edit.php?lid=".$R["lid"]."&type=$type&start=$start>".$LANG["change"]."</a> |\n";

	for ($j=1;$j<11;$j++) {
		if ($j==$R["moder_vote"])
			print "<B>".$j."</B>\n";
		else 
			print "<a href=vote.php?lid=".$R["lid"]."&vote=$j&type=$type&start=$start>".$j."</a>\n";
		}
	print "</td></tr></table></td></tr></table>\n";

	print "<br><table width=100%><tr><td background=../cat/dots.gif></td></tr></table><br>";
	}
if ($cnt!=0) {
	print "<br><B>".$LANG["groupop"].":</B><br><br>\n";
	print "<input type=hidden name=type value='".$type."'>\n";
	print "<input type=submit name=op value='".$LANG["delete"]."'>&nbsp;\n";
	print "<input type=submit name=op value='".$LANG["tonew"]."'>&nbsp;\n";
	print "<input type=submit name=op value='".$LANG["asubmit"]."'>\n";
	}
print "</form>\n";

if ($total>$pp) {
	print "<center><br>";
	if ($start!=0) print "<a href=./?type=$type&start=0>&lt;&lt;</a> | ";
	else print "&lt;&lt; | ";
	$sstart=$start-60; if ($sstart<0) $sstart=0;
	$send=$start+60;if ($send>$total) $send=$total;
	for ($i=$sstart;$i<$send;$i+=$pp) {
		if ($start==$i) 
			print "<b>".($i+1)."</b>";
		else
			print "<a href=./?type=$type&start=$i>".($i+1)."</a>";
		print " | ";
		}
	if ($start==$i-$pp) 
		print "&gt;&gt;";
	else
		print "<a href=./?type=$type&start=".($total-$pp).">&gt;&gt;</a>";
	print "</center>";
	}

print "<P>".$LANG["total"].": <B>$total</B>";
include "_bottom.php";
?>
