<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

$return_message = "";

if(isset($_POST['login'])){

	$sql = "
		select
			username,
			id
		from
			$tb_users
		where
			username = '$_POST[UN]'
		and
			password = password('$_POST[PW]')
	";
	$query = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($query)){
		
		$_SESSION['username'] = mysql_result($query, 0, "username");
		$_SESSION['userid'] = (int) mysql_result($query, 0, "id");

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
			$_SESSION['sl'] = false;
		} else {
			$_SESSION['sl'] = true;
		}
		
		$r = urldecode($_GET['r']);
		$url = $base_url . "/" . $r;

		header("Location: $url");
		exit();

	} else {
		$return_message = "Login Failed<br><br>";
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

$must_signup = "";
if(isset($_SESSION['m']) && $_SESSION['m'] == 1){
$must_signup = <<<EOF
<br>
<table width="100%">
<tr>
	<td class="bold" align="center">You must login to access that area of $site_title.</td>
</tr>
</table>
EOF;
unset($_SESSION['m']);
}

$r = urlencode($_GET['r']);

$login = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Login</td>
</tr>
</table>
<br>$must_signup
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" align="center"><br><br><span class="error">$return_message</span>
	<table cellpadding="5" cellspacing="1" border="0">
	<tr><form method="post" action="$base_url/login_page.php?r=$r">
		<td class="bold" align="right">username:</td>
		<td valign="top"><input class="input" type="text" name="UN" size="16"></td>
	</tr>
	<tr>
		<td class="bold" align="right">password:</td>
		<td valign="top"><input class="input" type="password" name="PW" size="16"></td>
	</tr>
	<tr>
		<td align="right" class="bold" valign="top">stay logged in:</td>
		<td valign="top" class="regular"><input type="radio" name="keep_me_logged_in" value="Y" checked> Yes <input type="radio" name="keep_me_logged_in" value="N"> No</td>
	</tr>
	<tr>
		<td class="regular" align="right" colspan="2"><input class="button" type="submit" name="login" value=" login -> "></td>
	</tr></form>
	<tr>
		<td class="bold" align="center" colspan="2"><br><br><a class="bold" href="$base_url/lost.php">Lost Password</a> | <a class="bold" href="$base_url/signup.php">Signup Now</a><br></td>
	</tr></form>
	</table>
	<br><br><br><br>
	</td>
</tr>
</table>
EOF;

$final_output .= table("Login Page", $login);

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