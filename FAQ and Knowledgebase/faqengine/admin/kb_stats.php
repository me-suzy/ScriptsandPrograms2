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
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$act_lang="";
$sql = "select SUM(views) from ".$tableprefix."_kb_articles";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
	$totalviews=$myrow["SUM(views)"];
else
	$totalviews=0;
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
?>
<tr class="inforow"><td align="center" colspan="6">
<b><?php echo $l_top10article_prog?></b></td></tr>
<?php
	do {
		$progname=display_encoded($myrow["programmname"]);
		$proglang=$myrow["language"];
		$datasql = "select *, (rating/ratingcount) as rate from ".$tableprefix."_kb_articles where programm=".$myrow["prognr"]." and views>0 order by views desc";;
		if(!$dataresult = faqe_db_query($datasql, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
		if ($datarow = faqe_db_fetch_array($dataresult))
		{
			echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"5\">";
			echo "<b>".display_encoded($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>\n";
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\" colspan=\"2\"><b>$l_article</b></td>";
			echo "<td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
			echo "<td align=\"center\" width=\"10%\">";
			if($enablekbrating==1)
				echo "<b>$l_rating</b>";
			else
				echo "&nbsp;";
			echo "</td></tr>\n";
			do {
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"center\" width=\"5%\">";
				echo $datarow["articlenr"];
				echo "</td>";
				echo "<td align=\"center\" width=\"50%\">";
				echo undo_html_ampersand(stripslashes($datarow["heading"]));
				echo "</td>";
				echo "<td align=\"center\" width=\"5%\">";
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
				if($enablekbrating==1)
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
			echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"5\">";
			echo "<b>".display_encoded($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>";
			echo "<tr class=\"displayrow\"><td colspan=\"5\" align=\"center\">";
			echo "$l_noentries";
			echo "</td></tr>";
		}
	} while($myrow = faqe_db_fetch_array($result));
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="5">
<b><?php echo $l_top10articles?> (<?php echo $l_byviews?>)</b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_kb_articles where (views>0) order by views desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"2\"><b>$l_article</b></td>";
	echo "<td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\" align=\"center\">";
	if($enablekbrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_kb_cat where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database");
		if ($myrow2 = faqe_db_fetch_array($result2))
			$catname=display_encoded($myrow2["catname"]);
		else
			$catname=$l_none;
		$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$result3 = faqe_db_query($sql3, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database");
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
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\" width=\"5%\">".$myrow["articlenr"]."</td>";
		echo "<td align=\"center\" width=\"50%\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		echo "<td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"10%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo do_htmlentities("$percentage%");
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($enablekbrating==1)
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
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"5\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="5">
<b><?php echo $l_top10articles?> (<?php echo $l_byrating?>)</b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_kb_articles where (rating>0) order by (rating/ratingcount) desc limit 10";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"2\"><b>$l_article</b></td>";
	echo "<td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td align=\"center\">";
	if($enablekbrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_kb_cat where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if ($myrow2 = faqe_db_fetch_array($result2))
			$catname=display_encoded($myrow2["catname"]);
		else
			$catname=$l_none;
		$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$result3 = faqe_db_query($sql3, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
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
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\" width=\"10%\">".$myrow["articlenr"]."</td>";
		echo "<td align=\"center\" width=\"30%\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		echo "<td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"10%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo do_htmlentities("$percentage%");
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($enablekbrating==1)
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
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"5\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="5">
<b><?php echo $l_last10articles?> (<?php echo $l_byviews?>)</b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_kb_articles where (views>0) order by views asc limit 10";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"2\"><b>$l_article</b></td>";
	echo "<td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td width=\"10%\" align=\"center\">";
	if($enablekbrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_kb_cat where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database");
		if ($myrow2 = faqe_db_fetch_array($result2))
			$catname=display_encoded($myrow2["catname"]);
		else
			$catname=$l_none;
		$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$result3 = faqe_db_query($sql3, $db))
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database");
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
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\" width=\"5%\">".$myrow["articlenr"]."</td>";
		echo "<td align=\"center\" width=\"50%\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		echo "<td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"10%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo do_htmlentities("$percentage%");
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($enablekbrating==1)
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
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"5\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="5">
<b><?php echo $l_last10articles?> (<?php echo $l_byrating?>)</b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_kb_articles where (rating>0) order by (rating/ratingcount) asc limit 10";
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"rowheadings\"><td align=\"center\" colspan=\"2\"><b>$l_article</b></td>";
	echo "<td align=\"center\" colspan=\"2\"><b>$l_views</b></td>";
	echo "<td align=\"center\">";
	if($enablekbrating==1)
		echo "<b>$l_rating</b>";
	echo "</td></tr>\n";
	do {
		$sql2 = "select * from ".$tableprefix."_kb_cat where (catnr=".$myrow["category"].")";
		if(!$result2 = faqe_db_query($sql2, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql2).");
		if ($myrow2 = faqe_db_fetch_array($result2))
			$catname=display_encoded($myrow2["catname"]);
		else
			$catname=$l_none;
		$sql3 = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$result3 = faqe_db_query($sql3, $db))
			die("<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Could not connect to the database ($sql3).");
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
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\" width=\"10%\">".$myrow["articlenr"]."</td>";
		echo "<td align=\"center\" width=\"30%\">";
		echo "$progname [$proglang] : $catname : ".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		echo "<td align=\"center\" width=\"5%\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td width=\"10%\">";
		if($totalviews>0)
		{
			$percentage=round(($myrow["views"]/$totalviews)*100);
			echo do_htmlentities("$percentage%");
			echo " <img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".round($percentage/3)."\" height=\"10\">";
		}
		echo "</td>";
		echo "<td width=\"10%\" align=\"center\">";
		if($enablekbrating==1)
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
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>\n";
	} while($myrow = faqe_db_fetch_array($result));
}
else
{
	echo "<tr class=\"displayrow\"><td colspan=\"5\" align=\"center\">";
	echo $l_noentries;
	echo "</td></tr>\n";
}
?>
</td></tr>
</table></td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
?>