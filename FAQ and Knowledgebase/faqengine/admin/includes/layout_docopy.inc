<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!$newlayoutid)
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_nolayoutid?></td></tr>
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
		$sql="select * from ".$tableprefix."_layout where id='$newlayoutid'";
		if(!$result = faqe_db_query($sql, $db))
		    die("Could not connect to the database.");
		if(mysql_num_rows($result)>0)
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2"><?php echo $l_layoutexists?></td></tr>
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
		$sql="delete from ".$tableprefix."_layout where id='$newlayoutid'";
		if(!$result = faqe_db_query($sql, $db))
		    die("Could not connect to the database.");
	}
}
$sql="select * from ".$tableprefix."_layout where id='$oldid'";
if(!$result = faqe_db_query($sql, $db))
    die("Could not connect to the database.");
if(!$myrow = faqe_db_fetch_array($result))
    die("Could not connect to the database.");
include('./includes/layout_get.inc');
$layoutid=$newlayoutid;
$input_mincomment=$mincommentlength;
$input_minquestion=$minquestionlength;
$newfaqdisplaymethod=$faqnewdisplaymethod;
$input_defmailsig=$defmailsig;
$new_dateformat=$dateformat;
$new_extdateformat=$extdateformat;
include('./includes/layout_new.inc');
if(!$result = faqe_db_query($sql, $db))
    die("Unable to connect to database.".faqe_db_error());
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2"><?php echo $l_layoutcopied?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_selectlayout?></a></td></tr>
</table></td></tr></table>
