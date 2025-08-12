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
$page_title=$l_statistics;
require_once('./heading.php');
include_once("./includes/get_layout.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$act_lang="";
$sql = "select * from ".$tableprefix."_programm order by language, prognr";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
?>
</td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
exit;
}
echo "<tr bgcolor=\"#000000\">";
for($i=0;$i<6;$i++)
{
	echo "<td><img border=\"0\" src=\"gfx/space.gif\" height=\"1\" width=\"1\"></td>";
}
echo "</tr>";
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10prog?></b></td></tr>
<?php
	do {
	$progname=display_encoded($myrow["programmname"]);
	$proglang=$myrow["language"];
	$sql = "select * from ".$tableprefix."_category where (programm=".$myrow["prognr"].") order by catnr";
	if(!$result2 = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	$datasql="";
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		$sqlcriteria="";
		$datasql = "select * from ".$tableprefix."_data where ";
		$firstrow=true;
		do {
			if(!$firstrow)
			{
				$sqlcriteria .=" or ";
			}
			else
			{
				$sqlcriteria .="(";
				$firstrow=false;
			}
			$sqlcriteria .="(category=".$myrow2["catnr"].")";
		} while($myrow2 = faqe_db_fetch_array($result2));
	}
	if($datasql)
	{
		$sumsql = "SELECT SUM(views) from ".$tableprefix."_data";
		if(!$sumresult = faqe_db_query($sumsql, $db))
		    die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
		if ($sumrow = faqe_db_fetch_array($sumresult))
			$totalviews=$sumrow["SUM(views)"];
		else
			$totalviews=0;
		$datasql .=$sqlcriteria;
		$datasql .=") AND (views > 0) ORDER BY VIEWS desc limit 10";
		if(!$dataresult = faqe_db_query($datasql, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if ($datarow = faqe_db_fetch_array($dataresult))
		{
			echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
			echo "<b>".display_encoded($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>\n";
			echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
			echo "<td width=\"10%\" align=\"center\">";
			if($displayrating==1)
				echo "<b>$l_rating</b>";
			else
				echo "&nbsp;";
			echo "</td></tr>\n";
			do {
				echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
				echo undo_html_ampersand(stripslashes($datarow["heading"]));
				echo "</td><td align=\"center\" width=\"5%\">";
				echo $datarow["views"];
				echo "</td>";
				echo "<td width=\"10%\">";
				if($totalviews>0)
				{
					$percentage=round(($datarow["views"]/$totalviews)*100);
					echo do_htmlentities("$percentage%");
					echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
				}
				echo "</td>";
				echo "<td width=\"10%\" align=\"center\">";
				if($displayrating==1)
				{
					$rating=$datarow["rating"];
					$ratingcount=$datarow["ratingcount"];
					if($ratingcount>0)
					{
						echo round($rating/$ratingcount,2);
						echo " ($ratingcount)";
					}
					else
						echo "--";
				}
				else
					echo "&nbsp;";
				echo "</td>";
				echo "</tr>\n";
			} while($datarow = faqe_db_fetch_array($dataresult));
		}
		else
		{
			echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
			echo "<b>".display_encoded($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>";
			echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
			echo "$l_noentries";
			echo "</td></tr>";
		}
	}
} while($myrow = faqe_db_fetch_array($result));
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10faq?> (<?php echo $l_byviews?>)</b></td></tr>
<?php
$sql = "select SUM(views) from ".$tableprefix."_data";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
$sql = "select * from ".$tableprefix."_data where (views>0) order by views desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\">";
	if($displayrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($myrow2["categoryname"]);
			$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow2["programm"].")";
			if(!$result3 = faqe_db_query($sql3, $db))
				die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database ($sql3).");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$progname=display_encoded($myrow3["programmname"]);
				$proglang=$myrow3["language"];
			}
			else
			{
				$progname=$l_none;
				$proglang="";
			}
		}
		else
		{
			$catname=$l_none;
			$progname=$l_none;
			$proglang="";
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]));
		echo "</td><td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"15%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo "$percentage%";
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($displayrating==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<?php
if($displayrating==1)
{
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10faq?> (<?php echo $l_byrating?>)</b></td></tr>
<?php
$sql = "select SUM(views) from ".$tableprefix."_data";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
$sql = "select * from ".$tableprefix."_data where (rating>0) order by (rating/ratingcount) desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\">";
	if($displayrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($myrow2["categoryname"]);
			$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow2["programm"].")";
			if(!$result3 = faqe_db_query($sql3, $db))
				die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database ($sql3).");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$progname=display_encoded($myrow3["programmname"]);
				$proglang=$myrow3["language"];
			}
			else
			{
				$progname=$l_none;
				$proglang="";
			}
		}
		else
		{
			$catname=$l_none;
			$progname=$l_none;
			$proglang="";
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo "$progname [$proglang] : $catname : ".undo_htmlentities(stripslashes($myrow["heading"]));
		echo "</td><td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"15%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo "$percentage%";
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($displayrating==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<?php
}
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_last10faq?> (<?php echo $l_byviews?>)</b></td></tr>
<?php
$sql = "select SUM(views) from ".$tableprefix."_data";
if(!$result = faqe_db_query($sql, $db))
	die("<tr bgcolor=\"#cccccc\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
$sql = "select * from ".$tableprefix."_data order by views asc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\">";
	if($displayrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($myrow2["categoryname"]);
			$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow2["programm"].")";
			if(!$result3 = faqe_db_query($sql3, $db))
				die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database ($sql3).");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$progname=display_encoded($myrow3["programmname"]);
				$proglang=$myrow3["language"];
			}
			else
			{
				$progname=$l_none;
				$proglang="";
			}
		}
		else
		{
			$catname=$l_none;
			$progname=$l_none;
			$proglang="";
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]));
		echo "</td><td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"15%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo "$percentage%";
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($displayrating==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<?php
if($displayrating==1)
{
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_last10faq?> (<?php echo $l_byrating?>)</b></td></tr>
<?php
$sql = "select SUM(views) from ".$tableprefix."_data";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
$sql = "select * from ".$tableprefix."_data where (rating>0) order by (rating/ratingcount) asc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\">";
	if($displayrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($myrow2["categoryname"]);
			$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow2["programm"].")";
			if(!$result3 = faqe_db_query($sql3, $db))
				die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database ($sql3).");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$progname=display_encoded($myrow3["programmname"]);
				$proglang=$myrow3["language"];
			}
			else
			{
				$progname=$l_none;
				$proglang="";
			}
		}
		else
		{
			$catname=$l_none;
			$progname=$l_none;
			$proglang="";
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]));
		echo "</td><td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"15%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo "$percentage%";
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($displayrating==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<?php
}
if($allowusercomments==1)
{
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10date?></b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_data order by editdate desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_faq</b></td><td align=\"center\" colspan=\"2\"><b>$l_editdate</b></td>";
	echo "<td width=\"10%\">";
	if($displayrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catname=display_encoded($myrow2["categoryname"]);
			$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow2["programm"].")";
			if(!$result3 = faqe_db_query($sql3, $db))
				die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database ($sql3).");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$progname=display_encoded($myrow3["programmname"]);
				$proglang=$myrow3["language"];
			}
			else
			{
				$progname=$l_none;
				$proglang="";
			}
		}
		else
		{
			$catname=$l_none;
			$progname=$l_none;
			$proglang="";
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]));
		echo "</td><td align=\"center\" colspan=\"2\">";
		list($year, $month, $day) = explode("-", $myrow["editdate"]);
		if($month>0)
			$displaydate=date($layoutdateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo $displaydate;
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($displayrating==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"4\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10comments?></b></td></tr>
<?php
$sql = "select SUM(views) from ".$tableprefix."_comments";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
$sql = "select * from ".$tableprefix."_comments order by views desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_comment</b></td><td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\">";
	if($ratecomments==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		echo "<tr class=\"displayrow\"><td align=\"center\" width=\"5%\">";
		echo $myrow["commentnr"]."</td>";
		echo "<td align=\"center\" width=\"20%\"><font size=\"2\">".do_htmlentities($myrow["email"])."</font>";
		echo "</td><td align=\"center\" width=\"20%\"><font size=\"2\">".$displaydate."</font>";
		echo "</td><td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"15%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo "$percentage%";
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($ratecomments==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	}while($myrow=faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10commentdate?></b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_comments order by postdate desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td colspan=\"6\" align=\"center\">Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"3\"><b>$l_comment</b></td><td align=\"center\" colspan=\"2\"><b>$l_postdate</b></td>";
	echo "<td width=\"10%\">";
	if($ratecomments==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		echo "<tr class=\"displayrow\"><td align=\"center\" width=\"5%\">";
		echo $myrow["commentnr"]."</td>";
		echo "<td align=\"center\" colspan=\"2\">".do_htmlentities($myrow["email"])."";
		echo "</td><td align=\"center\" colspan=\"2\">".$displaydate."";
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($ratecomments==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
		}
		echo "</td>";
		echo "</tr>\n";
	}while($myrow=faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"6\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
}
echo "<tr bgcolor=\"#000000\">";
for($i=0;$i<6;$i++)
{
	echo "<td><img border=\"0\" src=\"gfx/space.gif\" height=\"1\" width=\"1\"></td>";
}
echo "</tr>";
?>
</table></td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
?>