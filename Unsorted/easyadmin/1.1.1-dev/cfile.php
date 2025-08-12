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
<title>Easy Admin :: Create A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Create File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">
<?php
if (isset($HTTP_POST_VARS['cfileaction'])) {
$cfileaction=$HTTP_POST_VARS['cfileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (!isset($cfileaction)) {
$cfileaction="0";
}
if (($cfileaction!="form")and($cfileaction!="verify")and($cfileaction!="cfile"))   {
$cfileaction="form";
}

if (($cfileaction=="verify")and(!$fname)) {
$cfileaction="form";
}

if (($cfileaction=="cfile")and(!$fname)) {
$cfileaction="form";
}

if (($cfileaction=="verify")and(file_exists($fname))) {
$cfileaction="form";
}

if (($cfileaction=="cfile")and(file_exists($fname))) {
$cfileaction="form";
}

if ($cfileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A File :: Enter File Name</div><br>
<div align="justify" class="admintext">
Please enter a file that does not exist else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. Great care 
has been taken in coding to make sure you cannot
write over existing files. Just in case the safeguards fail you will be asked to confirm the 
file name on the page which will appear next. Also you will get a file could not be created error
if you try to create a file in a non-existent folder.
<br>
<form method="POST" action="cfile.php">
<div class="admintext">Enter File Name</div>
        <input type="text" value="" name="fname"><br>
<input type="hidden" value="verify" name="cfileaction"><br>
<input type="submit" value="Create">
</form>
</div>

<?php
} elseif ($cfileaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?>
<form method="POST" action="cfile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="cfile" name="cfileaction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="cfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($cfileaction=="cfile") {

//This is in here for future purpose - so module can be edited for file content to be added first
if (!isset($fc)) {
$fc="File Created By Easy Admin";
} else {
$fc= stripslashes($fc);
}

//Create file and add initial content
$fp = fopen($fname,"w"); 
fwrite($fp,$fc); 
fclose($fp);

//Check that the file has been created and send out corrosponding message
if (file_exists($fname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A File :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been created."; ?> You may now go to your file! Please note
that if this file is below your root directory you will probably get a 404 error. <br><br>
<form method="POST" action="cfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Create Another File">
</form>
</div>

<?php
chmod($fname,0755);

} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A File :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $fname cannot be created by this script. Are you sure you have set the correct permissions?"; ?><br><br>
<form method="POST" action="cfile.php">
<input type="hidden" value="form" name="cfileaction">
<input type="submit" value="Create Another File">
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
