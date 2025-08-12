<?php session_start();
include('config.php');
$username = $_POST['username'];
$password = $_POST['password'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ice-Downloader</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
a:link {
	color: #FF0000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF0000;
}
a:hover {
	text-decoration: underline;
	color: #FF6600;
}
a:active {
	text-decoration: none;
	color: #FF6600;
}
a {
	font-size: 10px;
}
-->
</style></head>

<body>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr>
    <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center"><img src="logo.jpg" height="130"></div></td>
      </tr>
      <tr>
        <td height="145">
		<?php if($_POST['submit']){
	if($username == $username2 && $password == $password2) {
		session_register("username");
		$_SESSION['username'] = $username;
		echo "<font color=\"#FF0000\"><b>Thank you for login in, you will be redirected to the protected pages in 2 seconds <META HTTP-EQUIV=\"refresh\" CONTENT=\"2; URL=protected.php\"></b></font>";
	}
} ?>
		<form name="form1" method="post" action="index.php">
        Username:<br>
        <input name="username" type="text" id="username">
        <br>
        Password: <br>
        <input name="password" type="password" id="password">
        <br>
        <br>
        <input name="submit" type="submit" id="submit" value="Login!">
        <br>
        <br>
            </form></td>
      </tr>
      <tr>
        <td><div align="center">Powered by <a href="www.ice-host.net" target="_blank">Ice-Downloads</a></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
