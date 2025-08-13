<?

/*
 * $Id: user_profile.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

if(isset($update_user)){ 
$ud_sql = "
	update
		$tb_users
	set
";

if(strlen($new_password)>0){
	$ud_sql .= "
		password = password('$new_password'),
	";
}

$ud_sql .= "
		hint = '$new_hint',
		realname = '$new_realname',
		description = '$new_description',
		age = '$new_age',
		sex = '$new_sex',
		state = '$new_state',
		country = '$new_country',
		email = '$new_email',
		url = '$new_url',
		quote = '$new_quote'
	where
		id = '$id'
";

if($ud_query = sql_query($ud_sql))
	$update_message = "Update Complete";
else
	$update_message = "Update Failed";

$um = <<<EOF
<tr>
<td class="bold" colspan="2" align="center">$update_message</td>
</tr>
EOF;

}

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
$styles
EOF;

if(isset($update_user)){
$content .= <<<EOF
<script>window.close();</script>
EOF;
}

$content .= <<<EOF
</head>
<body bgcolor="$page_bg_color">
EOF;

$sql = "
	select
		*
	from
		$tb_users
	where
		id = '$id'
";

$query = sql_query($sql);

$array = sql_fetch_array($query);

if(!isset($um)){$um = "";}

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr><form method="post" action="$base_url/admin/user_profile.php?$sn=$sid">
<td>
<table cellpadding="10" cellspacing="0" border="0" align="center">
$um
<tr>
<td class="regular" align="right">Username:</td>
<td class="regular">$array[username]</td>
</tr>
<tr>
<td class="regular" align="right">Name:</td>
<td class="regular"><input type="text" name="new_realname" value="$array[realname]" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">Description:</td>
<td class="regular"><textarea name="new_description" rows="4" cols="45" wrap="virtual">$array[description]</textarea></td>
</tr>
<tr>
<td class="regular" align="right">Age:</td>
<td class="regular"><input type="text" name="new_age" value="$array[age]" size="5" /></td>
</tr>
<tr>
<td class="regular" align="right">Gender:</td>
<td class="regular">
<select name="new_sex">
<option value="m"
EOF;
if($array["sex"] == "m"){ $table .= " selected"; }
$table .= <<<EOF
>Male</option>
<option value="f"
EOF;
if($array["sex"] == "f"){ $table .= " selected"; }
$table .= <<<EOF
>Female</option>
</select>
</td>
</tr>
<tr>
<td class="regular" align="right">State:</td>
<td class="regular"><input type="text" name="new_state" value="$array[state]" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">Country:</td>
<td class="regular">
<select name="new_country">
<option value="None.gif">Please Select:</option>
EOF;

$image_path = $base_path . "/images/flags";
$flags_list = getFlagList($image_path, $array["country"]);

$table .= <<<EOF
$flags_list
</select>
</td>
</tr>
<tr>
<td class="regular" align="right">Email:</td>
<td class="regular"><input type="text" name="new_email" value="$array[email]" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">URL:</td>
<td class="regular"><input type="text" name="new_url" value="$array[url]" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">Quote:</td>
<td class="regular"><input type="text" name="new_quote" value="$array[quote]" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">Password:<br><span class="smallregular">(Leave blank unless changing)</span></td>
<td class="regular"><input type="text" name="new_password" size="16" /></td>
</tr>
<tr>
<td class="regular" align="right">Password Hint:</td>
<td class="regular"><input type="text" name="new_hint" value="$array[hint]" size="35" /></td>
</tr>
<tr>
<td class="regular" colspan="2" align="center"><input type="submit" name="update_user" value=" Update Settings " /></td>
</tr>
<input type="hidden" name="id" value="$array[id]" />
</table>
</td>
</form></tr>
</table>
EOF;

$content .= small_table("Edit User", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: user_profile.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>