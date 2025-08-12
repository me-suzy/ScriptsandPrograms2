<?php
//this first section adds supptort for newer version of 
//php that dont have register_globals turned on as default!
//begin

if (isset($HTTP_POST_VARS['action'])) {
$action=$HTTP_POST_VARS['action'];
}

if (isset($HTTP_POST_VARS['adminusername'])) {
$adminusername=$HTTP_POST_VARS['adminusername'];
}

if (isset($HTTP_POST_VARS['adminpassword'])) {
$adminpassword=$HTTP_POST_VARS['adminpassword'];
}

//end


//check  action value is set to prevent errors
if (!isset($action)) {  $action="start";  }

//is the page is loaded for the first time then action should now be start
if ($action=="start") {

//main - fisrt installation screen
##################################################################
?>
<html>
<head>
<title>Easy Admin :: Installation</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="installtitle" align="center">Easy Admin Installation</div><br><br>
<div class="installtext" align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to the Easy Admin Installation, please
choose a username and password to use, enter them below and hit enter. Please note that the username 
'nobody' is not allowed to prevent problems with the logout system, and both fields must be filled in. You 
may also get errors if you enter the value '0' (by itself) in either of the fields.</div>
<div align="center">
<form method="POST" action="installer.php">
<div class="installtext">Admin Username</div>
        <input type="text" value="" name="adminusername"><br>
<div class="installtext">Admin Password</div>
       <input type="password" value="" name="adminpassword"><br>
<input type="hidden" value="install" name="action"><br>
<input type="submit" value="Install">
</form>
</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>
<?php
##################################################################

//when the main form is submitted action is set to install, the installation then takes place after checks
} elseif ($action=="install") {

//check to see if all fields have been filled and make sure that 
if ((!$adminusername)or(!$adminpassword)or($adminusername=="nobody")) {


//return error to browser - show warning and main installation again
##################################################################
?>
<html>
<head>
<title>Easy Admin :: Installation</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="installtitle" align="center">Easy Admin Installation :: Error</div><br><br>
<div class="installtext" align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;One field has a negative value or the username is invalid.
Please re-enter a username and password. You must enter both fields and use a username other than
'nobody'.</div>
<div align="center">
<form method="POST" action="installer.php">
<div class="installtext">Admin Username</div>
        <input type="text" value="" name="adminusername"><br>
<div class="installtext">Admin Password</div>
       <input type="password" value="" name="adminpassword"><br>
<input type="hidden" value="install" name="action"><br>
<input type="submit" value="Install">
</form>
</div>
<?php include("incs/footer.inc"); ?>
</body>
</html>
<?php
##################################################################
} else {

//installation events
$adminpassword=md5($adminpassword);
$fc="<?php \$adminuser=\"$adminusername\"; \$adminpass=\"$adminpassword\"; ?>";
chmod("incs/uinfo.php",0755);
$fp = fopen("incs/uinfo.php","w"); 
fwrite($fp,$fc); 
fclose($fp);


//finished install screen
##################################################################
?>
<html>
<head>
<title>Easy Admin :: Installation</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div class="installtitle" align="center">Easy Admin Installation :: Completed</div><br><br>
<div class="installtext" align="justify">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Congratulations, you have finished the small installation
process. Use the form below to logon for the first time. For security reasons you MUST 
delete this file as soon as possible. But dont fear you can delete files from within the admin section.</div>
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
##################################################################

}

} else {


//if this shows it means ive gone wrong somewhere - so this error message tells me
##################################################################
?>
<html>
<head>
<title>Easy Admin :: Installer script error</title>
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<div align="center" class="scripterror">Script error - action not set to a valid value</div><br>
<form method="POST" action="installer.php">
<input type="hidden" value="start" name="action"><br>
<input type="submit" value="Start Install">
</form>
<br>
<?php include("incs/footer.inc"); ?>
</body>
</html>
<?php
##################################################################

}

?>