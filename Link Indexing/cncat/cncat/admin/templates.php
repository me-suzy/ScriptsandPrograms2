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

if ($_SERVER["REQUEST_METHOD"]=="POST") {
	$html=mysql_escape_string(StripSlashes($_POST["html"]));
	$name=mysql_escape_string(StripSlashes($_POST["name"]));
	mysql_query("UPDATE ".$db["prefix"]."templates SET html='".$html."' WHERE name='".$name."';") or die(mysql_error());
	header("Location: templates.php");	
	exit;
	}

include "_top.php";

print "<h1>".$LANG["tmpl"]."</h1>";

if (isset($_GET["name"])) {
	$r=mysql_query("SELECT html,name FROM ".$db["prefix"]."templates WHERE name='".mysql_escape_string($_GET["name"])."';") or die(mysql_error());
	if ($a=mysql_fetch_assoc($r)) {
		print "<br><B>".$a["name"]."</B> - ".$LANG["tmpl_".$a["name"]]."<br><br>\n";
		print "<form action=\"templates.php\" method=\"POST\" style=\"margin:0;\"></a>\n";
		print "<input type=\"hidden\" name=\"name\" value=\"".$a["name"]."\">\n";
		print "<textarea rows=20 style=\"width:100%;\" name=\"html\">".StripSlashes(htmlspecialchars($a["html"]))."</textarea>\n";
		print "<input type=\"button\" onClick=\"history.back();\" value=\"".$LANG["back"]."\" style=\"width:100px;\">\n";
		print "<input type=\"submit\" value=\"".$LANG["change"]."\" style=\"width:100px;\">\n";
		print "</form>\n";
		}
	include "_bottom.php";
	exit;
	}

print "<br>\n";
$parent=0;$ul=0;
$r=mysql_query("SELECT name,parent FROM ".$db["prefix"]."templates ORDER BY parent,name") or die(mysql_error());
while ($a=mysql_fetch_assoc($r)) {
	if ($a["parent"]!=$parent) {
		$parent=$a["parent"];
		if ($ul==1) print "</UL>\n";
		print "<P><B>".$LANG["tmpl_title_".$parent]."</B>\n";
		print "<UL>\n";
		$ul=1;
		}
	print "<LI><a href=\"templates.php?name=".$a["name"]."\">".$a["name"]."</a> - ".$LANG["tmpl_".$a["name"]];
	}
print "</UL>\n";

print "<P>".$LANG["tmpl_about"]."</P>";

include "_bottom.php";
?>
