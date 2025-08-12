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
	$mid=intval($_POST["mid"]);
	$body=mysql_escape_string(StripSlashes($_POST["body"]));
	$headers=mysql_escape_string(StripSlashes($_POST["headers"]));
	$replyto=mysql_escape_string(StripSlashes($_POST["replyto"]));
	$from=mysql_escape_string(StripSlashes($_POST["from"]));
	$subject=mysql_escape_string(StripSlashes($_POST["subject"]));
	$active=$_POST["active"]=="on"?1:0;

	mysql_query("UPDATE ".$db["prefix"]."mail SET body='$body', headers='$headers',replyto='$replyto', ".$db["prefix"]."mail.from='$from', subject='$subject',active=$active  WHERE mid=$mid;") or die(mysql_error());
	header("Location: mail.php?mid=$mid");
	exit;
	}

include "_top.php";

print "<h1>".$LANG["mail_title"]."</h1>";

$mid=intval($_GET["mid"]);
if ($mid!=0) {

	$r=mysql_query("SELECT * FROM ".$db["prefix"]."mail WHERE mid='".$mid."';") or die(mysql_error());
	if ($a=mysql_fetch_assoc($r)) {
		print "<br><B>".$LANG["mail_desc_".$mid]."</B><br><br>\n";
		print "<form action=\"mail.php\" method=\"POST\" style=\"margin:0;\"></a>\n";
		print "<table width=100% cellspacing=0 cellpadding=5 border=0>\n";
		print "<tr><td colspan=\"2\"><table><tr><td><input class=checkbox ".($a["active"]==1?"checked":"")." type=\"checkbox\" name=\"active\"></td><td>".$LANG["mail_send"]."</td></tr></table></td></tr>\n";
		print "<tr><td>".$LANG["mail_subject"].": </td><td><input style=\"width:300px;\" type=\"text\" name=\"subject\" value=\"".mhtml($a["subject"])."\"></td></tr>\n";
		print "<tr><td>".$LANG["mail_from"].":</td><td><input style=\"width:300px;\" type=\"text\" name=\"from\" value=\"".mhtml($a["from"])."\"></td></tr>\n";
		print "<tr><td nowrap>Reply-To: </td><td width=\"100%\"><input style=\"width:300px;\" type=\"text\" name=\"replyto\" value=\"".mhtml($a["replyto"])."\"></td></tr>\n";
		print "<input type=\"hidden\" name=\"mid\" value=\"".$mid."\">\n";
		print "<tr><td colspan=\"2\">".$LANG["mail_headers"].":<br><img src=\"../cat/none.gif\" width=1 height=4><br><textarea rows=5 style=\"width:100%;\" name=\"headers\">".mhtml($a["headers"])."</textarea></td></tr>\n";
		print "<tr><td colspan=\"2\">".$LANG["mail_body"].":<br><img src=\"../cat/none.gif\" width=1 height=4><br><textarea rows=20 style=\"width:100%;\" name=\"body\">".mhtml($a["body"])."</textarea></td></tr>\n";
		print "<tr><td colspan=\"2\"><input type=\"button\" onClick=\"history.back();\" value=\"".$LANG["back"]."\" style=\"width:100px;\">\n";
		print "<input type=\"submit\" value=\"".$LANG["change"]."\" style=\"width:100px;\"></td></tr>\n";
		print "</form>\n";
		}
	include "_bottom.php";
	exit;
	}

include "_bottom.php";
?>
