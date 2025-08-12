<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");

$now = date("YmdHis", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));

$keep_me_logged_in_yes = " checked";
$keep_me_logged_in_no = "";

if(!isset($PW1_html)) $PW1_html = "";
if(!isset($PW2_html)) $PW2_html = "";
if(!isset($realname_html)) $realname_html = "";
if(!isset($age_html)) $age_html = "";
if(!isset($email_html)) $email_html = "";
if(!isset($signup_user_type_html)) $signup_user_type_html = "";

if(!isset($country)) $country = "United_States.gif";
$flags_list = getFlagList($base_path . "/images/flags", $country);

$signup_username = isset($_POST['signup_username']) ? $_POST['signup_username'] : "";
if(!strlen($signup_username)){
	$signup_username = isset($_GET['signup_username']) ? $_GET['signup_username'] : "";
}

$username_html = isset($_POST['username_html']) ? $_POST['username_html'] : "";
$PW1 = isset($_POST['PW1']) ? $_POST['PW1'] : "";
$PW2 = isset($_POST['PW2']) ? $_POST['PW2'] : "";
$signup_hint = isset($_POST['signup_hint']) ? $_POST['signup_hint'] : "";

$signup_realname = isset($_POST['signup_realname']) ? $_POST['signup_realname'] : "";
if(!strlen($signup_realname)){
	$signup_realname = isset($_GET['signup_realname']) ? $_GET['signup_realname'] : "";
}

$signup_description = isset($_POST['signup_description']) ? $_POST['signup_description'] : "";

$signup_age = isset($_POST['signup_age']) ? $_POST['signup_age'] : "";
if(!strlen($signup_age)){
	$signup_age = isset($_GET['signup_age']) ? $_GET['signup_age'] : "";
}

$signup_user_type = isset($_POST['signup_user_type']) ? $_POST['signup_user_type'] : "";

$signup_state = isset($_POST['signup_state']) ? $_POST['signup_state'] : "";
if(!strlen($signup_state)){
	$signup_state = isset($_GET['signup_state']) ? $_GET['signup_state'] : "";
}
$states_list = get_states_list($signup_state);

$signup_email = isset($_POST['signup_email']) ? $_POST['signup_email'] : "";
if(!strlen($signup_email)){
	$signup_email = isset($_GET['signup_email']) ? $_GET['signup_email'] : "";
}

$signup_url = isset($_POST['signup_url']) ? $_POST['signup_url'] : "http://";
$signup_quote = isset($_POST['signup_quote']) ? $_POST['signup_quote'] : "";

$signup_country = isset($_POST['signup_country']) ? $_POST['signup_country'] : "";
if(!strlen($signup_country)){
	$signup_country = isset($_GET['signup_country']) ? $_GET['signup_country'] : "";
}

$user_types = get_user_types($signup_user_type);
$wrapped_return_message = "";

if(isset($_POST['submit_signup'])){

	if($_POST['keep_me_logged_in'] == "Y"){
		$keep_me_logged_in_yes = " checked";
		$keep_me_logged_in_no = "";
		$_SESSION['sl'] = false;
	} else {
		unset($_SESSION['rc']);
		$keep_me_logged_in_yes = "";
		$keep_me_logged_in_no = " checked";
		$_SESSION['sl'] = true;
	}

	if(strlen($signup_realname)<4){
		$signup_error = 1;
		$realname_html = "<br>Your Name must be at least 4 chars";
	}

	if(strlen($signup_realname)>48){
		$signup_error = 1;
		$realname_html = "<br>Your Name must be less than 48 chars";
	}

	if(strlen($signup_username)<4){
		$signup_error = 1;
		$username_html = "<br>Your Username must be at least 4 chars";
	}

	if(strlen($signup_username)>16){
		$signup_error = 1;
		$username_html = "<br>Your Username must be less than 16 chars";
	}

	if(check_username($signup_username)){
		$signup_error = 1;
		$username_html = "<br>The username &quot;" . $signup_username . "&quot; already exists, please choose a different one.";
	}

	if(strlen($PW1) < 4){
		$signup_error = 1;
		$PW1_html = "<br>Your Password must be at least 4 chars";
	}

	if(strlen($PW1) > 16){
		$signup_error = 1;
		$PW1_html = "<br>Your Password must be less than 16 chars";
	}

	if(!strlen($signup_user_type)){
		$signup_error = 1;
		$signup_user_type_html = "<br>You must select a user type";
	}

	if($PW1 != $PW2){
		$signup_error = 1;
		$PW2_html = "<br>Your Passwords must match";
	} else $PW = $_POST['PW1'];

	if($signup_age < 18){
		$signup_error = 1;
		$age_html = "<br>You must be at least 18 years of age to signup";
	}

	if(!eregi("^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+(\.[a-zA-Z0-9_-])+", $signup_email)){
		$signup_error = 1;
		$email_html = "<br>Your Email address must be valid";
	}

	if(eregi("^([a-zA-Z0-9_.-])+@webtv.net+", $signup_email)){
		$signup_error = 1;
		$email_html = "<br>No WebTV users..  sorry";
	}

	if(check_user_email($signup_email)){
		$signup_error = 1;
		$email_html = "<br>That email address already exists, please use a different one if you want to signup again.";
	}

	if(!isset($signup_error)){
		
		$_SESSION['username'] = $signup_username;
		$signup_description = addslashes($signup_description);

		$su_sql = "
			insert into $tb_users (
				id,
				username,
				password,
				hint,
				realname,
				description,
				state,
				country,
				url,
				quote,
				age,
				user_type,
				email,
				total_ratings,
				total_points,
				average_rating,
				signup,
				image_url,
				md5key
			) values (
				'',
				'$signup_username',
				password('$PW1'),
				'$signup_hint',
				'$signup_realname',
				'$signup_description',
				'$signup_state',
				'$signup_country',
				'$signup_url',
				'$signup_quote',
				'$signup_age',
				'$signup_user_type',
				'$signup_email',
				'1',
				'10',
				'10',
				'$now',
				'',
				md5('$signup_username')
			)
		";

		$su_query = mysql_query($su_sql) or die(mysql_error());

		$mid = mysql_insert_id();

		$_SESSION['userid'] = $mid;

		if($_POST['keep_me_logged_in'] == "Y"){
			$md5 = md5(time());
			$sql = "
				insert into $tb_cookies (
					userid,
					cookie
				) values (
					'$mid',
					'$md5'
				)
			";
			$query = mysql_query($sql) or die(mysql_error());
			setcookie("keep_me_logged_in", $md5, time() + 31536000);
		}

		$rating_ins = "
			insert into $tb_ratings (
				id,
				user_id,
				rating,
				rater_id,
				rater_ip
			) values (
				'',
				'$_SESSION[userid]',
				'10',
				'0',
				'127.0.0.1'
			)
		";

		$rating_ins_query = mysql_query($rating_ins) or die(mysql_error());
		
		$recipient = $signup_realname . " <" . $signup_email . ">";
		
		$subject = "Thanks for signing up...";
		
		$signup_realname_array = explode(" " , $signup_realname);
		$srn = $signup_realname_array[0];

		$email_content = "Dear " . $srn . ",\r\n\r\n";
		$email_content .= "Thanks for signing up!  Here is what you submitted:\r\n\r\n";
		$email_content .= "Username: " . $signup_username . "\r\n";
		$email_content .= "Password: " . $PW . "\r\n\r\n";
		$email_content .= "You will need your Username and Password to login to the site, so be sure and save this email somewhere safe.\r\n\r\n";
		$email_content .= "We take security and privacy very seriously.  Your password is encrypted using one-way encryption, therfore we can not send it to you on request.  If you forget your password, we can only provide you with your chosen password hint.\r\n\r\n";
		$email_content .= "Your password hint is: " . $signup_hint . "\r\n\r\n";
		$email_content .= "You may now proceed to " . $base_url . " and update your picture and other details in your profile.\r\n\r\n";
		$email_content .= "Thanks,\r\n";
		$email_content .= $owner_name . "\r\n";
		$email_content .= $owner_email . "\r\n";

		$headers = "From: " . $owner_name . "<" . $owner_email . ">\n";
		$headers .= "X-Sender: <" . $owner_email . ">\n";
		$headers .= "Return-Path: <" . $owner_email . ">\n";
		$headers .= "Error-To: <" . $owner_email . ">\n";
		$headers .= "X-Mailer: " . $_SERVER['SERVER_NAME'] . "\n";

		mail($recipient, $subject, $email_content, $headers);

$message = <<<EOF
<br><br><br>Signup complete, <a href="$base_url/upload.php" class="">please click</a> here to upload your picture.<br><br><br>
EOF;

$form_return_message = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Signup</td>
</tr>
</table>

<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
<td align="center" class="regular"><br>$message<br><br></td>
</tr>
</table>

EOF;
	
	$wrapped_return_message = table("Signup", $form_return_message);

	}
}

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
<tr>
<td align="left" valign="top">$wrapped_return_message
FO;

if(!isset($_POST['submit_signup']) || isset($signup_error)){

$form_content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Signup</td>
</tr>
</table>

<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td class="regular" colspan="2" align="center"><br>$site_title is an amateur image rating site.  If you sign up you<br>are fully expected to upload a <a href="$base_url/image_rules.php">picture of yourself</a> on the next screen.  If<br>you do not intend on uploading a picture, please do not signup, thanks.<br><br>
EOF;

if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == "destiney"){
$form_content .= <<<EOF
By signing up you are automatically entered into our <a href="$base_url/sweep.php">$250 Cash Sweepstakes</a> drawing.<br><br>
EOF;
}

$age_options = get_age_options($low_age_limit);
$user_types_count = get_user_types_count();

$form_content .= <<<EOF
</td>
</tr>
<tr><form method="post" action="$base_url/signup.php" name="the_form">
	<td class="regular" align="right"><span class="bold">Username:</span><br>4-16 characters required, you will use this<br>with your password to login to the site.</td>
	<td valign="top"><input class="input" type="text" name="signup_username" value="$signup_username" size="16"><span class="error">$username_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Keep me logged in:</span><br>(store a login cookie)</td>
	<td valign="top" class="regular"><input type="radio" name="keep_me_logged_in" value="Y"$keep_me_logged_in_yes> Yes <input type="radio" name="keep_me_logged_in" value="N"$keep_me_logged_in_no> No</td>
</tr>
<tr>
	<td align="right" class="regular" width="50%"><span class="bold">Email Address:</span><br>valid email required</td>
	<td class="regular" width="50%"><input class="input" type="text" name="signup_email" value="$signup_email" size="24"><span class="error">$email_html</span></td>
</tr>
<tr>
	<td class="regular" align="right"><span class="bold">Password:</span><br>4-16 characters required</td>
	<td valign="top"><input class="input" type="password" name="PW1" value="$PW1" size="16"><span class="error">$PW1_html</span></td>
</tr>
<tr>
	<td class="regular" align="right"><span class="bold">Password Again:</span><br>4-16 characters required</td>
	<td valign="top"><input class="input" type="password" name="PW2" value="$PW2" size="16"><span class="error">$PW2_html</span></td>
</tr>
<tr>
	<td class="regular" align="right"><span class="bold">Password Hint:</span><br>in case you forget your password</td>
	<td valign="top"><input class="input" type="text" name="signup_hint" value="$signup_hint" size="16"></td>
</tr>
<tr>
	<td class="regular" align="right"><span class="bold">Name:</span><br>4-48 characters required.</td>
	<td valign="top"><input class="input" type="text" name="signup_realname" value="$signup_realname" size="16"><span class="error">$realname_html</span></td>
</tr>
<tr>
	<td class="regular" align="right"><span class="bold">Age:</span><br>must be at least 18 years old</td>
	<td valign="top"><select class="input" name="signup_age">$age_options</select><span class="error">$age_html</span></td>
</tr>
<tr>
	<td class="bold" align="right">User Type:</td>
	<td valign="top"><select class="input" name="signup_user_type" size="$user_types_count">$user_types</select><span class="error">$signup_user_type_html</span></td>
</tr>
<tr>
	<td class="bold" align="right">Description:<br></td>
	<td valign="top"><textarea class="input" name="signup_description" rows="6" cols="40">$signup_description</textarea>&nbsp;&nbsp;</td>
</tr>
<tr>
	<td class="bold" align="right">State or Province:</td>
	<td valign="top"><select class="input" name="signup_state"><option value="">Please Select:</option><option value="">None/Other</option>$states_list</select></td>
</tr>
<tr>
	<td class="bold" align="right">Country:</td>
	<td class="regular"><select class="input" name="signup_country"><option value="None.gif">Please Select:</option>$flags_list</select></td>
</tr>
<tr>
	<td class="bold" align="right">URL:</td>
	<td valign="top"><input class="input" type="text" name="signup_url" value="$signup_url" size="24"></td>
</tr>
<tr>
	<td class="bold" align="right">Favorite Quote:</td>
	<td valign="top"><input class="input" type="text" name="signup_quote" value="$signup_quote" size="24"></td>
</tr>
<tr>
	<td class="regular" align="center" colspan="2"><input class="button" type="submit" name="submit_signup" value=" Sign me up -> "></td>
</tr></form>
</table>
<br>
EOF;

$final_output .= table("Signup Now!", $form_content);

}

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