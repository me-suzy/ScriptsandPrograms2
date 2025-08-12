<?php
	
	if($action=='logout')
	{
		session_start();
		session_destroy();
	}
	include('./include/connection.php');
	$message = '';
	if(isset($submitForm))
	{
		$SQL = "SELECT id, full_name,rights, group_id FROM banner_user WHERE user_id = '$login_id' AND pass = '$pass' AND status = 1";
		$result = @mysql_query($SQL,$con);
		if(@mysql_affected_rows()>0)
		{
			$row = mysql_fetch_array($result);
			$user_name = $row[1];
			session_register("user_name");
			$id = $row[0];
			session_register("id");
			$rights = $row[2];
			session_register("rights");
			$group = $row[3];
			session_register("group");
			header('Location: banner_main.php');
			exit;
		}
		else
		{
			$message = 'Your ID / password is incorrect. Please try again.';
		}
	}
	
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Demo Login</title>
<meta name="Keywords" content="">
<meta name="Description" content="">
<style type="text/css">
		.copyright {font: 8pt arial}
		.copyrightsite {font: bold 8pt verdana}
		.header {font: bold 10pt verdana}
		.label {font: 9pt arial}
		.error {font: italic 8pt arial; color: red}
		input {font: 8pt arial}
</style>
</head>
<body bgcolor="white">
<center>
<form action="index.php" method="post">
<table width="70%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#ffae00">
		<tr><td>&nbsp;</td></tr>
		<tr><td align="center">
		<table width="78%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#ffae00">
		<tr align="center">
			<td colspan="3" class="header">
				Administration Login<br><hr width="80%">
			</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr><td colspan="3" class="error"><?php print($message); ?></td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
			<td class="label">Login ID</td><td class="label">:</td><td><input type="text" name="login_id" maxlength="20" class="label"></td>
		</tr>
		<tr>
			<td class="label">Password</td><td class="label">:</td><td><input type="password" name="pass" maxlength="15" class="label"></td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr><td colspan="3" class="copyright">** For demo please use Login ID : <b>demo</b> and password : <b>demo</b></td></tr>
		<tr><td colspan="3" class="copyright">** Login ID and password are case sensitive.</td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr><td colspan="3" align="right"><input type="reset" value="Clear">&nbsp;&nbsp;&nbsp;<input type="submit" name="submitForm" value="Login"></td></tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		</table>
		</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		</table>
		</td>
	</tr>
</table>
</form>
<?php include("./include/footer.php"); ?>
</center>
</body>
</html>
