<?

/*
 * $Id: password.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

if(isset($update_admin)){

if(strlen($new_pw)>1){
	if($new_pw == $new_pw2)
		$hash = md5($new_pw);
	else
		$pw_error = "Your passwords do not match";
}

$ud_sql = "
	update
		$tb_admin
	set
		username = '$new_username'
";

if(isset($hash)) $ud_sql .= ", password = '$hash'";

$ud_sql .= "
	where
		id = '1'
";

if(!isset($pw_error)){
	if($ud_query = sql_query($ud_sql))
		$update_message = "Admin update complete";
	else
		$update_message = "Admin update failed, database error";
} else
	$update_message .= "Admin update failed, password error";

if(isset($update_message)){
$um = <<<EOF
<tr>
<td class="bold" colspan="2" align="center">$update_message</td>
</tr>
EOF;
}
}

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
<script>if(top.location == self.location){top.location.href='$base_url/admin/';}</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$sql = "
	select
		*
	from
		$tb_admin
	where
		id = '1'
";

$query = sql_query($sql);
$array = sql_fetch_array($query);

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td>
<table cellpadding="10" cellspacing="0" border="0" align="center">
EOF;

if(isset($update_message)) $table .= $um;

if(!isset($pw_error)) $pw_error = "";

$table .= <<<EOF
<tr><form method="post" action="$base_url/admin/password.php?$sn=$sid">
<td class="regular" align="right">Admin  Username:</td>
<td class="regular"><input type="text" name="new_username" value="$array[username]" size="20" /></td>
</tr>
<tr>
<td class="regular" colspan="2" align="center">Leave blank unless changing:</td>
</tr>
<tr>
<td class="regular" align="right">Admin Password:</td>
<td class="regular"><input type="password" name="new_pw" size="20" /><br><span class="error">$pw_error</span></td>
</tr>
<tr>
<td class="regular" align="right">Password Again:</td>
<td class="regular"><input type="password" name="new_pw2" size="20" /></td>
</tr>
<tr>
<td class="regular" colspan="2" align="center"><input type="submit" name="update_admin" value=" Update Admin Login " /></td>
<input type="hidden" name="sn" value="$sn" />
<input type="hidden" name="sid" value="$sid" />
</form></tr>
</table>
</td>
</tr>
</table>
EOF;

$content .= small_table("Edit Admin Login", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: password.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>