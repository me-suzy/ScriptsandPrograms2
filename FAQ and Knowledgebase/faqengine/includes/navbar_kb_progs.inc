<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$sql = "select * from ".$tableprefix."_programm where (language='$act_lang') order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
	echo "<td align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize2;\">";
	echo $l_noentries;
	echo "</span></td></tr></table></td></tr></div>";
	include("./includes/bottom2.inc");
	exit;
}
do
{
	$navtree[$cnt][0]=1;
	$navtree[$cnt][1]=display_encoded($myrow["programmname"]);
	$navtree[$cnt][2]=$myrow["progid"];
	$navtree[$cnt][3]=NAV_PROG;
	$navtree[$cnt][4]=0;
	if ($navtree[$cnt][0] > $maxlevel)
		$maxlevel=$navtree[$cnt][0];
	$cnt++;
	$navtree[$cnt][0]=2;
	$navtree[$cnt][1]=$l_withoutcat;
	$navtree[$cnt][2]=$myrow["progid"]."|0";
	$navtree[$cnt][3]=NAV_CAT;
	$navtree[$cnt][4]=0;
	if ($navtree[$cnt][0] > $maxlevel)
		$maxlevel=$navtree[$cnt][0];
	$cnt++;
	$articlesql="select * from ".$tableprefix."_kb_articles where category=0 and programm=".$myrow["prognr"]." order by displaypos asc";
	if(!$articleresult = faqe_db_query($articlesql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if($articlerow=faqe_db_fetch_array($articleresult))
	{
		do
		{
			$navtree[$cnt][0]=3;
			$navtree[$cnt][1]=undo_html_ampersand(stripslashes($articlerow["heading"]));
			$navtree[$cnt][2]=$myrow["progid"]."|0|".$articlerow["articlenr"];
			$navtree[$cnt][3]=NAV_KB;
			$navtree[$cnt][4]=0;
			if ($navtree[$cnt][0] > $maxlevel)
				$maxlevel=$navtree[$cnt][0];
			$cnt++;
		}while($articlerow=faqe_db_fetch_array($articleresult));
	}
	$catsql="select * from ".$tableprefix."_kb_cat where programm=".$myrow["prognr"]." order by displaypos asc";
	if(!$catresult = faqe_db_query($catsql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	while($catrow=faqe_db_fetch_array($catresult))
	{
		$navtree[$cnt][0]=2;
		$navtree[$cnt][1]=display_encoded($catrow["heading"]);
		$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"];
		$navtree[$cnt][3]=NAV_CAT;
		$navtree[$cnt][4]=0;
		if ($navtree[$cnt][0] > $maxlevel)
			$maxlevel=$navtree[$cnt][0];
		$cnt++;
		$subcatsql="select * from ".$tableprefix."_kb_subcat where category=".$catrow["catnr"]." order by displaypos asc";
		if(!$subcatresult = faqe_db_query($subcatsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		// Subcategories available
		if(faqe_db_num_rows($subcatresult)>0)
		{
			while($subcatrow=faqe_db_fetch_array($subcatresult))
			{
				$navtree[$cnt][0]=3;
				$navtree[$cnt][1]=display_encoded($subcatrow["catname"]);
				$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$subcatrow["catnr"];
				$navtree[$cnt][3]=NAV_SUBCAT;
				$navtree[$cnt][4]=0;
				if ($navtree[$cnt][0] > $maxlevel)
					$maxlevel=$navtree[$cnt][0];
				$cnt++;
				$tmpsql="select * from ".$tableprefix."_kb_articles where category=".$catrow["catnr"]." and subcategory=".$subcatrow["catnr"]." order by displaypos asc";
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
					die("Could not connect to the database.");
				}
				if(faqe_db_num_rows($tmpresult)>0)
				{
					while($tmprow=faqe_db_fetch_array($tmpresult))
					{
						$navtree[$cnt][0]=4;
						$navtree[$cnt][1]=undo_html_ampersand($tmprow["heading"]);
						$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$tmprow["articlenr"];
						$navtree[$cnt][3]=NAV_KB;
						$navtree[$cnt][4]=0;
						if ($navtree[$cnt][0] > $maxlevel)
							$maxlevel=$navtree[$cnt][0];
						$cnt++;
					}
				}
			}
			$tmpsql="select * from ".$tableprefix."_kb_articles where category=".$catrow["catnr"]." and subcategory=0 order by displaypos asc";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.");
			}
			if(faqe_db_num_rows($tmpresult)>0)
			{
				while($tmprow=faqe_db_fetch_array($tmpresult))
				{
					$navtree[$cnt][0]=3;
					$navtree[$cnt][1]=undo_html_ampersand($tmprow["heading"]);
					$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$tmprow["articlenr"];
					$navtree[$cnt][3]=NAV_KB;
					$navtree[$cnt][4]=0;
					if ($navtree[$cnt][0] > $maxlevel)
						$maxlevel=$navtree[$cnt][0];
					$cnt++;
				}
			}
		}
		else
		{
			$tmpsql="select * from ".$tableprefix."_kb_articles where category=".$catrow["catnr"]." order by displaypos asc";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.");
			}
			if(faqe_db_num_rows($tmpresult)>0)
			{
				while($tmprow=faqe_db_fetch_array($tmpresult))
				{
					$navtree[$cnt][0]=3;
					$navtree[$cnt][1]=undo_html_ampersand($tmprow["heading"]);
					$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$tmprow["articlenr"];
					$navtree[$cnt][3]=NAV_FAQ;
					$navtree[$cnt][4]=0;
					if ($navtree[$cnt][0] > $maxlevel)
						$maxlevel=$navtree[$cnt][0];
					$cnt++;
				}
			}
		}
	}
}while($myrow=faqe_db_fetch_array($result));
?>