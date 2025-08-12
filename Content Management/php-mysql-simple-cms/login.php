<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Login</title>
	<link href="/style.css" rel="stylesheet" type="text/css">
</head>

<body style="font-family: tahoma;">
<?
require ("config.php");

if ($e ==  "1") {
echo "<p>Please Login</p>";
}
?>
<h3>Simple CMS Login</h3>
<p><img src='img/security.gif' title='please login'></p>
<form action="login.php" method="post" name="frm">
<table cellspacing="4" cellpadding="4" style="border-bottom-width: thin; border-left-width: thin; border-right-width: thin; border-top-width: thin; border-style: dotted; border-color: red;">
	<tr>
		<td>username</td>
		<td><input type="text" name="formlogin" class="cssborder"></td>
	</tr>
	<tr>
		<td>password</td>
		<td><input type="password" name="formpass" class="cssborder"></td>
	</tr>
</table>
<br> <input type="submit" value="login" class="cssborder">	 
</form>

<?
//echo "formlogin=$formlogin";
//echo "<br>formpass=$formpass";
//echo "<br>login=$login";
//echo "<br>pass=$pass";

if ($formpass == $pass && $formlogin == $login) {
	session_register("loggedin");
	$loggedin = "1";
//logged in so run a javascript redirect to admin page.
?>
<script language="javascript">
<!-- 
location.replace("admin");
-->
</script>

	<h4><a href='admin'>you are now logged in, continue to the admin section</a></h4>
<?
}
?>

<p><a href="login.php"><small>reload</small></a></p>
</body>
</html>
