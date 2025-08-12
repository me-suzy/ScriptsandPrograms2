<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: entry_functions.php
*	Description: Functions relating to the display and modification of
*	guestbook entries
****************************************************************************
*	Build Date: August 20, 2005
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

//Edit, delete, or add an entry to the database
function edit($act)
{
	global $queue, $notification, $bkname, $bkemail, $path;

	extract($_POST, EXTR_SKIP);
	if ($act == "add")
	{
		$ip = $_SERVER["REMOTE_ADDR"];

		//Check if their IP is banned
		$ipquery = mysql_query("SELECT * FROM vanilla_config WHERE config_name = 'banned_ip'");
		if ($ipquery && mysql_num_rows($ipquery) > 0)
		{
			$ipdata = mysql_fetch_array($ipquery);
			$ipdata = $ipdata["config_value"];
			$ipdata = explode("\n", $ipdata);
			mysql_free_result($ipquery);
		}

		if (in_array($ip, $ipdata))
		{
			echo "<p class=\"response\">Your have been banned from signing this guestbook.</p>";
			view();
		}

		//Validation!
		elseif (empty($name) || empty ($email) || empty($comment))
		{
			echo "<p class=\"response\">The name, email, and comment fields cannot be left blank.</p>";

			form("add_entry", 1, $name, $email, $website, $msn, $yahoo, $aim, $icq, $gtalk, $comment, $score, $avatar);
		}

		elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
		{
			echo "<p class=\"response\">You did not enter a valid email address.</p>";
			form("add_entry", 1, $name, $email, $website, $msn, $yahoo, $aim, $icq, $gtalk, $comment, $score, $avatar);
		}

		elseif (!empty($icq) && !is_numeric($icq))
		{
			echo "<p class=\"response\">Your ICQ field must be numeric.</p>";
			form("add_entry", 1, $name, $email, $website, $msn, $yahoo, $aim, $icq, $gtalk, $comment, $score, $avatar);
		}

		elseif(md5($visual) != $answer)
		{
			echo "<p class=\"response\">Sorry, an error occurred.  You failed to verify you were a person (and not a bot).</p>";
			form("add_entry", 1, $name, $email, $website, $msn, $yahoo, $aim, $icq, $gtalk, $comment, $score, $avatar);
		}

		elseif(strlen($comment) < 5)
		{
			echo "<p class=\"response\">Comments must be at least five characters long.</p>";
			form("add_entry", 1, $name, $email, $website, $msn, $yahoo, $aim, $icq, $gtalk, $comment, $score, $avatar);
		}

		else
		{
			$ip = $_SERVER["REMOTE_ADDR"];
			$date = date("Y-m-d H:i:s");

			mysql_query("INSERT INTO vanilla_entry VALUES (NULL, '$queue', '$ip', '$date', '$name', '$email', '$website', '$msn', '$yahoo', '$aim', '$icq', '$gtalk', '$comment', '$score', '$avatar')") or die(mysql_error());
			$id = mysql_insert_id();

			if ($queue == 1)
			{
				echo "<p class=\"response\">Thank you for signing the guestbook.  Your entry will have to be previewed and approved before it is added.  Have a nice day!</p>";
				view();
			}

			else
			{
				echo "<p class=\"response\">Thank you for signing the guestbook.  <a href=\"index.php?act=view#entry{$id}\">View your entry</a>.</p>";
				view();
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
				$subject = $bkname." has a new comment";
				$headers = "From: $bkname <$bkemail>\r\nContent-type: text/html\r\n";

$message = <<<SUKALO
Your guestbook has a new comment:
<a href="{$path}index.php?act=view#entry{$id}">{$path}index.php?act=view#entry{$id}</a>

<p><strong>From:</strong> $name<br />
<strong>Email:</strong> $email<br />
<strong>Comment:</strong></p>

$comment
SUKALO;
				mail($bkemail, $subject, $message, $headers);
			}
		}
	}

	if ($act == "edit")
	{
		if ($mode == "Delete")
		{
			$size = sizeof($id) - 1;
			$i = 0;
			while ($i <= $size)
			{
				mysql_query("DELETE FROM vanilla_entry WHERE ID = '{$id[$i]}'") or die(mysql_error());
				$i++;
			}
			echo "<p class=\"response\">Entry deleted.</p>";
			view("admin");
		}

		else
		{
		mysql_query("UPDATE vanilla_entry SET name = '$name', email = '$email', website = '$website', msn = '$msn', yahoo = '$yahoo', aim = '$aim', icq = '$icq', gtalk = '$gtalk', comment = '$comment', score = '$score', avatar = '$avatar' WHERE ID = '$id'") or die(mysql_error());

		echo "<p class=\"response\">Entry edited.  <a href=\"index.php?act=view#entry{$id}\">View</a>.</p>";
		view("admin");
		}
	}
}

//Display a form to edit/add entries
function form($act, $vars = 0, $name = "", $email = "", $website = "", $msn = "", $yahoo = "", $aim = "", $icq = "", $gtalk = "", $comment = "", $score = "", $avatar = "")
{
	global $serverpath, $allow;
	$id = $_REQUEST["id"];

	//If you're editing an entry
	if ($act == "edit_form")
	{
		//Get the data
		$query = mysql_query("SELECT * FROM vanilla_entry WHERE ID = '$id'") or die(mysql_error());
		$data = mysql_fetch_array($query);

		$new_act = "edit";
		$sub_value = "Edit Entry";
		$sub_name = "submit";

		extract($data,EXTR_SKIP);
	}

	elseif ($act == "add_entry")
	{
		$new_act = "add";
		$sub_value = "Sign the Guestbook";
		$sub_name = "submit";
	}

	elseif ($act == "queue_form")
	{
		//Get the data
		$query = mysql_query("SELECT * FROM vanilla_entry WHERE ID = '$id'") or die(mysql_error());
		$data = mysql_fetch_array($query);

		$new_act = "update_queue";
		$sub_value = "Approve Entry";
		$sub_name = "mode";

		//Set variables
		extract($data,EXTR_SKIP);
		$avatar = $path."avatars/".$avatar;
	}
?>
<form id="editform" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<?php
	if ($act == "edit_form" || $act == "queue_form")
	{
?>
<div class="bk_form">
	<span class="bk_label">
		<strong><abbr title="Internet Protocol">IP</abbr> Address</strong>
	</span>
	<span class="bk_field">
		<?php echo $ip; ?> <a href="admin.php?act=ban_ip&amp;ip=<?php echo $ip;?>">Ban this IP</a>
	</span>
</div>

<?php
	}

	if ($act == "add_entry")
	{
		$wordnums = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty");

		$num1 = rand(0,20);
		$num2 = rand(0,20);

		$answer = $num1 + $num2;
		$answer = md5($answer);

		$question = $wordnums[$num1]." plus ".$wordnums[$num2];
?>
<p class="instructions" style="font-weight: normal; font-size: small;">* marks a required field.</p>
<div class="bk_form">
	<p style="text-align: center;">To verify you are a real person, please answer this simple question: What is <strong><?php echo $question; ?></strong>?*</p>
	<span class="bk_label">
		<label for="visual">Answer in the form of a number, eg, 42</label>
	</span>
	<span class="bk_field">
		<input type="text" name="visual" id="visual" tabindex="1" />
		<input type="hidden" name="answer" value="<?php echo $answer; ?>" />
	</span>
</div>
<?php
	}
?>

<div class="bk_form">
	<span class="bk_label">
		<label for="score">Score</label>:
	</span>
	<span class="bk_field">
		<select name="score" id="score">
<?php
	switch ($score)
	{
		default:
		case 1:
?>
			<option value="1" selected="selected">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
<?php
			break;

		case 2:
?>
			<option value="1">1</option>
			<option value="2" selected="selected">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
<?php
			break;

		case 3:
?>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected="selected">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
<?php
			break;

		case 4:
?>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4" selected="selected">4</option>
			<option value="5">5</option>
<?php
			break;

		case 5:
?>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5" selected="selected">5</option>
<?php
			break;
	}
?>
		</select>
	</span>
</div>

<div class="bk_form">
	<input type="hidden" name="act" value="<?php echo $new_act;?>" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<span class="bk_label">
		<label for="name">Name</label>:*
	</span>
	<span class="bk_field">
		<input type="text" name="name" id="name" value="<?php echo $name;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="email">Email</label>:*
	</span>
	<span class="bk_field">
		<input type="text" name="email" id="email" value="<?php echo $email;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="website">Website</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="website" id="website" value="<?php echo $website;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="msn"><abbr title="Microsoft Network">MSN</abbr> Messenger</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="msn" id="msn" value="<?php echo $msn;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="yahoo">Yahoo! Instant Messenger</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="yahoo" id="yahoo" value="<?php echo $yahoo;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="aim"><abbr title="America On-Line">AOL</abbr> Instant Messenger</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="aim" id="aim" value="<?php echo $aim;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="icq">ICQ</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="icq" id="icq" value="<?php echo $icq;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="gtalk">Google Talk</label>:
	</span>
	<span class="bk_field">
		<input type="text" name="gtalk" id="gtalk" value="<?php echo $gtalk;?>" />
	</span>
</div>

<div class="bk_form bk_commentfield">
	<span class="bk_label">
		<label for="comment">Comment</label>:*
	</span>
	<span class="bk_field">
		<textarea name="comment" id="comment" rows="10" cols="53" onfocus="this.select(); return true"><?php echo $comment;?></textarea>
	</span>

<?php
	if ($act = "add_entry")
	{
?>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>emotes.php', 'emoticons', config='height=300,width=500,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="bk_assist">Emoticon Guide</a>
	<a href="javascript: ;" onclick="window.open('<?php echo $path;?>xhtml.php', 'xhtml', config='height=250,width=700,toolbar=0,menubar=0,scrollbars=1,resizable=1,location=0,directories=0,status=0'); return true" class="bk_assist">Allowed <abbr title="eXtensible HyperText Markup Language">(X)HTML</abbr></a>
<?php
	}
?>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="avatar">Avatar</label>:
	</span>
	<span class="bk_field">
		<select name="avatar" id="avatar" onchange="avatardisplay(this)">
<?php
	$dir = $serverpath."images/avatars";
	$handle = opendir($dir);

	if ($handle)
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file == "." || $file == "..")
			{
				continue;
			}

			if ($file == $avatar)
			{
				echo "<option value=\"$avatar\" selected=\"selected\">$avatar</option>\n";
			}
			else
			{
				echo "<option value=\"$file\">$file</option>\n";
			}
		}
		closedir($handle);
	}
?>
		</select>

		<img src="images/avatars/<?php echo $avatar;?>" id="avatarimg" alt="User Avatar" />
	</span>
</div>

<div class="bk_form bk_buttons">
	<input type="submit" value="<?php echo $sub_value;?>" name="<?php echo $sub_name;?>" />
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
		$query = mysql_query ("SELECT * FROM vanilla_entry WHERE queue = '1'");
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
					mysql_query("DELETE FROM vanilla_entry WHERE ID = '{$ID[$i]}'") or die(mysql_error());
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
					mysql_query("UPDATE vanilla_entry SET queue = '0' WHERE ID = '{$ID[$i]}'") or die(mysql_error());
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

		elseif ($mode == "Approve Entry")
		{
			extract($_POST, EXTR_SKIP);

			mysql_query("UPDATE vanilla_entry SET queue = '0', name = '$name', email = '$email', website = '$website', msn = '$msn', yahoo = '$yahoo', aim = '$aim', icq = '$icq', gtalk = '$gtalk', comment = '$comment', score = '$score', avatar = '$avatar' WHERE ID = '$id'") or die(mysql_error());

			echo "<p class=\"response\">Entry approved.</p>";
			manage_queue("queue");
		}
	}
}

//Build links to other pages
function page_links($page, $total_pages)
{
	echo "<div class=\"bk_pages\">\nPages: \n<ul>\n";

	if ($page > 1)
	{
		$prev = ($page - 1);
		echo "<li><a href=\"".$_SERVER["PHP_SELF"]."?act=view&amp;page=$prev\">&laquo;Previous</a></li>\n";
	}

	for ($i = 1; $i <= $total_pages; $i++)
	{
		if ($page == $i)
		{
			echo "<li>$i</li>\n";
		}

		else
		{
			echo "<li><a href=\"".$_SERVER["PHP_SELF"]."?act=view&amp;page=$i\">$i</a></li>\n";
		}
	}

	//This would be the "Next" link
	if ($page < $total_pages)
	{
		$next = ($page + 1);
		echo "<li><a href=\"".$_SERVER["PHP_SELF"]."?act=view&amp;page=$next\">Next&raquo;</a></li>\n";
	}
	echo "</ul>\n</div>\n";
}

//View guestbook entries
function view($mode = "default")
{
	global $hlevel, $path, $limit, $allow, $disp_order;

	//The page numbering thing is mostly
	//from a PHP Freaks tutorial, eh

	//Check if a page is set
	if (!isset($_GET["page"]) || empty($_GET["page"]))
	{
		$page = 1;
	}

	else
	{
		$page = $_GET["page"];
	}

	//Figure out the limit
	$from = (($page * $limit) - $limit);

	//Query only that page's results
	$query = mysql_query("SELECT * FROM vanilla_entry WHERE queue = '0' ORDER BY ID $disp_order LIMIT $from, $limit");

	// Figure out the total number of results in DB:
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM vanilla_entry WHERE queue = '0'"),0);

	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results / $limit);

	$request = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$request2 = "http://www.".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; //If www was used
	$adminpath = "admin.php?act=view";

	if ($allow != 0 && $mode == "default")
	{
		echo '<div class="bk_pages"><a href="index.php?act=sign">Sign the Guestbook</a></div>';
	}

	if ($query && mysql_num_rows($query) > 0)
	{
		page_links($page, $total_pages);

		if ($mode == "admin")
		{
?>
<p class="instructions">Select an entry below to edit or delete.</p>

<form id="entries" act="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<p style="text-align: center;">
	<input type="hidden" name="act" value="edit" />
	<a href="javascript: SetAllCheckBoxes('entries', 'id[]', true)">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<a href="javascript: SetAllCheckBoxes('entries', 'id[]', false)">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<strong>With Selected:</strong>
	<input type="submit" class="button" value="Delete" id="mode" name="mode" />
</p>
<ul>
<?php
			while ($row = mysql_fetch_array($query))
			{
				$abbr = substr($row["comment"], 0, 80);
				echo "<li><input type=\"checkbox\" name=\"id[]\" value=\"{$row["ID"]}\" /><a href=\"admin.php?act=edit_form&amp;id={$row["ID"]}\" title=\"$abbr\" />{$row["name"]} at {$row["date"]}</a></li>\n";
			}
?>
</ul>
<p style="text-align: center;">
	<a href="javascript: SetAllCheckBoxes('entries', 'id[]', true)">Select All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<a href="javascript: SetAllCheckBoxes('entries', 'id[]', false)">Unselect All</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<strong>With Selected:</strong>
	<input type="submit" class="button" value="Delete" id="mode" name="mode" />
</p>
</form>
<?php
		}
		else
		{
	while ($row = mysql_fetch_array($query))
	{
		echo "<div class=\"bk_entry\" id=\"entry{$row["ID"]}\">
		<h{$hlevel} class=\"bk_name\">";

		if (!empty($row["website"]))
		{
			echo "<a href=\"{$row["website"]}\">{$row["name"]}</a>";
		}
		else
		{
			echo "{$row["name"]}";
		}
		echo " at {$row["date"]}</h{$hlevel}>
		<div class=\"bk_avatar\">
			<img src=\"{$path}images/avatars/{$row["avatar"]}\" class=\"bk_avatar\" alt=\"User Avatar\" />";

		for ($i = 1; $i <= $row["score"]; $i++)
		{
			echo "<img src=\"{$path}images/score/star_on.png\" class=\"bk_score\" alt=\"*\" />";
		}
		$remain = 5 - ($row["score"]);

		for ($i = 0; $i <= $remain; $i++)
		{
			if ($i == 0)
			{
				continue;
			}
			else
			{
				echo "<img src=\"{$path}images/score/star_off.png\" class=\"bk_score\" alt=\"-\" />";
			}
		}
		echo "</div>
		<div class=\"bk_comment\">\n";

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
		echo $comment."\n
		<div class=\"bk_profile\">


		<form id=\"form{$row["ID"]}\" method=\"get\" action=\"index.php\">
			<div class=\"bk_info\">
			<input type=\"text\" value=\"\" readonly=\"readonly\" name=\"info{$row["ID"]}\" id=\"info{$row["ID"]}\" class=\"bk_info\" onfocus=\"this.select();\" />
			</div>
		</form>

		<div class=\"bk_info_msg\">\n";

		//Now do the profile info
		if ($row["msn"] != NULL)
		{
			echo "<a href=\"javascript:profile({$row["ID"]},'{$row["msn"]}')\"><img src=\"{$path}images/profile/msn.png\" class=\"bk_profile msn\" alt=\"MSN Messenger\" title=\"{$row["msn"]}\" /></a>\n";
		}

		if ($row["yahoo"] != NULL)
		{
			echo "<a href=\"javascript:profile({$row["ID"]},'{$row["yahoo"]}')\"><img src=\"{$path}images/profile/yahoo.png\" class=\"bk_profile yahoo\" alt=\"Yahoo Instant Messenger\" title=\"{$row["yahoo"]}\" /></a>\n";
		}

		if ($row["aim"] != NULL)
		{
			echo "<a href=\"javascript:profile({$row["ID"]},'{$row["aim"]}')\"><img src=\"{$path}images/profile/aim.png\" class=\"bk_profile aim\" alt=\"AOL Instant Messenger\" title=\"{$row["aim"]}\" /></a>\n";
		}

		if ($row["icq"] != NULL)
		{
			echo "<a href=\"javascript:profile({$row["ID"]},'{$row["icq"]}')\"><img src=\"{$path}images/profile/icq.png\" class=\"bk_profile icq\" alt=\"ICQ\" title=\"{$row["icq"]}\" /></a>\n";
		}

		if ($row["gtalk"] != NULL)
		{
			echo "<a href=\"javascript:profile({$row["ID"]},'{$row["gtalk"]}')\"><img src=\"{$path}images/profile/gtalk.png\" class=\"bk_profile gtalk\" alt=\"Google Talk\" title=\"{$row["gtalk"]}\" /></a>\n";
		}

		echo "</div>\n</div>\n</div>\n</div>\n";
	}
	}
		page_links($page, $total_pages);
	}

	else
	{
		echo "<p class=\"response\">Sorry, no entries were found.";
	}
}
?>