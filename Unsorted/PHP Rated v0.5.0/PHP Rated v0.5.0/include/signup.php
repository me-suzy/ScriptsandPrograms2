<?

/*
 * $Id: signup.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$title = "Signup Now!";

if(isset($submit_signup)){

	if(strlen($signup_realname)<4){
		$signup_error = 1;
		$realname_html = "<br />Your Name must be at least 4 chars";
	}
	if(strlen($signup_realname)>48){
		$signup_error = 1;
		$realname_html = "<br />Your Name must be less than 48 chars";
	}
	if(strlen($signup_username)<4){
		$signup_error = 1;
		$username_html = "<br />Your Username must be at least 4 chars";
	}
	if(strlen($signup_username)>16){
		$signup_error = 1;
		$username_html = "<br />Your Username must be less than 16 chars";
	}

	$cu_sql = "
		select
			*
		from
			$tb_users
		where
			username = '$signup_username'
	";

	$cu_query = sql_query($cu_sql);

	if(sql_num_rows($cu_query)>0){
		$signup_error = 1;
		$username_html = "<br />The username &quot;" . $signup_username . "&quot; already exists, please choose a different one.";
	}

	if(strlen($PW1)<4){
		$signup_error = 1;
		$PW1_html = "<br />Your Password must be at least 4 chars";
	}

	if(strlen($PW1)>16){
		$signup_error = 1;
		$PW1_html = "<br />Your Password must be less than 16 chars";
	}

	if($PW1 != $PW2){
		$signup_error = 1;
		$PW2_html = "<br />Your Passwords must match";
	} else $PW = $PW1;

	if($signup_age<18){
		$signup_error = 1;
		$age_html = "<br />You must be at least 18 years of age to signup";
	}

	if(!eregi("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $signup_email)){
		$signup_error = 1;
		$email_html = "<br />Your Email address must be valid";
	}

	$ce_sql = "
		select
			*
		from
			$tb_users
		where
			email = '$signup_email'
	";
	$ce_query = sql_query($ce_sql);
	if(sql_num_rows($ce_query)>0){
		$signup_error = 1;
		$email_html = "<br />That email address already exists, please use a different one if you wish to run multiple accounts.";
	}

	if(!isset($signup_error)){

		$now = date("YmdHis",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

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
				sex,
				email,
				total_ratings,
				total_points,
				average_rating,
				signup
			) values (
				'',
				'$signup_username',
				password('$PW'),
				'$signup_hint',
				'$signup_realname',
				'$signup_description',
				'$signup_state',
				'$signup_country',
				'$signup_url',
				'$signup_quote',
				'$signup_age',
				'$signup_sex',
				'$signup_email',
				'1',
				'1',
				'1',
				'$now'
			)
		";
		if($su_query = sql_query($su_sql)){

			$ins_id = sql_insert_id($su_query);
			$rating_ins = "
				insert into $tb_ratings (
					id,
					user_id,
					rating,
					rater_id
				) values (
					'',
					'$ins_id',
					'1'
					'0'
				)
			";
			$rating_ins_query = sql_query($rating_ins);
			$recipient = $signup_realname . " <" . $signup_email . ">";
			$subject = "Thanks for signing up...";
			$signup_realname_array = explode(" " , $signup_realname);
			$srn = $signup_realname_array[0];
			
			$content = "Dear " . $srn . ",\r\n\r\n";
			$content .= "Thanks for signing up!  Here is what you submitted:\r\n\r\n";
			$content .= "Username: " . $signup_username . "\r\n";
			$content .= "Password: " . $PW . "\r\n\r\n";
			$content .= "You will need your Username and Password to login to the site, so be sure and save this email somewhere safe.\r\n\r\n";
			$content .= "We take security and privacy very seriously.  Your password is encrypted using one-way encryption, therfore we can not send it to you on request.  If you forget your password, we can only provide you with your chosen password hint.\r\n\r\n";
			$content .= "Your password hint is: " . $signup_hint . "\r\n\r\n";
			$content .= "You may now proceed to " . $base_url . " and update your picture and other details in your profile.\r\n\r\n";
			$content .= "Thanks,\r\n";
			$content .= $owner_name . "\r\n";
			$content .= $owner_email . "\r\n";

			$headers = "From: " . $owner_name . "<" . $owner_email . ">\n";
			$headers .= "X-Sender: <" . $owner_email . ">\n";
			$headers .= "Return-Path: <" . $owner_email . ">\n";
			$headers .= "Error-To: <" . $owner_email . ">\n";
			$headers .= "X-Mailer: " . $SERVER_NAME . "\n";

			mail($recipient, $subject, $content, $headers);

			$username = $signup_username;
			session_register("username");
			$userid = $ins_id;
			session_register("userid");

$message = <<<EOF
<br /><br /><br />Signup complete, <a href="$base_url/index.php?$sn=$sid&amp;show=upload" class="">please click</a> here to upload your picture.<br /><br /><br />
EOF;

		} else $message = "Signup failed.";

$content = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td align="center" class="regular"><br />$message<br /><br /></td>
</tr>
</table>
EOF;

} 
}

if(!isset($submit_signup) || isset($signup_error)){

if(!isset($signup_username)) $signup_username = "";
if(!isset($username_html)) $username_html = "";
if(!isset($PW1)) $PW1 = "";
if(!isset($PW2)) $PW2 = "";
if(!isset($PW1_html)) $PW1_html = "";
if(!isset($PW2_html)) $PW2_html = "";
if(!isset($signup_hint)) $signup_hint = "";
if(!isset($signup_realname)) $signup_realname = "";
if(!isset($realname_html)) $realname_html = "";
if(!isset($signup_description)) $signup_description = "";
if(!isset($signup_age)) $signup_age = "";
if(!isset($age_html)) $age_html = "";
if(!isset($signup_sex)) $signup_sex = "";
if(!isset($signup_state)) $signup_state = "";
if(!isset($signup_email)) $signup_email = "";
if(!isset($email_html)) $email_html = "";
if(!isset($signup_url)) $signup_url = "";
if(!isset($signup_quote)) $signup_quote = "";


$content = <<<EOF
<br />
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr><form method="post" action="$base_url/index.php?$sn=$sid&amp;show=signup">
<td align="right" class="regular" valign="top" width="50%"><span class="bold">Username:</span><br />4-16 characters required, you will use this<br />with your password to login to the site.</td>
<td valign="top" width="50%"><input type="text" name="signup_username" value="$signup_username" size="16" /><span class="error">$username_html</span></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Password:</span><br />4-16 characters required</td>
<td valign="top"><input type="password" name="PW1" value="$PW1" size="16" /><span class="error">$PW1_html</span></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Password Again:</span><br />4-16 characters required</td>
<td valign="top"><input type="password" name="PW2" value="$PW2" size="16" /><span class="error">$PW2_html</span></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Password Hint:</span><br />in case you forget your password</td>
<td valign="top"><input type="text" name="signup_hint" value="$signup_hint" size="16" /></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Name:</span><br />4-48 characters required.</td>
<td valign="top"><input type="text" name="signup_realname" value="$signup_realname" size="16" /><span class="error">$realname_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Description:</span><br /></td>
	<td valign="top"><textarea name="signup_description" rows="5" cols="30">$signup_description</textarea></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Age:</span><br />must be at least 18 years old</td>
<td valign="top"><input type="text" name="signup_age" value="$signup_age" size="16" /><span class="error">$age_html</span></td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Gender:</span></td>
<td valign="top"><select name="signup_sex">
<option value="m"
EOF;

if($signup_sex=="m"){$content .= " selected";}

$content .= <<<EOF
>Male</option>
<option value="f"
EOF;

if($signup_sex=="f"){$content .= " selected";}

$content .= <<<EOF
>Female</option>
</select></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">State or Province:</span></td>
	<td valign="top"><input type="text" name="signup_state" value="$signup_state" size="24" /></td>
</tr>
	<tr>
<td class="regular" align="right"><span class="bold">Country:</span></td>
<td class="regular">
<select name="signup_country">
<option value="None.gif">Please Select:</option>
EOF;

if(!isset($country)) $country = "United_States.gif";

$image_path = $base_path . "/images/flags";
$flags_list = getFlagList($image_path, $country);

$content .= <<<EOF
$flags_list
</select>
</td>
</tr>
<tr>
<td align="right" class="regular" valign="top"><span class="bold">Email Address:</span><br />valid email required</td>
<td valign="top"><input type="text" name="signup_email" value="$signup_email" size="24" /><span class="error">$email_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">URL:</span></td>
	<td valign="top"><input type="text" name="signup_url" value="$signup_url" size="24" /></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Favorite Quote:</span></td>
	<td valign="top"><input type="text" name="signup_quote" value="$signup_quote" size="24" /></td>
</tr>
<tr>
<td align="center" colspan="2"><input type="submit" name="submit_signup" value=" Sign me up -> " /></td>
</tr></form>
</table>
<br />
EOF;
}

$final_output .= table($title, $content);

/*
 * $Id: signup.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>