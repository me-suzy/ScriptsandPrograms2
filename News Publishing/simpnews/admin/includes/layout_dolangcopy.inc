<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!$destlang)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_nodestlang?></td></tr>
<?php
	echo "<tr class=\"actionrow\"><td align=\"center\">";
	echo "<a href=\"javascript:history.back()\">$l_back</a>";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
else
{
	if(!isset($dooverwrite))
	{
		$sql="select * from ".$tableprefix."_layout where id='$layoutid' and lang='$destlang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if(mysql_num_rows($result)>0)
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_layoutexists2?></td></tr>
<?php
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
	}
	else
	{
		$sql="delete from ".$tableprefix."_layout where id='$layoutid' and lang='$destlang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
	}
}
$sql="select * from ".$tableprefix."_layout where (lang='$oldlang' and id='$layoutid')";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if(!$myrow = mysql_fetch_array($result))
    die("Could not connect to the database.");
include('./includes/layout_get.inc');
$layoutlang=$destlang;
include('./includes/layout_new.inc');
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutcopied?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_selectlayout?></a></td></tr>
<?php
echo "</table></td></tr></table>";
?>