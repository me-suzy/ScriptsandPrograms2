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
$page_title="$l_synclinkdates ($l_events)";
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
$sql = "select * from ".$tableprefix."_events where linkeventnr!=0";
if(!$result = mysql_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
while($myrow=mysql_fetch_array($result))
{
	$sql2="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
	if(!$result2 = mysql_query($sql2, $db))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
	    die("Could not connect to the database.");
	}
	if($myrow2=mysql_fetch_array($result2))
	{
		$sql3="update ".$tableprefix."_events set date='".$myrow2["date"]."' where eventnr=".$myrow["eventnr"];
		if(!$result3 = mysql_query($sql3, $db))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("Could not connect to the database.");
		}
	}
}
?>
<tr class="displayrow"><td align="center"><?php echo $l_datessynced?></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>