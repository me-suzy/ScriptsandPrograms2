<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: comments_functions.php
*	Description: All comment-managing functions
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

//Browse comments in Admin CP
function comment_browse($ID)
{
	$headingquery = mysql_query ("SELECT heading FROM vsns_news WHERE ID = '$ID'");
	$heading = mysql_fetch_array($headingquery);
	$heading = $heading[0];

	$commentsquery = mysql_query("SELECT * FROM vsns_comments WHERE article_id = '$ID'");
	$num = mysql_num_rows($commentsquery);

	echo "<h2>Manage Comments for <em>$heading</em></h2>\n";
?>
<p class="instructions">Select a comment to edit or delete.</p>
<form name="select_to_edit" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<input type="hidden" id="act" name="act" value="edit_comment" />
	<input type="hidden" id="article_id" name="article_id" value="<?php echo $ID;?>" />
<?php
		if ($num > 0)
		{
				echo "<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete Comment\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					</p>
					<ul>";

			while ($row = mysql_fetch_array($commentsquery))
			{
				echo "<li>
						<input type=\"checkbox\" name=\"ID[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=comment_edit&amp;id={$row["ID"]}&amp;article_id={$ID}\" />{$row["name"]} ({$row["ip"]}) @ {$row["date"]}</a>
					</li>";
			}
				echo "</ul>
					<p style=\"text-align: center;\">
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', true)\">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<a href=\"javascript: SetAllCheckBoxes('select_to_edit', 'ID[]', false)\">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<strong>With Selected:</strong>
					<input type=\"submit\" class=\"button\" value=\"Delete Comment\" name=\"act\" /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					</p>";
		}
		else
		{
			echo "<p class=\"response\">No comments found for <em>$heading</em>.</p>";
		}
	mysql_free_result($headingquery);
	mysql_free_result($commentsquery);
}

//Edit functions: add, delete, edit
function comment_edit($act)
{
	global $vsnsemail, $queue, $notification, $cright, $sitename, $path;
	extract($_POST,EXTR_SKIP);

	if ($act == "add_comment")
	{
		$ip = $_SERVER["REMOTE_ADDR"];

		//Check if their IP is banned
		$ipquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'banned_ip'");
		if ($ipquery && mysql_num_rows($ipquery) > 0)
		{
			$ipdata = mysql_fetch_array($ipquery);
			$ipdata = $ipdata["config_value"];
			$ipdata = explode("\n", $ipdata);
			mysql_free_result($ipquery);
		}


		if (in_array($ip, $ipdata))
		{
			echo "<p class=\"response\">Your have been banned from making comments.</p>";
			show_general("id",$article_id,FALSE);
		}

		//Validation!

		elseif ($comments == "locked")
		{
			$query00 = mysql_query("SELECT comments,year,month,day FROM vsns_news WHERE ID = '$article_id'");
			$data = mysql_fetch_array($query00);
			extract($data,EXTR_SKIP);
			mysql_free_result($query00);
			echo "<p class=\"response\">Sorry, but this article is locked from further comment.</p>";
		}

		elseif (empty($name) || empty ($commentemail) || empty($comment))
		{
			echo "<p class=\"response\">The name, email, and comment fields cannot be left blank.</p>";
			show_general("id",$article_id,FALSE);
			comment_form("comment_add", $article_id, "", $name, $commentemail, $website, $comment);
		}

		elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $commentemail))
		{
			echo "<p class=\"response\">You did not enter a valid email address.</p>";
			show_general("id",$article_id,FALSE);
			comment_form("comment_add", $article_id, "", $name, $commentemail, $website, $comment);
		}

		elseif(md5($visual) != $answer)
		{
			echo "<p class=\"response\">Sorry, an error occurred.  You failed to verify you were a person (and not a bot).</p>";
			show_general("id",$article_id,FALSE);
			comment_form("comment_add", $article_id, "", $name, $commentemail, $website, $comment);
		}

		elseif(strlen($comment) < 5)
		{
			echo "<p class=\"response\">Comments must be at least five characters long.</p>";
			show_general("id",$article_id,FALSE);
			comment_form("comment_add", $article_id, "", $name, $commentemail, $website, $comment);
		}

		else
		{
			$ip = $_SERVER["REMOTE_ADDR"];
			$date = date("Y-m-d H:i:s");
			$link = $path."archives/".$year."/".$month."/".$day."/".$article_id."/";
			$pubDate = time();

			mysql_query("INSERT INTO vsns_comments VALUES(NULL, '$article_id', '$ip', '$name', '$commentemail', '$website', '$comment', '$date', '$pubDate','$queue')") or die(mysql_error());
			$id = mysql_insert_id();

			if ($queue == 1)
			{
				echo "<p class=\"response\">Your comment will have to be previewed and approved before it is added.  Have a nice day!</p>";
				show_general("id",$article_id);
			}

			else
			{
				echo "<p class=\"response\">Thank you for your comment.  <a href=\"#comment{$id}\">View your comment</a>.</p>";
				show_general("id",$article_id);
			}

			if ($notification == 1)
			{
				$comment = stripslashes($comment);
				$comment = strip_tags($comment, "<a><em><strong><code><blockquote><abbr><acronym>");
				$comment = preg_replace("/(\s)style\=('|\")(.*)('|\")/i", "", $comment);
				$comment = "<p>".$comment."</p>\n";
				$comment = str_replace("\r\n\r\n", "</p><p>", $comment);
				$comment = str_replace("\n", "<br />", $comment);
				$comment = str_replace("</p><p>", "</p>\n\n<p>", $comment);
				$comment = str_replace("<blockquote>", "</p>\n<blockquote>\n", $comment);
				$comment = str_replace("</blockquote>", "</blockquote>\n<p>\n", $comment);
				$comment = str_replace("& ", "&amp; ", $comment);
				$comment = str_replace("/me", "<span style=\"font-style: italic; font-weight: bold;\">{$row["name"]}</span>", $comment);
				$comment = replace_emotes($comment);
				$subject = $sitename." has a new comment";
				$headers = "From: $sitename <$commentemail>\r\nContent-type: text/html\r\n";
$message = <<<FWCI
<p>$cright:</p>

<p>Your blog has a new comment!  Click the link below to see it.</p>

<p><a href="${path}archive.php?id=$article_id">${path}archive.php?id=${article_id}#${id}</a></p>

<p>The contents of the comment are as follows:</p>

<p><strong>From:</strong> $name<br />
<strong>Email:</strong> $commentemail<br />
<strong>Website:</strong> $website<br />
<strong>Comment:</strong></p>

$comment
FWCI;

				mail($vsnsemail, $subject, $message, $headers);
			}
		}
	}

	if ($act == "edit_comment")
	{
		mysql_query("UPDATE vsns_comments SET name = '$name', commentemail = '$commentemail', website = '$website', comment = '$comment' WHERE ID = '$id'");
		echo "<p class=\"response\">Comment edited.</p>";
		comment_browse($article_id);
	}

	if ($act == "Delete Comment")
	{
		$id = $_REQUEST["ID"];
		$size = sizeof($id);
		$i = 0;
		while ($i <= $size)
		{
			mysql_query("DELETE FROM vsns_comments WHERE ID = '{$id[$i]}'") or die(mysql_error());
			$i++;
		}
		echo '<p class="response">Comment(s) deleted.</p>';
		comment_browse($article_id);
	}
}

//Comment Form
function comment_form($act, $article_id, $password = "", $name = "", $commentemail = "", $website = "", $comment = "")
{
	global $path;
	$ID = $_GET["id"];

	$commentquery = mysql_query("SELECT * FROM vsns_news WHERE ID = '$article_id'") or die(mysql_error());
	$commentdata = mysql_fetch_array($commentquery);
	extract($commentdata,EXTR_PREFIX_ALL,"data");
	mysql_free_result($commentquery);

	if ($password != $data_password && $comments == "password")
	{
		echo "The password you entered was incorrect.";
		show_general("id",$article_id);
	}

	//Editing comments through Admin CP
	if ($act == "comment_edit")
	{
		$query = mysql_query("SELECT * FROM vsns_comments WHERE ID = '$ID'") or die(mysql_error());
		$data = mysql_fetch_array($query);

		$new_act = "edit_comment";
		$sub_value = "Edit Comment";

		//Get variables for the form
		extract($data,EXTR_OVERWRITE);
?>
<p style="text-align: center;"><strong>IP Address:</strong> <?php echo $ip;?> <a href="<?php echo $path;?>admin.php?act=ban_ip&amp;ip=<?php echo $ip;?>">Ban this IP</a>.
<br />
<strong>Date:</strong> <?php echo $date;?></p>
<?php
	}

	if ($act == "queue_form")
	{
		$query = mysql_query("SELECT * FROM vsns_comments WHERE ID = '$ID'") or die(mysql_error());
		$data = mysql_fetch_array($query);

		$new_act = "update_queue";
		$sub_value = "Approve Comment";

		//Get variables for the form
		extract($data,EXTR_OVERWRITE);
?>
<p style="text-align: center;"><strong>IP Address:</strong> <?php echo $ip;?> <a href="<?php echo $path;?>admin.php?act=ban_ip&amp;ip=<?php echo $ip;?>">Ban this IP</a>.
<br />
<strong>Date:</strong> <?php echo $date;?></p>
<?php
	}
?>
<form id="add_comment_form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php
	//Adding comments through blog
	if ($act == "comment_add")
	{
		$new_act = "add_comment";
		$sub_value = "Add Comment";

		$wordnums = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty");

		$num1 = rand(0,20);
		$num2 = rand(0,20);

		$answer = $num1 + $num2;
		$answer = md5($answer);

		$question = $wordnums[$num1]." plus ".$wordnums[$num2];
?>
<p class="instructions">Enter your comment.  The website field is optional, and your email will not be shown.</p>
<div class="vsns_form">
	<p style="text-align: center;">To verify you are a real person, please answer this simple question: What is <strong><?php echo $question; ?></strong>?</p>
	<span class="vsns_label">
		<label for="visual">Answer in the form of a number, eg, 42</label>
	</span>
	<span class="vsns_field">
		<input type="text" name="visual" id="visual" tabindex="1" />
		<input type="hidden" name="answer" value="<?php echo $answer; ?>" tabindex="1" />
	</span>
</div>

<?php
	}
?>
<div class="vsns_form">
	<input type="hidden" name="act" value="<?php echo $new_act;?>" />
	<input type="hidden" name="id" value="<?php echo $ID;?>" />
	<input type="hidden" name="article_id" value="<?php echo $article_id;?>" />
	<span class="vsns_label">
		<label for="name">Name</label>:
	</span>
	<span class="vsns_field">
		<input type="text" name="name" id="name" value="<?php echo $name;?>" tabindex="2" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="commentemail">Email</label>:
	</span>
	<span class="vsns_field">
		<input type="text" name="commentemail" id="commentemail" value="<?php echo $commentemail;?>" tabindex="3" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="website">Website</label>:
	</span>
	<span class="vsns_field">
		<input type="text" name="website" id="website" value="<?php echo $website;?>" tabindex="4" />
	</span>
</div>

<div class="vsns_form vsns_commentfield">
	<span class="vsns_label">
		<label for="comment">Comment</label>:
	</span>
	<span class="vsns_field" style="clear: both;">
		<textarea name="comment" id="comment" cols="50" rows="10" tabindex="5"><?php echo $comment;?></textarea>
	</span>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>emotes.php', 'emoticons', config='height=500,width=300,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="vsns_assist">Emoticon Guide</a>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>xhtml.php', 'xhtml', config='height=280,width=700,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="vsns_assist">Allowed <abbr title="eXtensible HyperText Markup Language">(X)HTML</abbr></a>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" value="<?php echo $sub_value;?>" name="mode" tabindex="6" />
</div>
</form>

<?php
}

//Manage queued entries
//These are entries that are pending
//approval; it's a setting that can
//be toggled in the config
function manage_queue($act)
{
	if ($act == "queue")
	{
		$query = mysql_query ("SELECT * FROM vsns_comments WHERE queue = '1'");
?>
<form id="queued_posts" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
<p style="text-align: center;">
	<input type="hidden" name="act" value="update_queue" />
	<a href="javascript: SetAllCheckBoxes('queued_posts', 'ID[]', true)">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<a href="javascript: SetAllCheckBoxes('queued_posts', 'ID[]', false)">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<strong>With Selected:</strong>
	<input type="submit" class="button" value="Approve" name="mode" />&nbsp;&nbsp;&nbsp;
	<input type="submit" class="button" value="Delete" name="mode" />
</p>
<ul>
<?php
		if ($query && mysql_num_rows($query) > 0)
		{
			while ($row = mysql_fetch_array($query))
			{
				$abbr = substr($row["comment"], 0, 80);
				echo "<li><input type=\"checkbox\" name=\"ID[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=queue_form&amp;id={$row["ID"]}\" title=\"$abbr\" />{$row["name"]} at {$row["date"]}</a></li>\n";
			}
		}
		else
		{
			echo '<li style="font-weight: bold; text-align: center;">There are currenly no entries awaiting approval.</li>';
		}
?>
</ul>
<p style="text-align: center;">
	<input type="hidden" name="act" value="update_queue" />
	<a href="javascript: SetAllCheckBoxes('queued_posts', 'ID[]', true)">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<a href="javascript: SetAllCheckBoxes('queued_posts', 'ID[]', false)">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<strong>With Selected:</strong>
	<input type="submit" class="button" value="Approve" name="mode" />&nbsp;&nbsp;&nbsp;
	<input type="submit" class="button" value="Delete" name="mode" />
</p>
</form>
<?php
	}

	elseif ($act == "update_queue")
	{
		$mode = $_REQUEST["mode"];

		if ($mode == "Delete")
		{
			$ID = $_REQUEST["ID"];
			$size = sizeof($ID);

			if ($size > 0)
			{
				$i = 0;
				while ($i <= $size)
				{
					mysql_query("DELETE FROM vsns_comments WHERE ID = '{$ID[$i]}'") or die(mysql_error());
					$i++;
				}
				echo "<p class=\"response\">Entry deleted.</p>";
				manage_queue("queue");
			}
			else
			{
				echo "<p class=\"response\">Please select at least one entry to delete.</p>";
				manage_queue("queue");
			}
		}

		elseif ($mode == "Approve")
		{
			$ID = $_REQUEST["ID"];
			$size = sizeof($ID);

			if($size > 0)
			{
				$i = 0;
				while ($i <= $size)
				{
					mysql_query("UPDATE vsns_comments SET queue = '0' WHERE ID = '{$ID[$i]}'") or die(mysql_error());
					$i++;
				}
				echo "<p class=\"response\">Entry approved.</p>";
				manage_queue("queue");
			}
			else
			{
				echo "<p class=\"response\">Please select at least one entry to approve.</p>";
				manage_queue("queue");
			}
		}

		elseif ($mode == "Approve Comment")
		{
			extract($_POST, EXTR_SKIP);

			mysql_query("UPDATE vsns_comments SET name = '$name', commentemail = '$commentemail', website = '$website', comment = '$comment', queue = '0' WHERE ID = '$id'") or die(mysql_error());

			echo "<p class=\"response\">Entry approved.</p>";
			manage_queue("queue");
		}
	}
}

//Shows number of comments with link to add a comment
function show_comments($id, $comments, $month, $year, $day)
{
	global $path;
	$comments_query = mysql_query("SELECT * FROM vsns_comments WHERE article_id = '".$id."' ORDER BY ID ASC");
	$num_comments = mysql_num_rows($comments_query);

	if ($num_comments == 0)
	{
		$num_statement = "There are <strong>no</strong> comments yet.";
	}
	elseif ($num_comments == 1)
	{
		$num_statement = "<a href=\"".$path."archives/{$year}/{$month}/{$day}/{$id}/\">There is <strong>1</strong> comment.</a>";
	}
	else
	{
		$num_statement = "<a href=\"".$path."archives/{$year}/{$month}/{$day}/{$id}/\">There are <strong>$num_comments</strong> comments.</a>";
	}

	if ($comments == "open" || $comments == NULL)
	{
		echo "<span class=\"news_comments_span\">
	$num_statement
	&nbsp;&nbsp;&nbsp;
	<a href=\"".$path."archives/{$year}/{$month}/{$day}/{$id}/\">Add a comment</a>
</span>";
	}
	if ($comments == "locked")
	{
		echo "<span class=\"news_comments_span\">
	$num_statemtent
	&nbsp;&nbsp;&nbsp;
	This article is locked from further comment.
</span>";
	}
	if ($comments == "password")
	{
		echo "<span class=\"news_comments_span\">
	$num_statement
	&nbsp;&nbsp;&nbsp;
	<a href=\"".$path."archives/{$year}/{$month}/{$day}/{$id}/\">Add a comment (password required)</a>
</span>";
	}
	mysql_free_result($comments_query);
}

//Viewing comments
function view_comments($article_id, $comments, $commentform)
{
	global $path;
	$comment_query = mysql_query("SELECT * FROM vsns_comments WHERE article_id = '$article_id' AND queue = '0' ORDER BY ID ASC");

	if ($comment_query && mysql_num_rows($comment_query) > 0)
	{
		while ($row = mysql_fetch_array($comment_query))
		{
			echo "<div class=\"news_comments\">
	<div class=\"news_comments_name\" id=\"comment{$row["ID"]}\">\n";
			if (!empty($row["website"]))
			{
				$name = "<a href=\"{$row["website"]}\">{$row["name"]}</a>";
			}
			else
			{
				$name = $row["name"];
			}
			echo $name."</div>
	<span class=\"news_comments_date\">Date: {$row["date"]}</span>
	<div class=\"news_comments_content\">";
		$comment = $row["comment"];
		$comment = stripslashes($comment);
		$comment = strip_tags($comment, "<a><em><strong><code><blockquote><abbr><acronym>");
		$comment = preg_replace("/(\s)style\=('|\")(.*)('|\")/i", "", $comment);
		$comment = "<p>".$comment."</p>\n";
		$comment = str_replace("\r\n\r\n", "</p><p>", $comment);
		$comment = str_replace("\n", "<br />", $comment);
		$comment = str_replace("</p><p>", "</p>\n\n<p>", $comment);
		$comment = str_replace("<blockquote>", "</p>\n<blockquote>\n", $comment);
		$comment = str_replace("</blockquote>", "</blockquote>\n<p>\n", $comment);
		$comment = str_replace("& ", "&amp; ", $comment);
		$comment = str_replace("/me", "<span style=\"font-style: italic; font-weight: bold;\">{$row["name"]}</span>", $comment);
		$comment = replace_emotes($comment);
		echo "$comment
	</div>\n</div>\n";
		}
		mysql_free_result($comment_query);
	}

	if ($comments == "locked")
	{
		echo '<p class="response">This article is locked, no new comments may be added.</p>';
	}
	elseif ($comments == "password" && empty($_POST['password']))
	{
?>
<p class="instructions">You must enter a password to comment.</p>
<form id="commentcheck" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">

<div class="vsns_form">
	<input type="hidden" name="article_id" value="<?php echo $article_id;?>" />
	<input type="hidden" name="id" value="<?php echo $article_id;?>" />
	<span class="vsns_label">
		<label for="password">Password</label>:
	</span>
	<span class="vsns_field">
		<input type="password" name="password" id="password" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" value="Enter Password" />
</div>
</form>
<?php
	}
	elseif (TRUE == $commentform)
	{
		comment_form("comment_add",$article_id,$_POST['password']);
	}
}
?>