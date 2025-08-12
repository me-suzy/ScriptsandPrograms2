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
<title>Easy Admin :: Rename A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Rename File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
if (isset($HTTP_POST_VARS['rnfileaction'])) {
$rnfileaction=$HTTP_POST_VARS['rnfileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (isset($HTTP_POST_VARS['cfname'])) {
$cfname=$HTTP_POST_VARS['cfname'];
}
if (!isset($rnfileaction)) {
$rnfileaction="0";
}
if (($rnfileaction!="form")and($rnfileaction!="verify")and($rnfileaction!="rnfile"))   {
$rnfileaction="form";
}

if (($rnfileaction=="verify")and(!$fname)) {
$rnfileaction="form";
}

if (($rnfileaction=="verify")and(!$cfname)) {
$rnfileaction="form";
}

if (($rnfileaction=="rnfile")and(!$fname)) {
$rnfileaction="form";
}

if (($rnfileaction=="rnfile")and(!$cfname)) {
$rnfileaction="form";
}

if (($rnfileaction=="verify")and(!file_exists($fname))) {
$rnfileaction="form";
}

if (($rnfileaction=="verify")and(file_exists($cfname))) {
$rnfileaction="form";
}

if (($rnfileaction=="rnfile")and(!file_exists($fname))) {
$rnfileaction="form";
}

if (($rnfileaction=="rnfile")and(file_exists($cfname))) {
$rnfileaction="form";
}

if ($rnfileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Rename A File :: Enter File Names</div><br>
<div align="justify" class="admintext">
Please enter a file to rename that exists and enter a new name that doesn't exist else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. Great care 
has been taken in coding to make sure you cannot
write over existing files. Just in case the safeguards fail you will be asked to confirm the 
file names on the page which will appear next.<br>
Tip :: you can use ../ to use files in previous directories.
<br>
<form method="POST" action="rnfile.php">
<div class="admintext">Enter File Name</div>
        <input type="text" value="" name="fname"><br>
<div class="admintext">Enter New File Name</div>
        <input type="text" value="" name="cfname"><br>
<input type="hidden" value="verify" name="rnfileaction"><br>
<input type="submit" value="Rename">
</form>
</div>

<?php
} elseif ($rnfileaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Rename A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?> to be renamed to  <?php echo"$cfname"; ?> .<br>
<form method="POST" action="rnfile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="<?php echo"$cfname"; ?>" name="cfname">
<input type="hidden" value="rnfile" name="rnfileaction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="rnfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($rnfileaction=="rnfile") {


//Copyfile
rename ("$fname", "$cfname");


//Check that the new file has been created and send out corrosponding message
if (file_exists($cfname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Rename A File :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been renamed to $cfname."; ?> Click <a href="<?php echo "$cfname"; ?>">here</a> to go to your file. Please note
that if this file is below your root directory you will probably get a 404 error. <br><br>
<form method="POST" action="rnfile.php">
<input type="hidden" value="form" name="rnfileaction">
<input type="submit" value="Copy Another File">
</form>
</div>

<?php


} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Rename A File :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $fname cannot be renamed to $cfname by this script. Are you sure you have set the correct permissions?"; ?><br><br>
<form method="POST" action="rnfile.php">
<input type="hidden" value="form" name="rnfileaction">
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
