<?

/*
 * $Id: lost.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$title = "Lost Password";

if(isset($search)){

	$lost_sql = "select * from $tb_users where ";
	
	if(isset($lost_username))
		$lost_sql .= "username = '$search_username'";

	if(isset($lost_email))
		$lost_sql .= "email = '$search_email'";

	$lost_query = sql_query($lost_sql);

	if(sql_num_rows($lost_query) == 1){

		$lost_array = sql_fetch_array($lost_query);

		$message = "<br /><br />We found your account and have emailed your Password Hint.  Please check your email.<br /><br />";

		$recipient = $lost_array["realname"] . " <" . $lost_array["email"] . ">";

		$subject = "Your requested info:";

		$lost_realname_array = explode(" " , $lost_array["realname"]);

		$lrn = $lost_realname_array[0];

		$content = "Dear " . $lrn . ",\r\n\r\n";
		$content .= "Here is you lost account information as requested:\r\n\r\n";
		$content .= "Username: " . $lost_array["username"] . "\r\n";
		$content .= "Password Hint: " . $lost_array["hint"] . "\r\n\r\n";
		$content .= "Please proceed to " . $base_url . " and login.\r\n\r\n";
		$content .= "Thanks,\r\n";
		$content .= $owner_name . "\r\n";
		$content .= $owner_email . "\r\n";

		$headers = "From: " . $owner_name . "<" . $owner_email . ">\n";
		$headers .= "X-Sender: <" . $owner_email . ">\n";
		$headers .= "Return-Path: <" . $owner_email . ">\n";
		$headers .= "Error-To: <" . $owner_email . ">\n";
		$headers .= "X-Mailer: " . $SERVER_NAME . "\n";

		mail($recipient, $subject, $content, $headers);

	} else $message = "<br /><br />We were unable to find your account.  Please <a href=\"" . $base_url . "/index.php?" . $sn . "=" . $sid . "&amp;show=signup\" target=\"_top\">signup now</a>.<br /><br />";

$content = <<<EOF
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td width="100%" class="regular" align="center"><br />$message<br /><br /></td>
</tr>
</table>
EOF;

} else {

$content = "<br /><br />";

$lost_pass_form = template("lost_pass_form");
eval("\$lost_pass_form = \"$lost_pass_form\";");

$content .= $lost_pass_form . "<br /><br /><br /><br /><br />";
}

$final_output .= table($title, $content);

/*
 * $Id: lost.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>