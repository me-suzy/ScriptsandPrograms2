<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title="$l_rebuildsearch ($l_events)";
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$sql = "delete from ".$tableprefix."_evsearch";
if(!$result = mysql_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
$sql = "select * from ".$tableprefix."_events where linkeventnr=0";
if(!$result = mysql_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
while($myrow=mysql_fetch_array($result))
{
	if($myrow["heading"])
		$searchtext = stripslashes($myrow["heading"])." ";
	else
		$searchtext = "";
	$searchtext.= stripslashes($myrow["text"]);
	$searchtext = undo_htmlentities($searchtext);
	$searchtext = remove_htmltags($searchtext);
	$searchtext = strtolower($searchtext);
	$searchtext = trim($searchtext);
	$searchtext = addslashes($searchtext);
	if(strlen($searchtext)>0)
	{
		$sql2 = "insert into ".$tableprefix."_evsearch (eventnr, text) values (".$myrow["eventnr"].", '$searchtext')";
		if(!$result2 = mysql_query($sql2, $db))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
	}
}
?>
<tr class="displayrow"><td align="center"><?php echo $l_searchrebuild?></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>