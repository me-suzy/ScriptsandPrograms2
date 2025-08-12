<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

if(isset($_POST['update_profile'])){

	if(isset($_POST['keep_me_logged_in'])){
		if($_POST['keep_me_logged_in'] == "Y"){
			$md5 = md5(time());
			$sql = "
				replace into $tb_cookies (
					userid,
					cookie
				) values (
					'$_SESSION[userid]',
					'$md5'
				)
			";
			$query = mysql_query($sql) or die(mysql_error());
			setcookie("keep_me_logged_in", $md5, time() + 31536000);
			$keep_me_logged_in_yes = " checked";
			$keep_me_logged_in_no = "";
			$_SESSION['sl'] = false;
		} else {
			unset($_SESSION['rc']);
			$_SESSION['sl'] = true;
			setcookie("keep_me_logged_in");
			$keep_me_logged_in_yes = "";
			$keep_me_logged_in_no = " checked";
		}
	}

	if(strlen($_POST['PW1']) > 1){
		
		if(strlen($_POST['PW1']) < 4){
			$update_error=1;
			$PW1_html = "<br>Your Password must be at least 4 chars";
		}

		if(strlen($_POST['PW1']) > 16){
			$update_error=1;
			$PW1_html = "<br>Your Password must be less than 16 chars";
		}

		if($_POST['PW1'] != $_POST['PW2']){
			$update_error=1;
			$PW2_html = "<br>Your Passwords must match";

		} else $PW = $_POST['PW1'];

		$PW_update = 1;
	}

	if($_POST['update_age'] < 18){
		$update_error=1;
		$age_html = "<br>You must be at least 18 years of age to participate";
	}

	if(!eregi("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $_POST['update_email'])){
		$update_error=1;
		$email_html = "<br>Your Email Address must be valid";
	}
	
	if(!isset($update_error)){

		$ud_sql = "update $tb_users set ";

		if(isset($PW_update)) $ud_sql .= "password = password('$PW'),";

		$quote = addslashes($_POST['update_quote']);
		$description = addslashes($_POST['update_description']);
		$hint = addslashes($_POST['update_hint']);

		$ud_sql .= "
				age = '$_POST[update_age]',
				email = '$_POST[update_email]',
				quote = '$quote',
				url = '$_POST[update_url]',
				country = '$_POST[update_country]',
				state = '$_POST[update_state]',
				user_type = '$_POST[update_user_type]',
				realname = '$_POST[update_realname]',
				description = '$description',
				hint = '$hint',
				subscribed = '$_POST[subscribed]'
			where
				id = '$_SESSION[userid]'
		";

		$ud_query = mysql_query($ud_sql) or die(mysql_error());
		$message = "Profile update complete.";
	}
}

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['update_profile'])){
	if(isset($_COOKIE['keep_me_logged_in']) && $_COOKIE['keep_me_logged_in']){
		$keep_me_logged_in_yes = " checked";
		$keep_me_logged_in_no = "";
	} else {
		$keep_me_logged_in_yes = "";
		$keep_me_logged_in_no = " checked";
	}
}

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

$content = "";

$gp_sql = "
	select
		*
	from
		$tb_users
	where
		id = '$_SESSION[userid]'
";
$gp_query = mysql_query($gp_sql) or die(mysql_error());
$gp_array = mysql_fetch_array($gp_query);

$gp_array_url = stripslashes($gp_array["url"]);

if(!isset($age_html)) $age_html = "";
if(!isset($email_html)) $email_html = "";
if(!isset($PW1)) $PW1 = "";
if(!isset($PW1_html)) $PW1_html = "";
if(!isset($PW2)) $PW2 = "";
if(!isset($PW2_html)) $PW2_html = "";

if($gp_array["subscribed"] == "yes"){
	$subscribed_yes = " checked";
	$subscribed_no = "";
} else {
	$subscribed_yes = "";
	$subscribed_no = " checked";
}

$flags_list = getFlagList($base_path . "/images/flags", $gp_array["country"]);
$user_types = get_user_types($gp_array["user_type"]);
$states_list = get_states_list($gp_array["state"]);

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" class="regular" href="$base_url/">$site_title</a> >> Edit Profile</td>
</tr>
</table><br />
EOF;

if(isset($_POST['update_profile'])){
$content .= <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td align="center" class="bold">$message</td>
</tr>
</table>
EOF;
}

$age_options = get_age_options($gp_array["age"]);

$content .= <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr><form method="post" action="$base_url/profile.php">
	<td align="right" class="bold" valign="top">Username:</td>
	<td valign="top" class="regular">$_SESSION[username]</td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Keep me logged in:</span><br>(store a login cookie)</td>
	<td valign="top" class="regular"><input type="radio" name="keep_me_logged_in" value="Y"$keep_me_logged_in_yes> Yes <input type="radio" name="keep_me_logged_in" value="N"$keep_me_logged_in_no> No</td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Age:</span><br>must be at least 18 years old</td>
	<td valign="top"><select class="input" name="update_age">$age_options</select><span class="error">$age_html</span></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">User Type:</td>
	<td valign="top"><select class="input" name="update_user_type">$user_types</select></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Email Address:<br>valid email required</td>
	<td valign="top"><input class="input" type="text" name="update_email" value="$gp_array[email]" size="24"><span class="error">$email_html</span></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Name:<br></td>
	<td valign="top"><input class="input" type="text" name="update_realname" value="$gp_array[realname]" size="24"></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Description:<br></td>
	<td valign="top"><textarea class="input" name="update_description" rows="6" cols="50">$gp_array[description]</textarea></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">State or Province:</td>
	<td valign="top"><select class="input" name="update_state"><option value="">None/Other</option>$states_list</select></td>
</tr>
<tr>
<td class="bold" align="right">Country:</td>
<td class="regular">
<select class="input" name="update_country">
<option value="None.gif">Please Select:</option>
$flags_list
</select>
</td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">URL:</td>
	<td valign="top"><input class="input" type="text" name="update_url" value="$gp_array_url" size="24"></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Favorite Quote:</td>
	<td valign="top"><input class="input" type="text" name="update_quote" value="$gp_array[quote]" size="24"></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Password Hint:</td>
	<td valign="top"><input class="input" type="text" name="update_hint" value="$gp_array[hint]" size="24"></td>
</tr>
<tr>
	<td align="right" class="bold" valign="top">Subscribed To Mailings:</td>
	<td valign="top" class="regular"><input type="radio" name="subscribed" value="yes"$subscribed_yes> Yes <input type="radio" name="subscribed" value="no"$subscribed_no> No</td>
</tr>
<tr>
	<td align="center" class="bold" valign="top" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td align="center" class="bold" valign="top" colspan="2">(Only to update password, leave blank otherwise)</td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Password:</span><br>4-16 characters required</td>
	<td valign="top"><input class="input" type="password" name="PW1" value="$PW1" size="16"><span class="error">$PW1_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Password Again:</span><br>4-16 characters required</td>
	<td valign="top"><input class="input" type="password" name="PW2" value="$PW2" size="16"><span class="error">$PW2_html</span></td>
</tr>
<tr>
	<td align="center" colspan="2"><input class="button" type="submit" name="update_profile" value=" Update My Profile -> "></td>
</tr></form>
</table><br><br><br>
EOF;

$final_output .= table("Edit Profile", $content);

$final_output .= <<<FO
</td>
</tr>
FO;

$final_output .= <<<FO
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