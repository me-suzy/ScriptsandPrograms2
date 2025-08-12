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

print "<h1>".$LANG["brokenlinks"]."</h1>";

function GetCat($id) {
	GLOBAL $db;

	$r=mysql_query("SELECT name FROM ".$db["prefix"]."cat_linear WHERE cid='$id'");
	if (mysql_num_rows($r)==0) return("No");
	else return(mysql_result($r,0,0));
	}

if (isset($_GET["lid"])) {
	$lid=intval($_GET["lid"]);
	$r=mysql_query("UPDATE ".$db["prefix"]."main SET broken=0 WHERE lid='$lid';") or die(mysql_error());
	}

$r=mysql_query("SELECT * FROM ".$db["prefix"]."main WHERE type=1 AND broken!=0 ORDER by broken DESC LIMIT 10") or die(mysql_error());
if (mysql_num_rows($r)==0) print "<br><b>".$LANG["nobroken"]."</b>";
else {
	while ($R=mysql_fetch_assoc($r)) {

		print "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td valign=\"top\">\n";
		print $R["broken"]."<B>: ".$R["title"]."</B><br><img src=../cat/none.gif width=280 height=4><br>\n";
		print "URL: <a href=\"".$R["url"]."\" target=_blank>".$R["url"]."</a><br><img src=../cat/none.gif width=1 height=4><br>\n";
		print "E-Mail: <a href='mailto:".$R["email"]."'>".$R["email"]."</a>\n";

		print "</td><td width=\"100%\" valign=\"top\">\n";
		print $R["description"]."<br>\n";
		if (!empty($cat["resfield1"])) print "<br><B>".$cat["resfield1"].":</B> ".$R["resfield1"]."<br>\n";
		if (!empty($cat["resfield2"])) print "<B>".$cat["resfield2"].":</B> ".$R["resfield2"]."<br>\n";
		if (!empty($cat["resfield3"])) print "<B>".$cat["resfield3"].":</B> ".$R["resfield3"]."<br>\n";
		print "<br><B>".$LANG["category"].":</B> ".GetCat($R["cat1"]);
		print "</td></tr><tr><td colspan=2><br><table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td>";

		print "<a href='broken.php?lid=".$R["lid"]."'>".$LANG["resetbroken"]."</a> |\n";

		print "<a href='moveto.php?to=2&lid=".$R["lid"]."&type=$type&start=$start'>".$LANG["delete"]."</a> |\n";
		print "<a href='moveto.php?to=0&lid=".$R["lid"]."&type=$type&start=$start'>".$LANG["tonew"]."</a> |\n";
		print $LANG["asubmit"]." |\n";

		print "<a href=edit.php?lid=".$R["lid"]."&type=$type&start=$start>".$LANG["change"]."</a> |\n";

		for ($j=1;$j<11;$j++) {
			if ($j==$R["moder_vote"])
				print "<B>".$j."</B>\n";
			else 
				print "<a href=vote.php?lid=".$R["lid"]."&vote=$j&type=1>".$j."</a>\n";
			}
		print "</td><td align=right>&nbsp;</td></tr></table></td></tr></table>\n";

		print "<br><table width=100%><tr><td background=../cat/dots.gif></td></tr></table><br>";
		}
	}
include "_bottom.php";
?>

