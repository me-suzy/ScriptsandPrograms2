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
$page_title=$l_treeview;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="1" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table class="treebox" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" cols="4">
<?php
$db_lang="";
$sql = "select * from ".$tableprefix."_programm order by language, prognr";
if(!$result = faqe_db_query($sql, $db))
    die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"treebox\"><td align=\"center\">";
	echo $l_noentries;
?>
</td></tr></table></td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
exit;
}
$mycount1=1;
do {
	if($myrow["language"]!=$db_lang)
	{
		echo "<tr class=\"treebox_lang\"><td colspan=\"5\"><b>".$myrow["language"]."</b></td></tr>\n";
		$db_lang=$myrow["language"];
	}
	$prognr=$myrow["prognr"];
	if($admin_rights > 1)
		$node="<a class=\"treebox_prog\" href=\"".do_url_session("program.php?$langvar=$act_lang&mode=edit&input_prognr=$prognr")."\">".display_encoded($myrow["programmname"])."</a> (".$myrow["progid"].")";
	else
		$node=display_encoded($myrow["programmname"])." (".$myrow["progid"].")";
	if($mycount1 < faqe_db_num_rows($result))
	{
		echo "<tr class=\"treebox_prog\"><td width=\"1%\"><img src=\"gfx/tree_split.gif\" border=\"0\"></td><td colspan=\"5\">$node</tr></td>\n";
		$sep1="gfx/tree_vertline.gif";
	}
	else
	{
		echo "<tr class=\"treebox_prog\"><td width=\"1%\"><img src=\"gfx/tree_end.gif\" border=\"0\"></td><td colspan=\"5\">$node</tr></td>\n";
		$sep1="gfx/tree_space.gif";
	}
	$mycount1+=1;
	$sql = "select * from ".$tableprefix."_category where (programm=".$myrow["prognr"].") order by catnr";
	if(!$result2 = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		$mycount2=1;
		do {
			$catnr=$myrow2["catnr"];
			if($admin_rights > 1)
				$node="<a class=\"treebox_cat\" href=\"".do_url_session("categories.php?$langvar=$act_lang&mode=edit&oldprog=$prognr&input_catnr=$catnr")."\">".display_encoded(stripslashes($myrow2["categoryname"]))."</a>";
			else
				$node=display_encoded($myrow2["categoryname"]);
			if($mycount2 < faqe_db_num_rows($result2))
			{
				$sep2="gfx/tree_vertline.gif";
				echo "<tr class=\"treebox_cat\"><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_split.gif\" border=\"0\"></td><td colspan=\"4\">$node</td></tr>\n";
			}
			else
			{
				$sep2="gfx/tree_space.gif";
				echo "<tr class=\"treebox_cat\"><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_end.gif\" border=\"0\"></td><td colspan=\"4\">$node</td></tr>\n";
			}
			$sql = "select * from ".$tableprefix."_data where (category=".$myrow2["catnr"].") order by faqnr";
			if(!$result3 = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($myrow3 = faqe_db_fetch_array($result3))
			{
				$mycount3=1;
				do {
					if($displayrating==1)
					{
						$ratingcount=$myrow3["ratingcount"];
						$rating=$myrow3["rating"];
						if($ratingcount>0)
							$realrating=", ".round($rating/$ratingcount,2);
						else
							$realrating=", --";
					}
					else
						$realrating="";
					if($admin_rights > 1)
						$node="<a class=\"treebox\" href=\"".do_url_session("faq.php?$langvar=$act_lang&mode=edit&input_faqnr=".$myrow3["faqnr"]."&oldcat=$catnr")."\">";
					else
						$node="";
					$node.=undo_html_ampersand(stripslashes($myrow3["heading"]));
					if($admin_rights > 1)
						$node.="</a>";
					$node.=" (".$myrow3["views"]."$realrating)";
					if($myrow3["subcategory"]!=0)
					{
						$tempsql = "select * from ".$tableprefix."_subcategory where catnr=".$myrow3["subcategory"];
						if(!$tempresult=faqe_db_query($tempsql,$db))
						    die("Could not connect to the database.");
						$temprow=faqe_db_fetch_array($tempresult);
						$node.=" [".display_encoded($temprow["categoryname"])."]";
					}
					if($mycount3 < faqe_db_num_rows($result3))
						echo "<tr><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_split.gif\" border=\"0\"></td><td colspan=\"3\">$node</td></tr>\n";
					else
						echo "<tr><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_end.gif\" border=\"0\"></td><td colspan=\"3\">$node</td></tr>\n";
					$mycount3+=1;
					$sql = "select * from ".$tableprefix."_comments where faqnr=".$myrow3["faqnr"]." order by postdate desc";
					if(!$result4=faqe_db_query($sql,$db))
					    die("Could not connect to the database.");
					if($myrow4=faqe_db_fetch_array($result4))
					{
						$mycount4=1;
						do{
							if($ratecomments==1)
							{
								$ratingcount=$myrow4["ratingcount"];
								$rating=$myrow4["rating"];
								if($ratingcount>0)
									$realrating=", ".round($rating/$ratingcount,2);
								else
									$realrating=", --";
							}
							else
								$realrating="";
							list($mydate,$mytime)=explode(" ",$myrow4["postdate"]);
							list($year, $month, $day) = explode("-", $mydate);
							list($hour, $min, $sec) = explode(":",$mytime);
							if($month>0)
								$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
							else
								$displaydate="";
							$commenttext=do_htmlentities($myrow4["email"]);
							$commentdate=do_htmlentities("[".$displaydate."]");
							if($admin_rights > 1)
								$node="<i><a class=\"treebox\" href=\"".do_url_session("usercomments.php?$langvar=$act_lang&mode=display&input_commentnr=".$myrow4["commentnr"])."\">".$commenttext."</a> ".$commentdate."</i> (".$myrow4["views"]."$realrating)";
							else
								$node="<i>".$commenttext." ".$commentdate."</i> (".$myrow4["views"]."$realrating)";
							if($mycount4 < faqe_db_num_rows($result4))
								echo "<tr><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_split.gif\" border=\"0\"></td><td><font size=\"2\">$node</font></td></tr>\n";
							else
								echo "<tr><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_end.gif\" border=\"0\"></td><td><font size=\"2\">$node</font></td></tr>\n";
							$mycount4+=1;
						}while($myrow4=faqe_db_fetch_array($result4));
					}
				} while($myrow3 = faqe_db_fetch_array($result3));
			}
			else
				echo "<tr><td width=\"1%\"><img src=\"$sep1\" border=\"0\"></td><td width=\"1%\"><img src=\"$sep2\" border=\"0\"></td><td width=\"1%\"><img src=\"gfx/tree_end.gif\" border=\"0\"></td><td colspan=\"2\">$l_noentries</td></tr>\n";

			$mycount2+=1;
		} while($myrow2 = faqe_db_fetch_array($result2));
	}
} while($myrow = faqe_db_fetch_array($result));
?>
</table></td></tr></table></tr></td></table>
<?php
include('./trailer.php');
?>