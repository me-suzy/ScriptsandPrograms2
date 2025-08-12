<?php
include("config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$id = isset($_GET['id']) ? $_GET['id'] : $id;

if(isset($_POST['update_user'])){ 
$ud_sql = "
	update
		$tb_users
	set
";

if(strlen($_POST['new_password'])>0){
	$ud_sql .= "
		password = password('$_POST[new_password]'),
	";
}

$hint = addslashes($_POST['new_hint']);
$description = addslashes($_POST['new_description']);
$quote = addslashes($_POST['new_quote']);

$ud_sql .= "
		hint = '$hint',
		realname = '$_POST[new_realname]',
		description = '$description',
		age = '$_POST[new_age]',
		user_type = '$_POST[new_user_type]',
		state = '$_POST[new_state]',
		country = '$_POST[new_country]',
		email = '$_POST[new_email]',
		url = '$_POST[new_url]',
		quote = '$quote'
	where
		id = '$_POST[id]'
";

$ud_query = mysql_query($ud_sql) or die(mysql_error());
$update_message = "Update Complete";

$um = <<<EOF
<tr>
<td class="bold" colspan="2" align="center">$update_message</td>
</tr>
EOF;

}

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edit User Profile</title>
<script>
var ImagePopUpX = (screen.width/2)-320;
var ImagePopUpY = (screen.height/2)-240;
var ImagePos = "left="+ImagePopUpX+",top="+ImagePopUpY;
function ImagePopUp(link){
ImagePopUpWindow = window.open(link,"Image","scrollbars=yes,resizable=yes,width=640,height=480,"+ImagePos);
}
</script>
EOF;

if(isset($_POST['update_user'])){
$final_output .= <<<EOF
<script>
window.opener.window.document.location.reload();
window.close();
</script>
EOF;
}

include("$include_path/styles.php");

$final_output .= <<<EOF
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

$query = mysql_query($sql) or die(mysql_error());

$array = mysql_fetch_array($query);

$hint = stripslashes($array["hint"]);
$description = stripslashes($array["description"]);
$quote = stripslashes($array["quote"]);

$um = isset($um) ? $um : "";

$user_type_options = get_user_types($array["user_type"]);

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr><form method="post" action="$base_url/admin/user_profile.php">
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
<td class="regular"><textarea name="new_description" rows="4" cols="45" wrap="virtual">$description</textarea></td>
</tr>
<tr>
<td class="regular" align="right">Age:</td>
<td class="regular"><input type="text" name="new_age" value="$array[age]" size="5" /></td>
</tr>
<tr>
<td class="regular" align="right">User Type:</td>
<td class="regular">
<select name="new_user_type">
$user_type_options
</select>&nbsp;&nbsp;<a href="javascript:ImagePopUp('$base_url/admin/user_image.php?userid=$array[id]');">Image</a>
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
<td class="regular"><input type="text" name="new_quote" value="$quote" size="35" /></td>
</tr>
<tr>
<td class="regular" align="right">Password:<br><span class="smallregular">(Leave blank unless changing)</span></td>
<td class="regular"><input type="text" name="new_password" size="16" /></td>
</tr>
<tr>
<td class="regular" align="right">Password Hint:</td>
<td class="regular"><input type="text" name="new_hint" value="$hint" size="35" /></td>
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

$final_output .= small_table("Edit User", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>