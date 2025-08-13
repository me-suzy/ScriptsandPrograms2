<?

/*
 * $Id: profile.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";

if(isset($update_profile)){

	if(strlen($PW1)>1){
		
		if(strlen($PW1)<4){
			$update_error=1;
			$PW1_html = "<br />Your Password must be at least 4 chars";
		}

		if(strlen($PW1)>16){
			$update_error=1;
			$PW1_html = "<br />Your Password must be less than 16 chars";
		}

		if($PW1 != $PW2){
			$update_error=1;
			$PW2_html = "<br />Your Passwords must match";

		} else $PW = $PW1;

		$PW_update = 1;
	}

	if($update_age<18){
		$update_error=1;
		$age_html = "<br />You must be at least 18 years of age to participate";
	}

	if(!eregi("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $update_email)){
		$update_error=1;
		$email_html = "<br />Your Email Address must be valid";
	}
	
	if(!isset($update_error)){

		$ud_sql = "update $tb_users set ";

		if(isset($PW_update)) $ud_sql .= "password = password('$PW'),";

		$ud_sql .= "
					age = '$update_age',
					email = '$update_email',
					quote = '$update_quote',
					url = '$update_url',
					country = '$update_country',
					state = '$update_state',
					sex = '$update_sex',
					realname = '$update_realname',
					description = '$update_description',
					hint = '$update_hint'
				where
					id = '$userid'
		";

		if($ud_query = sql_query($ud_sql))
			$message = "Profile update complete.";
		else
			$message = "Profile update failed.";
	}
}

$gp_sql = "
	select
		*
	from
		$tb_users
	where
		id = '$userid'
";
$gp_query = sql_query($gp_sql);
$gp_array = sql_fetch_array($gp_query);

$title = "Edit Profile";

$content = "<br />";

if(isset($update_profile)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td align="center" class="bold">$message</td>
</tr>
</table>
EOF;
}

if(!isset($age_html)){$age_html = "";}

$content .= <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr><form method="post" action="$base_url/index.php?$sn=$sid&amp;show=profile">
	<td align="right" class="regular" valign="top"><span class="bold">Username:</span></td>
	<td valign="top" class="regular">$username</td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Age:</span><br />must be at least 18 years old</td>
	<td valign="top"><input type="text" name="update_age" value="$gp_array[age]" size="16" /><span class="error">$age_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Gender:</span></td>
	<td valign="top"><select name="update_sex">
		<option value="m"
EOF;

if($gp_array["sex"]=="m") $content .= " selected";

$content .= <<<EOF
>Male</option><option value="f"
EOF;

if($gp_array["sex"]=="f") $content .= " selected";

if(!isset($email_html)) $email_html = "";

$content .= <<<EOF
>Female</option>
	</select></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Email Address:</span><br />valid email required</td>
	<td valign="top"><input type="text" name="update_email" value="$gp_array[email]" size="24" /><span class="error">$email_html</span></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Name:</span><br /></td>
	<td valign="top"><input type="text" name="update_realname" value="$gp_array[realname]" size="24" /></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Description:</span><br /></td>
	<td valign="top"><textarea name="update_description" rows="5" cols="40">$gp_array[description]</textarea></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">State or Province:</span></td>
	<td valign="top"><input type="text" name="update_state" value="$gp_array[state]" size="24" /></td>
</tr>
<tr>
<td class="regular" align="right"><span class="bold">Country:</span></td>
<td class="regular">
<select name="update_country">
<option value="None.gif">Please Select:</option>
EOF;

$image_path = $base_path . "/images/flags";
$flags_list = getFlagList($image_path, $gp_array["country"]);

$gp_array_url = stripslashes($gp_array["url"]);

if(!isset($email_html)) $email_html = "";
if(!isset($PW1)) $PW1 = "";
if(!isset($PW1_html)) $PW1_html = "";
if(!isset($PW2)) $PW2 = "";
if(!isset($PW2_html)) $PW2_html = "";

$content .= <<<EOF
$flags_list
</select>
</td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">URL:</span></td>
	<td valign="top"><input type="text" name="update_url" value="$gp_array_url " size="24" /></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Favorite Quote:</span></td>
	<td valign="top"><input type="text" name="update_quote" value="$gp_array[quote]" size="24" /></td>
</tr>
<tr>
	<td align="right" class="regular" valign="top"><span class="bold">Password Hint:</span></td>
	<td valign="top"><input type="text" name="update_hint" value="$gp_array[hint]" size="24" /></td>
</tr>
<tr>
	<td align="center" class="bold" valign="top" colspan="2">(Only to update)</td>
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
	<td align="center" colspan="2"><input type="submit" name="update_profile" value=" Update My Profile -> " /></td>
</tr></form>
</table><br />
EOF;

$final_output .= table($title, $content);

/*
 * $Id: profile.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>