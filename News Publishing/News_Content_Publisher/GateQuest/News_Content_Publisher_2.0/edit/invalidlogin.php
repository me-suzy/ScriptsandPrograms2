<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>GateQuest php News/Content Publisher</title>
	<link rel="stylesheet" href="../docs/misc/styles.css" type="text/css">
</head>

<body>

<table style="height: 100%; text-align: center; width: 90%">
	<tr>
		<td style="height: 65px; padding: 10px 0px 0px 10px; text-align: left"><a href="http://www.gatequest.net/products/News_Content_Publisher/"><img alt="" border="0" width="100" height="50" src="../docs/misc/gfx/gq_logo.gif"></a></td>
	</tr>
	<tr>
		<td style="vertical-align: middle">
			<table>
				<form method="post" action="<? echo $PHP_SELF ?>?action=login">
				<tr>
					<td><b>Login name:</b><br>
					<input type="text" size="30" name="loginname" onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
					<td><br>&nbsp;&nbsp;Use &raquo;&nbsp;&nbsp;news</td>
				</tr>
				<tr>
					<td><b>Password:</b><br>
					<input type="password" size="30" name="password" onfocus="this.style.borderColor='#0072BC';" onblur="this.style.borderColor='silver';"></td>
					<td><br>&nbsp;&nbsp;Use &raquo;&nbsp;&nbsp;demo</td>
				</tr>
				<tr>
					<td colspan="2"><p><? if (substr($PHP_SELF,-9) == "login.php") { echo "<p>Never link directly to this file, always link to the protected file!</p>"; } else { echo "<input class=send type=submit value=\"Login!\">"; } ?></p>
					<p><span class="error"><b>Invalid login name or password!</b> &nbsp;Please try again.</span></p></td>
				</tr>
				</form>
			</table>		
		</td>
	</tr>
</table>

</body>
</html>
