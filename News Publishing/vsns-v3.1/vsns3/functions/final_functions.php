<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: final_functions.php
*	Description: Contains the functions for displaying news
****************************************************************************
*	Build Date: August 17, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//Check for pinned articles that are passed their expiration date
function check_expiry()
{
	global $disp_order;

	$date = date("Y-m-d");
	$expiry_query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '1' ORDER BY ID $disp_order");

	while ($row = mysql_fetch_array($expiry_query))
	{
		if ($date >= $row["expires"] && $row["expires"] != "0000-00-00")
		{
			if ($on_expiry == "delete")
			{
				mysql_query("DELETE FROM vsns_news WHERE pinned = '1' AND expires = '{$row["expires"]}'");
			}
			else
			{
				mysql_query("UPDATE vsns_news SET pinned = '0' WHERE pinned = '1' AND expires = '{$row["expires"]}'");
			}
		}
	}
	mysql_free_result($expiry_query);
}

//Shows categories
function show_categories()
{
	global $disp_order, $limit, $show_date, $show_author, $disable_categories, $hlevel, $path, $categories;

	$categories = explode("\n", $categories);
?>
<form id="cat_navigation" method="get" action="<?php echo $_SERVER["PHP_SELF"];?>">
<div class="cat_navigation">
	<select name="cat" id="cat" onchange="document.forms['cat_navigation'].submit()">
		<option value="" selected="selected">Choose a category:</option>
		<option value="all">All News</option>
		<option value="Pinned">Pinned</option>
<?php
	$size = sizeof($categories) - 1;
	$i = 0;
	while ($i <= $size)
	{
		echo "<option value=\"$categories[$size]\">$categories[$size]</option>\n";
		$size--;
	}
?>
	</select>
</div>
</form>
<?php
}

//Shows general articles
function show_general($type,$id="", $commentform = TRUE)
{
	global $disp_order, $limit, $show_date, $show_author, $disable_comments, $hlevel, $path;

	//Check if we're showing pinned articles
	if ($type == "pinned")
	{
		$query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '1'");
		$css_container = "news_pinned";
	}

	//If not, we're going with general ones
	elseif ($type == "general")
	{
		$query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '0' ORDER BY ID $disp_order LIMIT $limit");
		$css_container = "news_container";
	}

	//Or we could do categories
	elseif ($type == "cat")
	{
		$cat = $_REQUEST["cat"];
		if ($cat == "all")
		{
			show_general("general");
		}
		else
		{
			$query = mysql_query("SELECT * FROM vsns_news WHERE category = '$cat' AND pinned = '0' ORDER BY ID $disp_order LIMIT $limit");
			$css_container = "news_container";
		}
	}

	//Archiving is nice
	elseif ($type == "archive")
	{
		global $month, $year;
		$query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '0' AND month='$month' AND year='$year' ORDER BY ID $disp_order");
		$css_container = "news_container";
	}

	//Ooooh, or do it by ID!  YEAH!
	elseif ($type == "id")
	{
		if (empty($id))
		{
			$id = $_REQUEST["id"];
		}
		$query = mysql_query("SELECT * FROM vsns_news WHERE ID = '$id'");
		$css_container = "news_container";
	}

	//Check to see if the query exists
	if ($query && mysql_num_rows($query) > 0)
	{
		while ($row = mysql_fetch_array($query))
		{

			echo "\n<div class=\"$css_container\">
	<h{$hlevel} class=\"news_heading\">{$row["prefix"]}<a href=\"".$path."archives/{$row["year"]}/{$row["month"]}/{$row["day"]}/{$row["ID"]}/\">{$row["heading"]}</a></h{$hlevel}>\n";
			if ($show_date == 1)
			{
				$hlevel++;
				echo "\t<h{$hlevel} class=\"news_date\">{$row["year"]}-{$row["month"]}-{$row["day"]}</h{$hlevel}>\n";
				$hlevel--;
			}
			if ($show_author == 1)
			{
				$hlevel++;
				echo "\t<h{$hlevel} class=\"news_author\">{$row["author"]}</h{$hlevel}>\n";
				$hlevel--;
			}

			$content = $row["content"];
			$content = stripslashes($content);
			$content = "<p>".$content."</p>\n";
			$content = str_replace("\r\n\r\n", "</p><p>", $content);
			$content = str_replace("\n", "<br />", $content);
			$content = str_replace("</p><p>", "</p>\n\n<p>", $content);
			$content = str_replace("<blockquote>", "</p>\n<blockquote>\n", $content);
			$content = str_replace("</blockquote>", "</blockquote>\n<p>\n", $content);
			$content = str_replace("& ", "&amp; ", $content);
			$content = str_replace("/me", "<span style=\"font-style: italic; font-weight: bold;\">{$row["author"]}</span>", $content);
			$content = replace_emotes($content);

			echo $content;

			if ($disable_comments != 1 && $type != "id")
			{
				show_comments($row["ID"], $row["comments"], $row["month"], $row["year"], $row["day"]);
			}
			elseif ($disable_comments != 1 && $type == "id")
			{
				view_comments($id, $row["comments"],$commentform);
			}

			echo "\n</div>\n";
		}
	mysql_free_result($query);
	}
}

//Shows only the headlines of general articles
function show_headlines($type)
{
	global $disp_order, $limit, $show_date, $show_author, $disable_comments, $path;

	//Check if we're showing pinned articles
	if ($type == "pinned")
	{
		$query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '1'");
		$css_container = "news_pinned";
	}

	//If not, we're going with general ones
	elseif ($type == "general")
	{
		$query = mysql_query("SELECT * FROM vsns_news WHERE pinned = '0' ORDER BY ID $disp_order LIMIT $limit");
		$css_container = "news_container";
	}

	//Or we could do categories
	elseif ($type == "cat")
	{
		$cat = $_REQUEST["cat"];
		$query = mysql_query("SELECT * FROM vsns_news WHERE category = '$cat' AND pinned = '0' ORDER BY ID $disp_order LIMIT $limit");
		$css_container = "news_container";
	}

	//Check to see if the query exists
	if ($query && mysql_num_rows($query) > 0)
	{
		echo "<div class=\"$css_container\">\n<ul class=\"news_list\">\n";
		while ($row = mysql_fetch_array($query))
		{
			echo "<li><a href=\"".$path."archives/{$row["year"]}/{$row["month"]}/{$row["day"]}/{$row["ID"]}/\">{$row["prefix"]}{$row["heading"]}</a>\n";
			if ($show_date == 1)
			{
				echo "<span class=\"news_date\">{$row["date"]}</span>\n";
			}
			echo "</li>\n";
		}
		echo "</ul>\n</div>\n";
		mysql_free_result($query);
	}
}
?>