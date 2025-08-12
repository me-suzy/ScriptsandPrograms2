<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
if(!isset($newslang))
	$newslang=$act_lang;
require_once('./auth.php');
$page_title=$l_comments;
require_once('./heading.php');
if($admin_rights < 2)
{
	die($l_functionotallowed);
}
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$subscriptionsendmode=$myrow["subscriptionsendmode"];
	$enablesubscriptions=$myrow["enablesubscriptions"];
	$subject=$myrow["subject"];
	$simpnewsmail=$myrow["simpnewsmail"];
}
else
{
	$subscriptionsendmode=0;
	$enablesubscriptions=0;
	$subject="News";
	$simpnewsmail="simpnews@foo.bar";
}
if(isset($mode))
{
	if($mode=="del")
	{
		$sql = "delete from ".$tableprefix."_comments where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
	}
	if($mode=="edit")
	{
		$sql="select * from ".$tableprefix."_comments where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
			die("No such entry");
		$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["entryref"]);
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_commentnr" value="<?php echo $input_commentnr?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="entryref" value="<?php echo $myrow["entryref"]?>">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_editcomment?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newsentry?>:</td><td>
<a class="shdetailslink" href="javascript:openWindow3('<?php echo $showurl?>','nShow',20,20,400,200);"><?php echo $myrow["entryref"]?></a></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_poster?>:</td><td><?php echo $myrow["poster"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td>
<a href="mailto:<?php echo $myrow["email"]?>"><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td>
<?php echo $displaydate?></td></tr>
<?php
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_text:</td><td>";
		echo "<textarea class=\"sninput\" name=\"commenttext\" rows=\"10\" cols=\"50\">";
		echo undo_htmlentities($myrow["comment"]);
		echo "</textarea>";
		echo "<tr class=\"actionrow\"><td colspan=\"2\" align=\"center\">";
		echo "<input type=\"submit\" class=\"snbutton\" value=\"$l_update\" name=\"submit\">";
		echo "</td></tr></form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("comments.php?$langvar=$act_lang&entryref=".$myrow["entryref"])."\">$l_listofcomments</a></div>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("news.php?$langvar=$act_lang")."\">$l_news</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="update")
	{
		$sql="update ".$tableprefix."_comments set comment='$commenttext' where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		else
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center"><?php echo $l_entryupdated?></td></tr>
<?php
			echo "</table></td></tr></table>";
		}
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql = "select * from ".$tableprefix."_comments where entryref=$entryref order by enterdate desc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if ($myrow = mysql_fetch_array($result))
{
?>
<?php
	do{
		$act_id=$myrow["commentnr"];
		$commenttext=do_htmlentities($myrow["comment"]);
		echo "<tr class=\"displayrow\"><td align=\"center\" width=\"8%\">";
		echo $myrow["commentnr"]."</td>";
		echo "<td align=\"left\">";
		echo "$commenttext</td>";
		echo "<td align=\"center\" width=\"20%\">";
		echo $myrow["enterdate"]."</td>";
		if($admin_rights > 1)
		{
			echo "<td align=\"center\" width=\"2%\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=edit&input_commentnr=$act_id&entryref=$entryref")."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a></td>";
			echo "<td align=\"center\" width=\"2%\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=del&input_commentnr=$act_id&entryref=$entryref")."\"><img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		}
		echo "</tr>";
	}while($myrow=mysql_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td align=\"center\">$l_noentries</td></tr>";
}
echo "</table></td></tr></table>";
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("news.php?$langvar=$act_lang")."\">$l_news</a></div>";
include('./trailer.php')
?>
