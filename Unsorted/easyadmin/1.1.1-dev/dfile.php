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
<title>Easy Admin :: Delete A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Delete File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
if (isset($HTTP_POST_VARS['dfileaction'])) {
$dfileaction=$HTTP_POST_VARS['dfileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (!isset($dfileaction)) {
$dfileaction="0";
}
if (($dfileaction!="form")and($dfileaction!="verify")and($dfileaction!="dfile"))   {
$dfileaction="form";
}

if (($dfileaction=="verify")and(!$fname)) {
$dfileaction="form";
}

if (($dfileaction=="dfile")and(!$fname)) {
$dfileaction="form";
}

if (($dfileaction=="verify")and(!file_exists($fname))) {
$dfileaction="form";
}

if (($dfileaction=="dfile")and(!file_exists($fname))) {
$dfileaction="form";
}

if ($dfileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A File :: Enter File Name</div><br>
<div align="justify" class="admintext">
Please enter a file that exists else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. Just as a
safeguard you will be asked to confirm the file name on the page which will appear next.
<br>
<form method="POST" action="dfile.php">
<div class="admintext">Enter File Name</div>
        <input type="text" value="" name="fname"><br>
<input type="hidden" value="verify" name="dfileaction"><br>
<input type="submit" value="Delete">
</form>
</div>

<?php
} elseif ($dfileaction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?>
<form method="POST" action="dfile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="dfile" name="dfileaction">
<input type="submit" value="Continue to Delete?">
</form>
<form method="POST" action="dfile.php">
<input type="hidden" value="form" name="dfileaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($dfileaction=="dfile") {

//delete file
chmod($fname,0755);
unlink("$fname");

//Check that the file has been created and send out corrosponding message
if (file_exists($fname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A File :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $fname cannot be deleted by this script. Are you sure you have set the correct permissions?"; ?><br><br>
<form method="POST" action="dfile.php">
<input type="hidden" value="form" name="dfileaction">
<input type="submit" value="Delete Another File">
</form>
</div>

<?php

} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A File :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been deleted."; ?><br><br>
<form method="POST" action="dfile.php">
<input type="hidden" value="form" name="dfileaction">
<input type="submit" value="Delete Another File">
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
