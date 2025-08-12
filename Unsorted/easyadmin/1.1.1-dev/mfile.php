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
<title>Easy Admin :: Move A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Move File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">
<?php
if (isset($HTTP_POST_VARS['mfileaction'])) {
$mfileaction=$HTTP_POST_VARS['mfileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (isset($HTTP_POST_VARS['cfname'])) {
$cfname=$HTTP_POST_VARS['cfname'];
}
if (!isset($mfileaction)) {
$mfileaction="0";
}
if (($mfileaction!="form")and($mfileaction!="verify")and($mfileaction!="mfile"))   {
$mfileaction="form";
}

if (($mfileaction=="verify")and(!$fname)) {
$mfileaction="form";
}

if (($mfileaction=="verify")and(!$cfname)) {
$mfileaction="form";
}

if (($mfileaction=="mfile")and(!$fname)) {
$mfileaction="form";
}

if (($mfileaction=="mfile")and(!$cfname)) {
$mfileaction="form";
}

if (($mfileaction=="verify")and(!file_exists($fname))) {
$mfileaction="form";
}

if (($mfileaction=="verify")and(file_exists($cfname))) {
$mfileaction="form";
}

if (($mfileaction=="mfile")and(!file_exists($fname))) {
$mfileaction="form";
}

if (($mfileaction=="mfile")and(file_exists($cfname))) {
$mfileaction="form";
}

if ($mfileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Move A File :: Enter File Names</div><br>
<div align="justify" class="admintext">
Please enter a file to move that exists and enter a name for the new file that doesn't exists else you will see this page again! 
Look familiar?? If you havent noticed this is a shortcut. This is a modified version of the copy file module. This simply delete's the
original after copying.
You also cannot enter '0' as a file name or leave it blank else you will see this page again. 
Great care has been taken in coding to make sure you cannot
write over existing files. Just in case the safeguards fail you will be asked to confirm the 
file names on the page which will appear next.<br>
Tip :: you can use ../ to use files in previous directories.
<br>
<form method="POST" action="mfile.php">
<div class="admintext">Enter File Name To Move</div>
        <input type="text" value="" name="fname"><br>
<div class="admintext">Enter New Location With New Name</div>
        <input type="text" value="" name="cfname"><br>
<input type="hidden" value="verify" name="mfileaction"><br>
<input type="submit" value="Move">
</form>
</div>

<?php
} elseif ($mfileaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Move A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?> to move<br>
<div class="admintext" align="left">You have entered the file name <?php echo"$cfname"; ?> for the file to move to.<br>
<form method="POST" action="mfile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="<?php echo"$cfname"; ?>" name="cfname">
<input type="hidden" value="mfile" name="mfileaction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="mfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($mfileaction=="mfile") {


//Copyfile
copy("$fname", "$cfname");
if (file_exists($cfname)) {

unlink("$fname");

}


//Check that the new file has been created and send out corrosponding message
if (file_exists($cfname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Move A File :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been copied to $cfname."; ?> Click <a href="<?php echo "$cfname"; ?>">here</a> to go to your file. Please note
that if this file is below your root directory you will probably get a 404 error. <br><br>
<form method="POST" action="mfile.php">
<input type="hidden" value="form" name="mfileaction">
<input type="submit" value="Move Another File">
</form>
</div>

<?php


} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Move A File :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $fname cannot be moved to $cfname by this script. Are you sure you have set the correct permissions?<br><br>"; 

if (file_exists($cfname)) {
?>
The file has been copied.<br>
<?php
} else {
?>
The file has not been copied.<br>
<?php
}

if (!file_exists($fname)) {
?>
The original has been deleted.<br>
<?php
} else {
?>
The original can not been deleted.<br>
<?php
}

?>
<form method="POST" action="mfile.php">
<input type="hidden" value="form" name="mfileaction">
<input type="submit" value="Move Another File">
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
