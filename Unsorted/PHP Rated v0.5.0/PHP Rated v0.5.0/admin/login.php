<?

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

// process login form
if(isset($submit_login)){
	$hash = md5($login_password);
	$sql = "
		select
			*
		from
			$tb_admin
		where
			username = '$login_username'
		and
			password = '$hash'
	";
	$query = sql_query($sql);
	if(sql_num_rows($query) == 1){
		if($array = sql_fetch_array($query)){
			$admin = $array["username"];
			session_register("admin");
			header("Location: index.php?$sn=$sid");
		}
	}
}

$d = dir("./");
$entry_array = array();
while($entry = $d->read()){
	if((eregi(".sql$", $entry)) && !$debug){
		$entry_array[] = $entry;
	}
}
$d->close();

if(!isset($vars_set) or $vars_set == false)
	do_settings();

include("$include_path/$table_file");
include("$include_path/common.php");

$styles = template("styles");
eval("\$styles = \"$styles\";");

$html = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>pRated Admin Login</title>
$styles
</head>
<body bgcolor="$page_bg_color"><br><br><br><center>
EOF;

if(sizeof($entry_array)>0){
	$s = "";
	if(sizeof($entry_array)>1) $s = "s";
$error_html = <<<EOF
You must delete the following file$s before proceeding:<br><br>
EOF;
	while(list(,$value) = each($entry_array)){
		$error_html .= "&nbsp;&nbsp;&nbsp;" . $value . "<br>\n";
	}
$error_html .= <<<EOF
<br>
EOF;
}

// the login form
if(!isset($login_username)) $login_username = "";

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
<tr><form method="post" action="login.php?$sn=$sid">
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

$html .= small_table("Admin Login", $form);

$html .= <<<EOF
</center>
</body>
</html>
EOF;

echo $html;

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>