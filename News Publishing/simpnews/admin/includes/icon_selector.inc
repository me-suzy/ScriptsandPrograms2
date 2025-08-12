<?php
/***************************************************************************
 * (c)2001,2002 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function icon_selector($defval,$fieldname)
{
	global $tableprefix, $db, $l_without, $url_icons, $path_icons;
	$sql = "select * from ".$tableprefix."_icons order by icon_url asc";
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		echo "<table width=\"100%\" class=\"iconselector\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\">";
		echo "<tr><td class=\"iconselector\" align=\"center\">";
		echo "<input type=\"radio\" name=\"$fieldname\" value=\"\"";
		if(!$defval)
			echo " checked";
		echo ">$l_without</td>";
		$iconcount=1;
		do{
			$iconcount++;
			$imagesize = GetImageSize ($path_icons."/".$myrow["icon_url"]);
			echo "<td class=\"iconselector\" align=\"center\"><input type=\"radio\" name=\"headingicon\" value=\"",$myrow["icon_url"]."\"";
			if($defval==$myrow["icon_url"])
				echo " checked";
			echo "> <img width=\"".$imagesize[0]."\" height=\"".$imagesize[1]."\" src=\"$url_icons/".$myrow["icon_url"]."\" border=\"0\" align=\"top\"></td>";
			if($iconcount>4)
			{
				$iconcount=0;
				echo "</tr><tr>";
			}
		}while($myrow=mysql_fetch_array($result));
		echo "</tr></table></td></tr>";
	}
	else
		echo "<input type=\"hidden\" name=\"$fieldname\" value=\"\">$l_without";
}