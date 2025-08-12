<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_loginlist;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	$dateformat=$myrow["admdateformat"];
	$watchlogins=$myrow["watchlogins"];
	$enablehostresolve=$myrow["enablehostresolve"];
}
else
{
	$dateformat="Y-m-d H:i:s";
	$watchlogins=1;
	$enablehostresolve=1;
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="4"><b><?php echo "$l_ratingcomments ($l_article #$input_articlenr)"?></b></td></tr>
<?php
if(isset($mode))
{
	if($mode=="delete")
	{
		$deleteSQL="delete from ".$tableprefix."_kb_ratings where (entrynr=".$input_entrynr.")";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted</td></tr>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&input_articlenr=$input_articlenr")."\">$l_ratingcomment</a></div>";
		include('./trailer.php');
		exit;
	}
}
$sql = "select * from ".$tableprefix."_kb_ratings where articlenr=$input_articlenr";
if(!$result = faqe_db_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_noentries";
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"10%\"><b>$l_rating</b></td><td align=\"center\" width=\"80%\"><b>$l_comment</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["entrynr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["rating"]."</td>";
		$displaycomment=do_htmlentities($myrow["comment"]);
		$displaycomment=undo_htmlspecialchars($displaycomment);
		$displaycomment=str_replace("\n","<br>",$displaycomment);
		echo "<td align=\"left\">".$displaycomment."</td>";
		echo "<td align=\"center\">";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?input_entrynr=$act_id&input_articlenr=$input_articlenr&$langvar=$act_lang&mode=delete")."\">";
		echo "$l_delete</a>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</table></tr></td></table>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("kb.php?$langvar=$act_lang")."\">$l_articlelist</a></div>";
include('./trailer.php');
?>