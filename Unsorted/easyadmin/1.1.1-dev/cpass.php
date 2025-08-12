<?php
if (isset($HTTP_COOKIE_VARS['Loggedin'])) {
$_COOKIE['Loggedin']=$HTTP_COOKIE_VARS['Loggedin'];
}
$cooked=stripslashes($_COOKIE["Loggedin"]);
include("incs/uinfo.php");
if ($cooked==$adminuser) {

//beggining of module content
############################################################
?>


<html>
<head>
<title>Easy Admin :: Change Admin Pass Module</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<table width="601" border="0" cellspacing="0" cellpadding="0" height="287">
  <tr>
    <td height="67" width="110" align="center" valign="middle">&nbsp;</td>
    <td height="67" width="491" align="center" valign="middle" class="admintitle"><img src="incs/eallgo.jpg" height="43"></td>
  </tr>
  <tr>
    <td width="110" valign="top" align="left"><?php include("incs/module-list.inc"); ?></td>
    <td width="491" align="center" valign="top"> 
<br>
      <table width="90%" border="0" cellspacing="0" cellpadding="0" height="100%">
        <tr> 
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Change Admin Pass Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">
<?php
if (isset($HTTP_POST_VARS['cpassaction'])) {
$cpassaction=$HTTP_POST_VARS['cpassaction'];
}
if (isset($HTTP_POST_VARS['newpass'])) {
$newpass=$HTTP_POST_VARS['newpass'];
}
if (isset($HTTP_POST_VARS['vnewpass'])) {
$vnewpass=$HTTP_POST_VARS['vnewpass'];
}
if (!isset($cpassaction)) {
$cpassaction="0";
}
if (($cpassaction!="form")and($cpassaction!="verify")and($cpassaction!="cpass"))   {
$cpassaction="form";
}

if (($cpassaction=="verify")and(!$newpass)) {
$cpassaction="form";
}

if (($cpassaction=="cpass")and(!$newpass)) {
$cpassaction="form";
}

if (($cpassaction=="cpass")and($newpass!=$vnewpass)) {
$cpassaction="form";
}

if ($cpassaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Change Admin Pass :: Enter Password</div><br>
<div align="justify" class="admintext">
Please enter a password that you will remember! You cannot
enter '0' as a password or leave it blank else you will see this page again. You will be asked to verify 
the password on the next page.
<br>
<form method="POST" action="cpass.php">
<div class="admintext">Enter Password</div>
          <input type="password" value="" name="newpass"><br>
<input type="hidden" value="verify" name="cpassaction"><br>
<input type="submit" value="Continue">
</form>
</div>

<?php
} elseif ($cpassaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Change Admin Pass :: Please Verify</div><br>
<div class="admintext" align="left">Please verify your new password by re-entering it. Remember 
passwords are case sensitive. If you enter a non-matching password you will see the first screen again.
<form method="POST" action="cpass.php">
<div class="admintext">Re-enter Password</div>
          <input type="password" value="" name="vnewpass"><br>
<input type="hidden" value="<?php echo"$newpass"; ?>" name="newpass">
<input type="hidden" value="cpass" name="cpassaction"><br>
<input type="submit" value="Continue">
</form>
<form method="POST" action="cpass.php">
<input type="hidden" value="form" name="cpassaction"><br>
<input type="submit" value="Change / Cancel">
</form>
</div>

<?php
} elseif ($cpassaction=="cpass") {

//edit actions
include ("incs/uinfo.php");
$fname = "incs/uinfo.php";
$newpass=md5($newpass);
$content = "<?php \$adminuser=\"$adminuser\"; \$adminpass=\"$newpass\"; ?>";
if (file_exists($fname)) {
chmod($fname,0755);
$fp = fopen($fname,"w"); 
$fname = stripslashes($fname);
$content = stripslashes($content);
fwrite($fp,$content); 
fclose($fp);
} else {
echo"script error";
}
?>
<hr width="70%" align="center">
<div class="subadmintitle" align="left">Change Admin Pass :: Complete</div><br>
<div class="admintext" align="justify">
Congratultions you admin password should now have changed. If you should ever forget your password, do not fear. Using your FTP
client you can go to the incs folder, and there you will see a file called uinfo.php, open/download the file and you will see your username
 and password. If you find that you cannot download the file then re-upload the installer.php. You will not lose any data, it will just allow 
you to re-create the password file without having to login.<br><br>
<form method="POST" action="cpass.php">
<input type="hidden" value="form" name="cpassaction">
<input type="submit" value="Change Again?">
</form>
</div>
<?php

} else {
?>
<div align="center" class="scripterror">Script error - action not set to a valid value</div><br><br>
<?php
}
?>
</td>
        </tr>
      </table>

    </td>
  </tr>
</table>
<br><br><?php include("incs/footer.inc"); ?>
</body>
</html>


<?php
//end of module content
############################################################


} else {
?>
<html>
 <head>
  <title>You are not logged in.</title>
  <script language="Javascript">
   window.location="index.php"
  </script>
  <style>
<?php include("incs/eacss.inc"); ?>
  </style>
 </head>
 <body>
  <br>You are not logged in. Click<a href="index.php">here</a> to log in.<br><br>
<?php include("incs/footer.inc"); ?>
 </body>
</html>
<?php
}
?>
