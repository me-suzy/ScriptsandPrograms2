<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($backurl))
	$backurl="news.php?$langvar=$act_lang&category=$category";
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_comments?></font></td></tr>
<?php
		$sql = "select * from ".$tableprefix."_comments where entryref=$entryref order by enterdate desc";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			$poster=$myrow["poster"];
			if($myrow["email"])
				$poster.=" (".$myrow["email"].")";
			list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			if($month>0)
				$displaydate=date($dateformat,$temptime);
			else
				$displaydate="";
			echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">";
			echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
			echo get_start_tag($posterstyle);
			echo display_encoded($poster);
			echo get_end_tag($posterstyle);
			echo "</font></td></tr>";
			echo "<tr>";
			echo "<td align=\"left\" bgcolor=\"$timestampbgcolor\" width=\"10%\">";
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
			echo "</font></td></tr>";
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo display_encoded($myrow["comment"]);
			echo "</font></td></tr>";
			echo "<tr bgcolor=\"$bordercolor\"><td>";
			echo "<img src=\"gfx/space.gif\" width=\"1\" height=\"1\" border=\"0\"></td></tr>";
		}
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"$act_script_url?$langvar=$act_lang&amp;mode=new&amp;entryref=$entryref&amp;backurl=".urlencode($backurl)."\">$l_writecomment</a>&nbsp;&nbsp;&nbsp;";
		echo "<a class=\"actionlink\" href=\"$backurl\">$l_news</a>";
		echo "</font></td></tr>";
?>