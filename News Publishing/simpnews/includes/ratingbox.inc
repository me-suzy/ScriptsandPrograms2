<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
echo "&nbsp;";
if(!$ratingprelude)
	echo $l_ratingquestion;
else
	echo do_htmlentities($ratingprelude);
echo "&nbsp;";
echo "<select name=\"ratingvalue\" class=\"snewsdropdown\">";
$ratingsql="select * from ".$tableprefix."_ratings where lang='$act_lang' order by rating asc";
if(!$ratingresult=mysql_query($ratingsql,$db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(mysql_num_rows($ratingresult)<1)
{
	for($i=count($l_ratings)-1;$i>=0;$i--)
	{
		echo "<option value=\"$i\">";
		echo $l_ratings[$i]."</option>";
	}
}
else
{
	while($ratingrow=mysql_fetch_array($ratingresult))
	{
		echo "<option value=\"".$ratingrow["rating"]."\">";
		echo do_htmlentities($ratingrow["text"]);
		echo "</option>";
	}
}
echo "</select>";
?>