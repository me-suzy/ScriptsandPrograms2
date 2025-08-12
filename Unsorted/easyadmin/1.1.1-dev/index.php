<?php
if (isset($HTTP_POST_VARS['username'])) {
$username=$HTTP_POST_VARS['username'];
}

if (isset($HTTP_POST_VARS['password'])) {
$password=$HTTP_POST_VARS['password'];
}

if (isset($password)) {
$password=md5($password);
}
include("incs/uinfo.php");
if (!isset($username)) {
$username="0";
}
if (!$username) {
?> 

<html>
<head>
<title>Easy Admin Logon :: Welcome to 1.1 (developement)</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="logintitle" align="center">Easy Admin Login :: Welcome</div><br><br>
<br>
<div align="center">
<form name="login" method="post" action="index.php">
<div class="logintext">Username</div><br><input type="text" name="username"><br><br>
<div class="logintext">Password</div><br><input type="password" name="password"><br><br>
<input type="submit" name="submit" value="login">
</form>
</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>

<?php
} else {
  if ($username!=$adminuser) {
  ?>

<html>
<head>
<title>Easy Admin Logon :: Wrong Username</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="logintitle" align="center">Easy Admin Login :: Wrong Username</div><br><br>
<br>
<div align="center">
<form name="login" method="post" action="index.php">
<div class="logintext">Username</div><br><input type="text" name="username"><br><br>
<div class="logintext">Password</div><br><input type="password" name="password"><br><br>
<input type="submit" name="submit" value="login">
</form>
</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>

  <?php
  } else {
    if ($password!=$adminpass) {
    ?>

<html>
<head>
<title>Easy Admin Logon :: Wrong Password</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="logintitle" align="center">Easy Admin Login :: Wrong Password</div><br><br>
<br>
<div align="center">
<form name="login" method="post" action="index.php">
<div class="logintext">Username</div><br><input type="text" name="username"><br><br>
<div class="logintext">Password</div><br><input type="password" name="password"><br><br>
<input type="submit" name="submit" value="login">
</form>
</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>

    <?php
    } else {
    setcookie ("Loggedin", "$username",time()+3600);
    ?>

<html>
<head>
<title>Easy Admin Logon :: Completed :: Redirecting</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
<script language="Javascript">
window.location="admin.php"
</script>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="logintitle" align="center">Easy Admin Login :: Redirecting</div><br><br>
<br>
<div class="logintext" align="center">You are now logged in. Click<a href="admin.php">here</a> to continue.</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>

    <?php
    }
  }
}
?>

