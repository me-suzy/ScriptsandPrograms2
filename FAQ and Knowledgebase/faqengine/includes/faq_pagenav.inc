<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function faq_pagenav($start, $maxentries, $totalentries, $actentry, $urlstart)
{
	global $actionbgcolor, $FontFace, $pagenavfontsize, $pagenavfontcolor, $l_page, $l_entries, $l_of;
	global $l_page_first, $l_page_back, $l_page_forward, $l_page_last, $usepagenavicons;
	global $nextpagepic, $prevpagepic, $firstpagepic, $lastpagepic;
	
	echo "<table width=\"100%\" align=\"center\">";
	echo "<tr><td nowrap class=\"pagenav\" align=\"left\" width=\"10%\">";
	echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize color: $pagenavfontcolor;\">";
	if(($maxentries+$start)>$totalentries)
		$displayresults=$totalentries;
	else
		$displayresults=($maxentries+$start);
	$displaystart=$start+1;
	$displayend=$displayresults;
	if((floor(($start+$maxentries)/$maxentries)>1) && ($usepagenavicons==1))
	{
		echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=0\">";
		echo "<img src=\"".$firstpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".($start-$maxentries)."\">";
		echo "<img src=\"".$prevpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	else
		echo "&nbsp;";
	echo "</span></td><td width=\"80%\" align=\"center\">";
	echo "<table width=\"100%\" align=\"center\">";
	echo "<tr><td width=\"100%\" align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize; color: $pagenavfontcolor;\">";
	for($i=1;$i<($totalentries/$maxentries)+1;$i++)
	{
		if(ceil($actentry/$maxentries)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".(($i-1)*$maxentries);
			echo "\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	echo "</span></td></tr>";
	echo "<tr><td width=\"100%\" align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize; color: $pagenavfontcolor; font-weight: bold\">";
	echo "$l_page ".ceil(($start/$maxentries)+1)."/".ceil(($totalentries/$maxentries))."<br>";
	echo "($l_entries $displaystart - $displayend $l_of ".$totalentries.")</span></td></tr></table>";
	echo "</td><td nowrap align=\"right\" width=\"10%\">";
	echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize; color: $pagenavfontcolor; font-weight: bold\">";
	if(($start < (($i-2)*$maxentries)) && ($usepagenavicons==1))
	{
		echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".($start+$maxentries);
		echo "\">";
		echo "<img src=\"".$nextpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".(($i-2)*$maxentries);
		echo "\">";
		echo "<img src=\"".$lastpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	else
		echo "&nbsp;";
	echo "</span></td></tr>";
	echo "</table>";
}
?>