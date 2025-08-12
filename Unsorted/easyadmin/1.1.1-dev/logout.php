<?php
setcookie ("Loggedin", "nobody",time()+3600);
?>
<html>
<head>
<title>Easy Admin Logon :: Welcome</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="logintitle" align="center">Easy Admin Login :: Welcome</div><br><br>
<br>
<div class="logintext" align="center"><br>You should now be logged out. Click <a href="admin.php">here</a> to check. You should be redirected to the main page.<br>
Please note this log in and out system you need cookies enabled.
</div><br><br><br>
<?php include("incs/footer.inc"); ?>
</body>
</html>