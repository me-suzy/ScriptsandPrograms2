<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function display_ratings($rating, $ratingcount)
{
	global $l_userrating, $l_none, $l_of, $l_ratingstxt, $url_gfx;
	
	$text="";
	$text.="$l_userrating:&nbsp;";
	if($ratingcount<1)
		$avgrating=0;
	else
		$avgrating = round ($rating/$ratingcount,2);
	$text.="<img align=\"absmiddle\" class=\"rating\" src=\"gfx/ratings/";
	if($ratingcount<1)
		$text.="none.gif\"";
	if($avgrating < 0.5)
		$text.="0.gif\"";
	else if($avgrating < 1.0)
		$text.="0half.gif\"";
	else if($avgrating < 1.5)
		$text.="1.gif\"";
	else if($avgrating < 2.0)
		$text.="1half.gif\"";
	else if($avgrating < 2.5)
		$text.="2.gif\"";
	else if($avgrating < 3.0)
		$text.="2half.gif\"";
	else 
		$text.="3.gif\"";
	
	$text.=" alt=\"".sprintf("%.2f",$avgrating);
	$text.=" $l_of 3.00, $ratingcount $l_ratingstxt\"";
	$text.=" title=\"".sprintf("%.2f",$avgrating);
	$text.=" $l_of 3.00, $ratingcount $l_ratingstxt\"";
	$text.=">";
	return $text;
}

function display_ratings_inline($rating, $ratingcount)
{
	global $l_userrating, $l_none, $l_of, $l_ratingstxt, $url_gfx;
	
	$alttext=$l_userrating.": ";
	$text="";
	if($ratingcount<1)
		$avgrating=0;
	else
		$avgrating = round ($rating/$ratingcount,2);
	$text.="<img align=\"absmiddle\" class=\"rating\" src=\"gfx/ratings/";
	if($ratingcount<1)
		$text.="none.gif\"";
	if($avgrating < 0.5)
		$text.="0.gif\"";
	else if($avgrating < 1.0)
		$text.="0half.gif\"";
	else if($avgrating < 1.5)
		$text.="1.gif\"";
	else if($avgrating < 2.0)
		$text.="1half.gif\"";
	else if($avgrating < 2.5)
		$text.="2.gif\"";
	else if($avgrating < 3.0)
		$text.="2half.gif\"";
	else 
		$text.="3.gif\"";
	$alttext.=sprintf("%.2f",$avgrating)." $l_of 3.00, $ratingcount $l_ratingstxt";
	$text.=" alt=\"$alttext\"";
	$text.=" title=\"$alttext\"";
	$text.=">";
	return $text;
}

function display_faq_ratingcomments($faqnr, $layout)
{
	global $db, $tableprefix, $langvar, $act_lang, $l_ratingcomments;

	$text="";
	$sql="select * from ".$tableprefix."_ratings where faqnr='$faqnr'";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if(faqe_db_num_rows($result)<1)
		return;
	$text.="<br><a class=\"rating\" href=\"rcdisplay.php?$langvar=$act_lang&faqnr=$faqnr&layout=$layout";
	$text.="\" target=\"blank\">";
	$text.="$l_ratingcomments</a>";
	return $text;
}

function display_kb_ratingcomments($articlenr, $layout)
{
	global $db, $tableprefix, $langvar, $act_lang, $l_ratingcomments;
	$text="";
	$sql="select * from ".$tableprefix."_kb_ratings where articlenr='$articlenr'";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if(faqe_db_num_rows($result)<1)
		return;
	$text.="<br><a class=\"rating\" href=\"kbrcdisplay.php?$langvar=$act_lang&articlenr=$articlenr$layout=$layout\" target=\"blank\">";
	$text.="$l_ratingcomments</a>";
	return $text;
}
?>