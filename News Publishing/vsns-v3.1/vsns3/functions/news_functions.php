<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: news_functions.php
*	Description: Add, edit, delete, etc. the news articles
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

//Display the edit news article form
function edit_form($act)
{
	global $disable_categories, $prefixes, $path, $cright, $categories;
	$ID = $_GET["id"];
	$author = $cright;

	//Check if you're adding an article or editing
	if ($act == "edit_form")
	{
		$query = mysql_query("SELECT * FROM vsns_news WHERE ID = '$ID'");
		$data = mysql_fetch_array($query);

		$new_act = "edit";
		$sub_value = "Edit Article";

		//Get variables for the form
		extract($data,EXTR_SKIP);
?>
<p class="instructions"><a href="admin.php?act=comments_browse&amp;id=<?php echo $ID;?>">Manage Comments</a></p>
<?php
	}

	if ($act == "add")
	{
		$new_act = "add_news";
		$sub_value = "Add Article";

		$month = date("m");
		$day = date("d");
		$year = date("Y");
	}
?>
<form name="addnews" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="act" value="<?php echo $new_act;?>" />
	<input type="hidden" name="ID" value="<?php echo $ID; ?>" />

<div class="vsns_form">
	<span class="vsns_label">
		<label for="pinned">Pinned Article?</label>
	</span>
	<span class="vsns_field">
<?php
			if ($pinned == 0)
			{
?>
		<input type="radio" name="pinned" id="pinned" value="1" />Yes
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="pinned" id="pinned" value="0" checked="checked" />No
<?php
			}
			if ($pinned == 1)
			{
?>
		<input type="radio" name="pinned" id="pinned" value="1" checked="checked" />Yes
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="pinned" id="pinned" value="0" />No
<?php
			}
?>
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="prefix">Prefix:</label>
<br /><span class="description">Only applicable to pinned items.</span>
	</span>
	<span class="vsns_field">
		<select name="prefix" id="prefix">
			<option value="" selected="selected">Choose a prefix:</option>
<?php
	$prefixes = explode("\n", $prefixes);

	$size = sizeof($prefixes) - 1;
	$i = 0;
	while ($i <= $size)
	{
		if ($prefixes[$size] == $prefix)
		{
			echo "<option value=\"$prefixes[$size]\" selected=\"selected\">$prefixes[$size]</option>\n";
		}
		else
		{
			echo "<option value=\"$prefixes[$size]\">$prefixes[$size]</option>\n";
		}
		$size--;
	}
?>
		</select>
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<span class="otherlabel">Expiry Date:</span>
<br /><span class="description">Date that a pinned article expires. yyyy/mm/dd format.</span>
	</span>
<?php
			if ($pinned == 0)
			{
?>
	<span class="vsns_field">
		<input type="text" name="expires" value="0000-00-00" onfocus="this.select(); return true" maxlength="10" size="9" tabindex="1" />
	</span>
<?php
			}
			if ($pinned == 1)
			{
?>
	<span class="vsns_field">
		<input type="text" name="expires" value="<?php echo $expires;?>" onfocus="this.select(); return true" maxlength="10" size="9" tabindex="1" />
	</span>
<?php
			}
?>
</div>
<?php
	if ($disable_categories == 0)
	{
?>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="category">Category:</label>
	</span>
	<span class="vsns_field">
		<select name="category" id="category">
			<option value="" selected="selected">Choose a category:</option>
<?php
	$categories = explode("\n", $categories);

	$size = sizeof($categories) - 1;
	$i = 0;
	while ($i <= $size)
	{
		if ($categories[$size] == $category)
		{
			echo "<option value=\"$categories[$size]\" selected=\"selected\">$categories[$size]</option>\n";
		}
		else
		{
			echo "<option value=\"$categories[$size]\">$categories[$size]</option>\n";
		}
		$size--;
	}
?>
		</select>
	</span>
</div>
<?php
	}
?>
<div class="vsns_form">
	<span class="vsns_label">
		<span class="otherlabel">Date:</span>
	</span>
	<span class="vsns_field">
		<input type="text" name="year" id="year" value="<?php echo $year;?>" onfocus="this.select(); return true" maxlength="4" size="3" tabindex="7" />
		&nbsp;&nbsp;-&nbsp;&nbsp;
		<input type="text" name="month" id="year" value="<?php echo $month;?>" onfocus="this.select(); return true" maxlength="2" size="2" tabindex="8" />
		&nbsp;&nbsp;-&nbsp;&nbsp;
		<input type="text" name="day" id="year" value="<?php echo $day; ?>" onfocus="this.select(); return true" maxlength="2" size="2" tabindex="9" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="comments">Allow comments?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($comments == "open" || $comments == NULL)
	{
?>
		<input type="radio" name="comments" id="comments" value="open" checked="checked" />Yes
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="locked" />No
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="password" />Password Protect
<?php
	}
	if ($comments == "locked")
	{
?>
		<input type="radio" name="comments" id="comments" value="open" />Yes
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="locked" checked="checked" />No
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="password" />Password Protect
<?php
	}
	if ($comments == "password")
	{
?>
		<input type="radio" name="comments" id="comments" value="open" />Yes
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="locked" />No
		&nbsp;&nbsp;
		<input type="radio" name="comments" id="comments" value="password" checked="checked" />Password Protect
<?php
	}
?>
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="password">Password:</label>
<br />For articles that require a password to comment.
	</span>
	<span class="vsns_field">
		<input type="text" name="password" id="password" value="<?php echo $password;?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="heading">Heading</label>:
	</span>
	<span class="vsns_field">
		<input type="text" name="heading" id="heading" value="<?php echo $heading;?>" size="40" onfocus="this.select(); return true" tabindex="4" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="author">Author</label>:

	</span>
	<span class="vsns_field">
		<input type="text" name="author" id="author" value="<?php echo $author;?>" onfocus="this.select(); return true" />
	</span>
</div>

<div class="vsns_form vsns_commentfield">
	<span class="vsns_label">
		<label for="content">Content</label>:
	</span>
	<span class="vsns_field">
		<textarea name="content" id="content" rows="10" cols="53" onfocus="this.select(); return true"><?php echo $content;?></textarea>
	</span>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>emotes.php', 'emoticons', config='height=500,width=300,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="vsns_assist">Emoticon Guide</a>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>xhtml.php', 'xhtml', config='height=280,width=700,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="vsns_assist">Allowed <abbr title="eXtensible HyperText Markup Language">(X)HTML</abbr></a>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="<?php echo $sub_value;?>" tabindex="6" />
</div>

</form>
<?php
}

//Update the database to reflect edits by users
function edit_news($act)
{
	extract($_POST,EXTR_SKIP);

	switch ($act)
	{
		case "add_news":
			$pubDate = time();
			mysql_query("INSERT INTO vsns_news VALUES (NULL, '$heading', '$content', '$pinned', '$month', '$day', '$year', '$prefix', '$expires', '$category', '$author', '$comments', '$password', '$pubDate')") OR die(mysql_error());
			echo "<p class=\"response\">Entry added.</p>";
			view();
			break;

		case "edit":
		default:
			mysql_query("UPDATE vsns_news SET pinned = '$pinned', comments = '$comments', password = '$password', heading = '" . $heading . "', content = '". $content . "', month = '" . $month . "', day = '".$day."', year = '".$year."', prefix = '".$prefix."', expires = '".$expires."', category = '".$category."', author = '".$author."' WHERE ID = '".$ID."'") or die(mysql_error());
			echo "<p class=\"response\">Entry edited.</p>";
			view();
			break;

		case "Delete":
			$size = sizeof($ID) - 1;
			$i = 0;
			while ($i <= $size)
			{
				mysql_query("DELETE FROM vsns_news WHERE ID = '{$ID[$i]}'") or die(mysql_error());
				$i++;
			}
			echo "<p class=\"response\">Entry deleted.</p>";
			view();
			break;

		case "Unpin":
			$size = sizeof($ID) - 1;
			$i = 0;
			while ($i <= $size)
			{
				mysql_query("UPDATE vsns_news SET pinned = '0' WHERE ID = '{$ID[$i]}'") or die(mysql_error());
				$i++;
			}
			echo "<p class=\"response\">Entry unpinned.</p>";
			view();
			break;

		case "Pin":
			$size = sizeof($ID) - 1;
			$i = 0;
			while ($i <= $size)
			{
				mysql_query("UPDATE vsns_news SET pinned = '1' WHERE ID = '{$ID[$i]}'") or die(mysql_error());
				$i++;
			}
			echo "<p class=\"response\">Entry pinned.</p>";
			view();
			break;
		}
}
?>