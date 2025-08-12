<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: functions.php
*	Description: Generic functions
****************************************************************************
*	Build Date: July 20, 2005
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

//Display the index page (default Admin CP page)
function index_display()
{
?>
<p><abbr title="Very Simple News System">VSNS</abbr> Lemon 3.1 brings several new features such as friendly URIs, RSS feeds, and improved admin functions.</p>
<p>I recommend that you visit the <a href="admin.php?act=update">update</a> page regularly so you can make sure to get any new updates to this version.  If you need help, you can contact me at <a href="mailto:support@tachyondecay.net">support@tachyondecay.net</a>.  Feature requests or any additional comments are also welcome.</p>
<?php
}

//Display the navigation menu
function nav_display($act)
{
	$queuequery = mysql_query("SELECT * FROM vsns_comments WHERE queue = '1'");
	$num = mysql_num_rows($queuequery);
	mysql_free_result($queuequery);

	if ($num > 0)
	{
		$adjacent = "<span style=\"font-weight: bold;\">(".$num.")</span>";
	}
?>
<div class="links">
	<h2 class="navheader">Navigation</h2>
<ul>
	<li class="navtitle">News Management
	<ul>
		<li><a href="admin.php?act=add">Add News</a></li>
		<li><a href="admin.php?act=view">Browse News</a></li>
		<li><a href="admin.php?act=manage_ip">Manage IPs</a></li>
		<li><a href="admin.php?act=queue">Manage Queue <?php echo $adjacent;?></a></li>
	</ul>
	</li>

	<li class="navtitle">Configuration
	<ul>
		<li><a href="admin.php?act=blog_config">Blog Options</a></li>
		<li><a href="admin.php?act=news_config">News Options</a></li>
		<li><a href="admin.php?act=mysql">MySQL Info</a></li>
		<li><a href="admin.php?act=pass">Change Password</a></li>
		<li><a href="admin.php?act=update">Check for Updates</a></li>
	</ul>
	</li>
<?php
		if(!isset($_SESSION['password']) && $act != "login_check")
		{
?>
	<li><a href="admin.php?act=login">Login</a></li>
<?php
		}

		else
		{
?>
	<li><a href="admin.php?act=logout">Logout</a></li>
<?php
		}
?>
</ul>
</div>
<?php
}

//Allows you to view the news articles in an edit mode
function view()
{
	global $disable_categories, $categories;

	$month = $_REQUEST["month"];
	$year = $_REQUEST["year"];
	$cat = $_REQUEST["cat"];
	if (empty($month))
	{
		$month = date("m");
	}
	if (empty($year))
	{
		$year = date("Y");
	}
		$months_array = array(
			"01" => "January",
			"02" => "February",
			"03" => "March",
			"04" => "April",
			"05" => "May",
			"06" => "June",
			"07" => "July",
			"08" => "August",
			"09" => "September",
			"10" => "October",
			"11" => "November",
			"12" => "December",
		);
?>

	<div class="horizontal_nav">
<form id="view_navigation" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<input type="hidden" id="act" name="act" value="view">

		<label for="month">Month:</label>
		<select id="month" name="month">
			<option value="01" selected="selected">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
			<option value="Pinned">Pinned</option>
		</select>

		<label for="year">Year:</label>
		<select id="year" name="year">
<?php
	$yearvars = date("Y");
	$minyear = 2003;

	echo "<option value=\"$yearvars\" selected=\"selected\">$yearvars</option>";

	$i = $yearvars - 1;
	while ($i >= $minyear)
	{
		echo "<option value=\"$i\">$i</option>";
		$i--;
	}
?>
		</select>

		<input type="submit" class="button" value="Go" />
</form>
<?php
	//Navigation via category

	if ($disable_categories == 0)
	{
?>
<form name="cat_navigation" method="get" <?php echo $_SERVER["PHP_SELF"]; ?>">
	<input type="hidden" id="act" name="act" value="view">

	<select id="cat" name="cat">
		<option value="" selected="selected">Choose a category:</option>
<?php
	$categories = explode("\n", $categories);

	$size = sizeof($categories) - 1;
	$i = 0;
	while ($i <= $size)
	{
		echo "<option value=\"$categories[$size]\">$categories[$size]</option>\n";
		$size--;
	}
?>
		<option value="Pinned">Pinned</option>
	</select>
		<input type="submit" class="button" value="Go" />
</form>

<?php
	}
?>
</div>
<?php
	if ($month == "Pinned" || $cat == "Pinned")
	{
		$result = mysql_query("SELECT * FROM vsns_news WHERE pinned='1' ORDER BY ID DESC");
		$num = mysql_num_rows($result);
?>
<h1>Pinned News Articles</h1>

<form id="select_to_edit" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
<?php
		if ($num > 0)
		{
				echo "<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Unpin\" name=\"act\" />
					</p>
					<ul>";
			while ($row = mysql_fetch_array($result))
			{
				echo "<li>
						<input type=\"checkbox\" name=\"ID[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=edit_form&amp;id={$row["ID"]}\" />{$row["heading"]}</a>
					</li>";
			}
				echo "</ul>
					<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Unpin\" name=\"act\" />
					</p>";
		}
		else
		{
			echo "<p>There are no pinned articles at this time.</p>";
		}
		mysql_free_result($result);
?>
</form>
<?php
	}
	elseif (empty($cat))
	{
		$result = mysql_query("SELECT * FROM vsns_news WHERE month = '$month' AND year = '$year' AND pinned = '0' ORDER BY ID DESC");
		$num = mysql_num_rows($result);
		echo "<h2>News Articles for $months_array[$month], $year</h2>\n";
?>
<form id="select_to_edit" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
<?php
		if ($num > 0)
		{
				echo "<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Pin\" name=\"act\" />
					</p>
					<ul>";

			while ($row = mysql_fetch_array($result))
			{
				echo "<li>
						<input type=\"checkbox\" name=\"ID[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=edit_form&amp;id={$row["ID"]}\" />{$row["heading"]}</a>
					</li>";
			}
				echo "</ul>
					<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Pin\" name=\"act\" />
					</p>";
		}
		else
		{
			echo "<p>There are no news articles for $months_array[$month], $year.</p>";
		}
	}
	elseif ($cat)
	{
		$result = mysql_query("SELECT * FROM vsns_news WHERE category = '$cat' ORDER BY ID DESC");
		$num = mysql_num_rows($result);
		echo "<h2>News Articles for $cat</h2>\n";
?>
<form name="select_to_edit" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<input type="hidden" id="act" name="act" value="edit" />
<?php
		if ($num > 0)
		{
				echo "<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Pin\" name=\"act\" />
					</p>
					<ul>";

			while ($row = mysql_fetch_array($result))
			{
				echo "<li>
						<input type=\"checkbox\" name=\"ID[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=edit_form&amp;id={$row["ID"]}\" />{$row["heading"]}</a>
					</li>";
			}
				echo "</ul>
					<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input type=\"submit\" class=\"button\" value=\"Pin\" name=\"act\" />
					</p>";
		}
		else
		{
			echo "<p>No articles found in the $cat category.</p>";
		}
		mysql_free_result($result);
?>
</form>
<?php
	}
}
?>