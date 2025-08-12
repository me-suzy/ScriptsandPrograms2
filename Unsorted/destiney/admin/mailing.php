<?php
include("./config.php");
include("$include_path/common.php");

check_login();

ignore_user_abort(true);
set_time_limit(0);

$return_message = "";
$no_message_error = "";
$no_subject_error = "";

if(isset($_POST['launch_mailing'])){

	$error = false;

	if(!strlen($_POST['message'])){
		$error = true;
		$no_message_error = "Please include a message"; 
	}

	if(!strlen($_POST['subject'])){
		$error = true;
		$no_subject_error = "Please include a subject"; 
	}

	$message = $_POST['message'];

	$key = md5('destiney');

$html_unsubscribe = <<<EOF
<br><br>
<a href="$base_url/u.php?k=$key">Click Here To Unsubscribe</a>
<br><br>
EOF;

	$text_unsubscribe = "\r\n\r\nUnsubscribe: " . $base_url . "/u.php?k=$key\r\n\r\n";

	$headers = "From: " . $owner_name . " <" . $owner_email . ">\r\n";

	if(isset($_POST['message_type']) && $_POST['message_type'] == "html"){
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	}

	if(!$error){

		if(isset($_POST['signoff']) && $_POST['signoff'] == "yes"){

			if(isset($_POST['unsubscribe_link']) && $_POST['unsubscribe_link'] == "yes"){
				if($_POST['message_type'] == "html")
					$message .= $html_unsubscribe;
				else
					$message .= $text_unsubscribe;
			}

			if(mail($owner_email, $_POST['subject'], $message, $headers)){
				$return_message = "Test Message Sent.";
			} else {
				$return_message = "Test Message NOT sent, mail() returned FALSE..  check your setup.";
			}
		}
		
		if(isset($_POST['signoff']) && $_POST['signoff'] == "no") {

			$sql = "
				select
					email,
					username
				from
					$tb_users
				where
			";

			if(isset($_POST['user_type']) && $_POST['user_type'] != "all"){
				$sql .= " user_type = '$_POST[user_type]' ";
				$where_used_already = true;
			}

			if(isset($_POST['image_status']) && $_POST['image_status'] != "all"){
				if($where_used_already) $sql .= " and ";
				$sql .= " image_status = '$_POST[image_status]' ";
				$where_used_already = true;
			}

			if(isset($_POST['subscribed']) && $_POST['subscribed'] != "all"){
				if($where_used_already) $sql .= " and ";
				$sql .= " subscribed = '$_POST[subscribed]' ";
				$where_used_already = true;
			}

			if($where_used_already) $sql .= " and ";
			$signed_up_before_date = $_POST['year'] . $_POST['month'] . $_POST['day'] . "000000";
			$sql .= " signup < '$signed_up_before_date' ";

			$query = mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($query)){
		
				$succeeded = 0;

				while($array = mysql_fetch_array($query)){
					$key = md5($array["username"]);

$html_unsubscribe = <<<EOF
<br><br>
<a href="$base_url/u.php?k=$key">Click Here To Unsubscribe</a>
<br><br>
EOF;

					$text_unsubscribe = "\r\n\r\nUnsubscribe: " . $base_url . "/u.php?k=$key\r\n\r\n";
					if(isset($_POST['unsubscribe_link']) && $_POST['unsubscribe_link'] == "yes"){
						if($_POST['message_type'] == "html")
							$message .= $html_unsubscribe;
						else
							$message .= $text_unsubscribe;
					}
					if(mail($array["email"], $_POST['subject'], $message, $headers)){
						$succeeded++;
					}
					$message = $_POST['message'];
				}
				$failed = mysql_num_rows($query) - $succeeded;
				$return_message = $succeeded . " emails were sent..<br>" . $failed . " failed to send..<br>";
			} else {
				$return_message = "No email was sent, your query returned zero rows..<br>";
			}
		}
	} else {
		$return_message = "Errors were encountered.. no email was sent.";
	}
}

include("$include_path/$table_file");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
<script>if(top.location == self.location){top.location.href='$base_url/admin/';}</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$subject = isset($_POST['subject']) ? $_POST['subject'] : "";
$message = isset($_POST['message']) ? $_POST['message'] : "";

$months = get_months(isset($_POST['month']) ? $_POST['month'] : date("m"));
$days = get_days(isset($_POST['day']) ? $_POST['day'] : date("d") - 1);
$years = get_years(isset($_POST['year']) ? $_POST['year'] : date("Y"));

$user_type_options = get_user_types(isset($_POST['user_type']) ? $_POST['user_type'] : "");

$plain_text_checked = isset($_POST['message_type']) && $_POST['message_type'] == "plain_text" ? " checked" : "";
$html_checked = isset($_POST['message_type']) && $_POST['message_type'] == "html" ? " checked" : "";
if(!strlen($plain_text_checked) && !strlen($html_checked)) $plain_text_checked = " checked";

$unsub_checked = isset($_POST['unsubscribe_link']) && $_POST['unsubscribe_link'] == "yes" ? " checked" : "";
$unsub_not_checked = isset($_POST['unsubscribe_link']) && $_POST['unsubscribe_link'] == "no" ? " checked" : "";
if(!strlen($unsub_checked) && !strlen($unsub_not_checked)) $unsub_checked = " checked";

$subscribed_checked = isset($_POST['subscribed']) && $_POST['subscribed'] == "yes" ? " selected" : "";
$not_subscribed_checked = isset($_POST['subscribed']) && $_POST['subscribed'] == "no" ? " selected" : "";
if(!strlen($subscribed_checked) && !strlen($not_subscribed_checked)) $subscribed_checked = " selected";

$image_status_disabled_checked = isset($_POST['image_status']) && $_POST['image_status'] == "disabled" ? " selected" : "";
$image_status_queued_checked = isset($_POST['image_status']) && $_POST['image_status'] == "queued" ? " selected" : "";
$image_status_approved_checked = isset($_POST['image_status']) && $_POST['image_status'] == "approved" ? " selected" : "";
if(!strlen($image_status_disabled_checked) && !strlen($image_status_queued_checked) && !strlen($image_status_approved_checked)) $image_status_disabled_checked = " selected";

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr><form method="post" action="$base_url/admin/mailing.php">
	<td>
	<table cellpadding="10" cellspacing="0" border="0" align="center">
	<tr>
		<td class="regular" colspan="2" align="center"><span class="error">$return_message</span></td>
	</tr>
	<tr>
		<td class="regular" align="right">User Types:</td><td class="regular"><select name="user_type"><option value="all">All</option>$user_type_options</select></td>
	</tr>
	<tr>
		<td class="regular" align="right">Subscribed:</td><td class="regular"><select name="subscribed"><option value="all">All</option><option value="yes"$subscribed_checked>Subscribed</option><option value="no"$not_subscribed_checked>Unsubscribed</option></select></td>
	</tr>
	<tr>
		<td class="regular" align="right">Image Status:</td><td class="regular"><select name="image_status"><option value="all">All</option><option value="disabled"$image_status_disabled_checked>Disabled/Inactive</option><option value="queued"$image_status_queued_checked>Queued</option><option value="approved"$image_status_approved_checked>Approved</option></select></td>
	</tr>
	<tr>
		<td class="regular" align="right">Signed Up Before:</td><td class="regular"><select name="month">$months</select> <select name="day">$days</select> <select name="year">$years</select></td>
	</tr>
	<tr>
		<td class="regular" align="right">From:</td><td class="regular">$owner_name &lt;$owner_email&gt;</td>
	</tr>
	<tr>
		<td class="regular" align="right">Subject:</td><td class="regular"><input type="text" name="subject" size="55" value="$subject"><br><span class="error">$no_subject_error</span></td>
	</tr>
	<tr>
		<td class="regular" align="right">Message:</td><td class="regular"><textarea name="message" rows="10" cols="55">$message</textarea><br><span class="error">$no_message_error</span></td>
	</tr>
	<tr>
		<td class="regular" align="right">Message Type:</td><td class="regular"><input type="radio" name="message_type" value="plain_text"$plain_text_checked> Plain Text<br><input type="radio" name="message_type" value="html"$html_checked> HTML</td>
	</tr>
	<tr>
		<td class="regular" align="right">Unsubscribe Link:</td><td class="regular"><input type="radio" name="unsubscribe_link" value="yes"$unsub_checked> Yes<br><input type="radio" name="unsubscribe_link" value="no"$unsub_not_checked> No</td>
	</tr>
	<tr>
		<td class="regular" align="right">Signoff:</td><td class="regular"><input type="radio" name="signoff" value="yes" checked> Test Mode (send me a test email only)<br><input type="radio" name="signoff" value="no"> Real Mailing (I'm sure, do it!)</td>
	</tr>
	<tr>
		<td class="regular" colspan="2" align="right"><input type="submit" name="launch_mailing" value="Launch Mailing ->"><br><span class="error">(Click once and wait)</span></td>
	</tr>
	</table>
</td>
</form></tr>
</table>
EOF;

$final_output .= small_table("Send Users Email", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>