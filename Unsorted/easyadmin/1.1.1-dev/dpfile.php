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
<title>Easy Admin :: Copy A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Copy File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">
<?php
if (isset($HTTP_POST_VARS['dpfileaction'])) {
$dpfileaction=$HTTP_POST_VARS['dpfileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (isset($HTTP_POST_VARS['cfname'])) {
$cfname=$HTTP_POST_VARS['cfname'];
}
if (!isset($dpfileaction)) {
$dpfileaction="0";
}
if (($dpfileaction!="form")and($dpfileaction!="verify")and($dpfileaction!="dpfile"))   {
$dpfileaction="form";
}

if (($dpfileaction=="verify")and(!$fname)) {
$dpfileaction="form";
}

if (($dpfileaction=="verify")and(!$cfname)) {
$dpfileaction="form";
}

if (($dpfileaction=="dpfile")and(!$fname)) {
$dpfileaction="form";
}

if (($dpfileaction=="dpfile")and(!$cfname)) {
$dpfileaction="form";
}

if (($dpfileaction=="verify")and(!file_exists($fname))) {
$dpfileaction="form";
}

if (($dpfileaction=="verify")and(file_exists($cfname))) {
$dpfileaction="form";
}

if (($dpfileaction=="dpfile")and(!file_exists($fname))) {
$dpfileaction="form";
}

if (($dpfileaction=="dpfile")and(file_exists($cfname))) {
$dpfileaction="form";
}

if ($dpfileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Copy A File :: Enter File Names</div><br>
<div align="justify" class="admintext">
Please enter a file to copy that exists and enter a name for the copy that doesn't exists else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. Great care 
has been taken in coding to make sure you cannot
write over existing files. Just in case the safeguards fail you will be asked to confirm the 
file names on the page which will appear next.<br>
Tip :: you can use ../ to use files in previous directories.
<br>
<form method="POST" action="dpfile.php">
<div class="admintext">Enter File Name To Copy</div>
        <input type="text" value="" name="fname"><br>
<div class="admintext">Enter File Name (with place) For Copy</div>
        <input type="text" value="" name="cfname"><br>
<input type="hidden" value="verify" name="dpfileaction"><br>
<input type="submit" value="Copy">
</form>
</div>

<?php
} elseif ($dpfileaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Copy A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?> to copy!<br>
<div class="admintext" align="left">You have entered the file name <?php echo"$cfname"; ?> for the copy!<br>
<form method="POST" action="dpfile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="<?php echo"$cfname"; ?>" name="cfname">
<input type="hidden" value="dpfile" name="dpfileaction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="dpfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($dpfileaction=="dpfile") {


//Copyfile
copy ("$fname", "$cfname");


//Check that the new file has been created and send out corrosponding message
if (file_exists($cfname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Copy A File :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been copied to $cfname."; ?> Click <a href="<?php echo "$cfname"; ?>">here</a> to go to your file. Please note
that if this file is below your root directory you will probably get a 404 error. <br><br>
<form method="POST" action="dpfile.php">
<input type="hidden" value="form" name="dpfileaction">
<input type="submit" value="Copy Another File">
</form>
</div>

<?php


} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Copy A File :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $fname cannot be copied to $cfname by this script. Are you sure you have set the correct permissions?"; ?><br><br>
<form method="POST" action="dpfile.php">
<input type="hidden" value="form" name="dpfileaction">
<input type="submit" value="Copy Another File">
</form>
</div>
<?php

}

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
