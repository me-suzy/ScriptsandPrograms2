<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

$final_output .= <<<FO
</head>
<body bgcolor="$page_bg_color">
<table border="0" cellpadding="0" cellspacing="0" width="$total_width" align="center">
<tr>
	<td colspan="3" width="100%" valign="bottom">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="middle" class="dc">$title_image</td>
		<td align="right" valign="bottom">
FO;

include("$include_path/logged_status.php");

$final_output .= <<<FO
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
<td width="$left_col_width" valign="top">
FO;

include("$include_path/left.php");

$final_output .= <<<FO
</td>
<td width="$main_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Lost Password</td>
</tr>
</table>
EOF;

$title = "Lost Password";

if(isset($_POST['lost_username']) || isset($_POST['lost_email'])){

	$lost_sql = "select * from $tb_users where ";
	
	if(isset($_POST['lost_username']))
		$lost_sql .= "username = '$_POST[search_username]'";

	if(isset($_POST['lost_email']))
		$lost_sql .= "email = '$_POST[search_email]'";

	$lost_query = mysql_query($lost_sql) or die(mysql_error());

	if(mysql_num_rows($lost_query)){

		$lost_array = mysql_fetch_array($lost_query);

		$message = "<br><br>We found your account and have emailed your Password Hint.  Please check your email.<br><br>";

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

	} else {
$message = <<<EOF
<br><br>We were unable to find your account.  Please <a href="$base_url/signup.php" target="_top">signup now</a>.<br><br>
EOF;
	}

$content .= <<<EOF
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td width="100%" class="regular" align="center"><br>$message<br><br></td>
</tr>
</table>
EOF;

} else {

$content .= <<<EOF
<br><br><table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td width="100%" class="bold" align="center"><br>Enter your username or email and your password<br>hint will be sent to your registered email address.<br><br></td>
</tr>
<tr>
	<td width="100%" class="regular" align="center">
	<table cellpadding="5" cellspacing="0" border="0">
	<tr><form method="post" action="$base_url/lost.php?search=1">
		<td class="bold" align="right">username:</td>
		<td class="regular"><input class="input" type="text" name="search_username" size="24" value=""></td>
		<td class="regular"><input class="button" type="submit" name="lost_username" value="Search ->"></td>
	</form></tr>
	<tr><form method="post" action="$base_url/lost.php?search=1">
		<td class="bold" align="right">email:</td>
		<td class="regular"><input class="input" type="lost_email" name="search_email" size="24" value=""></td>
		<td class="regular"><input class="button" type="submit" name="lost_email" value="Search ->"></td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table><br><br><br><br><br>
EOF;

}

$final_output .= table($title, $content);

$final_output .= <<<FO
</td>
</tr>
</table>
FO;

include("$include_path/copyright.php");

$final_output .= <<<FO
</td>
<td width="$right_col_width" valign="top">
FO;

include("$include_path/right.php");

$final_output .= <<<FO
</td>
</tr>
</table>
</body>
</html>
FO;

$final_output = final_output($final_output);

echo $final_output;

?>