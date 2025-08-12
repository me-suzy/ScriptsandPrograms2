<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: support_functions.php
*	Description: Ask for support and help
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

//Send mail to me (after some validation)
function send_support()
{
	global $version, $path, $bkemail;

	$category = str_replace("_", " ", $_POST["category"]);
	$priority = $_POST["priority"];
	$message = $_POST["message"];

	$message = strip_tags($_POST["message"], "<em><pre><strong>");
	$message = stripslashes($message);
	$message = preg_replace("/(\s)style\=('|\")(.*)('|\")/i", "", $message);
	$message = "<p>".$message."</p>";
	$message = str_replace("\r\n\r\n", "</p><p>", $message);
	$message = str_replace("\n", "<br />", $message);

	$ip = $_SERVER["REMOTE_ADDR"];

	if ($category == "Feature Request" || $category == "Feedback")
	{
		$priority = "Low";
	}

	switch ($priority)
	{
		case "High":
			$coloured_priority = "<span style=\"color: #CD0000;\">High</span>";
			break;

		case "Normal":
			$coloured_priority = "<span style=\"color: #FFE600;\">Normal</span>";
			break;

		case "Low":
			$coloured_priority = "<span style=\"color: #008080;\">Low</span>";
			break;
	}

	$mailto = "support@tachyondecay.net";
	$mailfrom = "Vanilla Guestbook Support Form <" . $email . ">";
	$subject = "[". $priority . "] Vanilla Guestbook Support ($category)";
	$headers = "From: $mailto\r\nContent-type: text/html\r\n";

$message =  <<<LOTR
<p><strong>Category:</strong> $category<br />
<strong>Priority:</strong> $coloured_priority</p>

<p><strong>Vanilla Version:</strong> $version<br />
<strong>Website:</strong> <a href="$path">$path</a><br />
<strong>IP Address:</strong> $ip</p>

$message
LOTR;
	mail ($mailto, $subject, $message, $headers);
	echo "<p class=\"response\">Thank you for submitting this support form.  I will endeavor to contact you as soon as possible with a reply.</p>";
}

//Present with support form
function support_form()
{
?>
<p class="instructions">You can use this form to <a href="mailto:support@tachyondecay.net">email me</a> with a support issue.  If you prefer, you are welcome to just email me directly.  Either way, I make no guarantees that you will receive quick support, but I will attempt to provide it as much as possible.  This method does send the email to a separate folder, however, so I'm more likely to notice it.  Abusing this form will not be tolerated.</p>
<p>Please be as specific as possible.  If you are encountering an error, post the error message you receive.  If the error occurred after you edited any files, describe what you were doing at the time.</p>
<p>Note that I may ignore support requests filed under the wrong category.  Also, Feature Requests and Feedback entries are automatically marked as low priority regardless of their setting.</p>
<p>Additional information such as <abbr title="Internet Protocol">IP</abbr> address, Vanilla Guestbook version, and a link to your website will be included in the email to help me solve your problem.</p>
<p>Allowed HTML tags: <em>&lt;strong&gt;, &lt;em&gt;, &lt;pre&gt;</em></p>

<form id="support" action="<?php echo $_SERVER["PHP.SELF"];?>" method="post">

<div class="bk_form">
	<input type="hidden" name="act" value="send_support" />
	<span class="bk_label">
		<label for="category">Support Category</label>:
	</span>
	<span class="bk_field">
		<select id="category" name="category">
			<option value="General" selected="selected">General</option>
			<option value="Bug">Bug</option>
			<option value="Code_Related">Code-Related</option>
			<option value="Feature_Request">Feature Request</option>
			<option value="Feedback">Feedback</option>
			<option value="Other">Other</option>
			<option value="Style_Related">Style-Related</option>
		</select>
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="priority">Priority</label>:
	</span>
	<span class="bk_field">
		<select id="priority" name="priority">
			<option value="High">High Priority (Urgent)</option>
			<option value="Normal" selected="selected">Normal Priority</option>
			<option value="Low">Low Priority</option>
		</select>
	</span>
</div>

<div class="bk_form bk_commentfield">
	<span class="bk_label">
		<label for="message">Message</label>:
	</span>
	<span class="bk_field">
		<textarea name="message" id="message" rows="10" cols="53" onfocus="this.select(); return true"><?php echo $comment;?></textarea>
	</span>
</div>

<div class="bk_form bk_buttons">
	<input type="submit" value="Ask for Support" />
</div>

</form>
<?php
}
?>
