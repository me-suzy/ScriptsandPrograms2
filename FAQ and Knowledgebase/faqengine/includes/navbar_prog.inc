<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$sql = "select * from ".$tableprefix."_programm where language='$act_lang' and progid='$limitprog' order by displaypos asc";
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
$navtree[$cnt][0]=1;
$navtree[$cnt][1]=display_encoded($myrow["programmname"]);
$navtree[$cnt][2]=$myrow["progid"];
$navtree[$cnt][3]=NAV_PROG;
$navtree[$cnt][4]=0;
if ($navtree[$cnt][0] > $maxlevel)
	$maxlevel=$navtree[$cnt][0];
$cnt++;
$catsql="select * from ".$tableprefix."_category where programm=".$myrow["prognr"]." order by displaypos asc";
if(!$catresult = faqe_db_query($catsql, $db))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("Could not connect to the database.");
}
while($catrow=faqe_db_fetch_array($catresult))
{
	$navtree[$cnt][0]=2;
	$navtree[$cnt][1]=display_encoded($catrow["categoryname"]);
	$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"];
	$navtree[$cnt][3]=NAV_CAT;
	$navtree[$cnt][4]=0;
	if ($navtree[$cnt][0] > $maxlevel)
		$maxlevel=$navtree[$cnt][0];
	$cnt++;
	$subcatsql="select * from ".$tableprefix."_subcategory where category=".$catrow["catnr"]." order by displaypos asc";
	if(!$subcatresult = faqe_db_query($subcatsql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	// Subcategories available
	if(faqe_db_num_rows($subcatresult)>0)
	{
		$tmpsql="select * from ".$tableprefix."_data where category=".$catrow["catnr"]." and subcategory=0 order by displaypos asc";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		if(faqe_db_num_rows($tmpresult)>0)
		{
			$navtree[$cnt][0]=3;
			$navtree[$cnt][1]=$l_withoutsubcat;
			$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|0";
			$navtree[$cnt][3]=NAV_SUBCAT;
			$navtree[$cnt][4]=0;
			if ($navtree[$cnt][0] > $maxlevel)
				$maxlevel=$navtree[$cnt][0];
			$cnt++;
			while($tmprow=faqe_db_fetch_array($tmpresult))
			{
				if($tmprow["linkedfaq"]!=0)
				{
					$tmpsql2="select * from ".$tableprefix."_data where faqnr=".$tmprow["linkedfaq"];
					if(!$tmpresult2 = faqe_db_query($tmpsql2, $db))
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
						die("Could not connect to the database.");
					}
					if($tmprow2=faqe_db_fetch_array($tmpresult2))
						$entrydata=$tmprow2;
					else
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
						die("Could not connect to the database.");
					}
				}
				else
					$entrydata=$tmprow;
				$navtree[$cnt][0]=4;
				$navtree[$cnt][1]=display_encoded($entrydata["heading"]);
				$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$entrydata["faqnr"];
				$navtree[$cnt][3]=NAV_FAQ;
				$navtree[$cnt][4]=0;
				if ($navtree[$cnt][0] > $maxlevel)
					$maxlevel=$navtree[$cnt][0];
				$cnt++;
			}
		}
		while($subcatrow=faqe_db_fetch_array($subcatresult))
		{
			$navtree[$cnt][0]=3;
			$navtree[$cnt][1]=display_encoded($subcatrow["categoryname"]);
			$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$subcatrow["catnr"];
			$navtree[$cnt][3]=NAV_SUBCAT;
			$navtree[$cnt][4]=0;
			if ($navtree[$cnt][0] > $maxlevel)
				$maxlevel=$navtree[$cnt][0];
			$cnt++;
			$tmpsql="select * from ".$tableprefix."_data where category=".$catrow["catnr"]." and subcategory=".$subcatrow["catnr"]." order by displaypos asc";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.");
			}
			if(faqe_db_num_rows($tmpresult)>0)
			{
				while($tmprow=faqe_db_fetch_array($tmpresult))
				{
					if($tmprow["linkedfaq"]!=0)
					{
						$tmpsql2="select * from ".$tableprefix."_data where faqnr=".$tmprow["linkedfaq"];
						if(!$tmpresult2 = faqe_db_query($tmpsql2, $db))
						{
							echo "<tr><td bgcolor=\"$heading_bgcolor\">";
							die("Could not connect to the database.");
						}
						if($tmprow2=faqe_db_fetch_array($tmpresult2))
							$entrydata=$tmprow2;
						else
						{
							echo "<tr><td bgcolor=\"$heading_bgcolor\">";
							die("Could not connect to the database.");
						}
					}
					else
						$entrydata=$tmprow;
					$navtree[$cnt][0]=4;
					$navtree[$cnt][1]=display_encoded($entrydata["heading"]);
					$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$entrydata["faqnr"];
					$navtree[$cnt][3]=NAV_FAQ;
					$navtree[$cnt][4]=0;
					if ($navtree[$cnt][0] > $maxlevel)
						$maxlevel=$navtree[$cnt][0];
					$cnt++;
				}
			}
		}
	}
	else
	{
		$tmpsql="select * from ".$tableprefix."_data where category=".$catrow["catnr"]." order by displaypos asc";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		if(faqe_db_num_rows($tmpresult)>0)
		{
			while($tmprow=faqe_db_fetch_array($tmpresult))
			{
				if($tmprow["linkedfaq"]!=0)
				{
					$tmpsql2="select * from ".$tableprefix."_data where faqnr=".$tmprow["linkedfaq"];
					if(!$tmpresult2 = faqe_db_query($tmpsql2, $db))
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
						die("Could not connect to the database.");
					}
					if($tmprow2=faqe_db_fetch_array($tmpresult2))
						$entrydata=$tmprow2;
					else
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
						die("Could not connect to the database.");
					}
				}
				else
					$entrydata=$tmprow;
				$navtree[$cnt][0]=3;
				$navtree[$cnt][1]=display_encoded($entrydata["heading"]);
				$navtree[$cnt][2]=$myrow["progid"]."|".$catrow["catnr"]."|".$entrydata["faqnr"];
				$navtree[$cnt][3]=NAV_FAQ;
				$navtree[$cnt][4]=0;
				if ($navtree[$cnt][0] > $maxlevel)
					$maxlevel=$navtree[$cnt][0];
				$cnt++;
			}
		}
	}
}
if($allowquestions==1)
{
	$quessql = "select * from ".$tableprefix."_questions where prognr=".$myrow["prognr"]." and language='$act_lang' and publish=1 and faqref=0 order by enterdate desc";
	if(!$quesresult = faqe_db_query($quessql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if(faqe_db_num_rows($quesresult)>0)
	{
		$navtree[$cnt][0]=2;
		$navtree[$cnt][1]=$l_userquestions;
		$navtree[$cnt][2]=$myrow["progid"];
		$navtree[$cnt][3]=NAV_QUESCAT;
		$navtree[$cnt][4]=0;
		if ($navtree[$cnt][0] > $maxlevel)
			$maxlevel=$navtree[$cnt][0];
		$cnt++;
		while($quesrow=faqe_db_fetch_array($quesresult))
		{
			$navtree[$cnt][0]=3;
			if($questionshorting>0)
				$questiontext=substr($quesrow["question"],0,$questionshorting);
			else
				$questiontext=$quesrow["question"];
			$navtree[$cnt][1]=display_encoded($questiontext);
			$navtree[$cnt][2]=$myrow["progid"]."|".$quesrow["questionnr"];
			$navtree[$cnt][3]=NAV_QUES;
			$navtree[$cnt][4]=0;
			if ($navtree[$cnt][0] > $maxlevel)
				$maxlevel=$navtree[$cnt][0];
			$cnt++;
		}
	}
}
?>