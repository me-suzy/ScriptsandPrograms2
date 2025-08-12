<?php
include("./config.php");
include("$include_path/common.php");

if($lock_admin_with_owner_ip)
	if($_SERVER["REMOTE_ADDR"] != $owner_ip){
		header("Location: $base_url/");
		exit();
	}

if(isset($_POST['submit_login'])){
	$hash = md5($_POST['login_password']);
	$sql = "
		select
			*
		from
			$tb_admin
		where
			username = '$_POST[login_username]'
		and
			password = '$hash'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);
		$_SESSION['admin'] = $array["username"];
		header("Location: $base_url/admin/");
		exit();
	}
}

include("$include_path/$table_file");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="javascript" type="text/javascript">if(top.location != self.location){top.location = self.location;}</script>
<title>Admin Login</title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color"><br><br><br><center>
EOF;

$login_username = isset($_POST['login_username']) ? $_POST['login_username'] : "";

$form = <<<EOF
<table cellpadding="10" cellspacing="0" border="0" align="center">
EOF;

if(isset($error_html)){
$form .= <<<EOF
<tr>
	<td colspan="2" class="regular">$error_html</td>
</tr>
EOF;
}

$form .= <<<EOF
<tr><form method="post" action="$base_url/admin/login.php">
	<td align="right" class="regular">Admin:</td>
	<td class="regular"><input type="text" name="login_username" value="$login_username" size="16"></td>
</tr>
<tr>
	<td align="right" class="regular">Password:</td>
	<td class="regular"><input type="password" name="login_password" size="16"></td>
</tr>
<tr>
	<td colspan="2" align="center" class="regular"><input type="submit" name="submit_login" value="Login"></td>
</tr></form>
</table>
EOF;

$final_output .= small_table("Admin Login", $form);

$final_output .= <<<EOF
</center>
</body>
</html>
EOF;

echo $final_output;

?>