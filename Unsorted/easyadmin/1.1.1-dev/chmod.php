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
<title>Easy Admin :: CHMOD A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">CHMOD File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
if (isset($HTTP_POST_VARS['chmodaction'])) {
$chmodaction=$HTTP_POST_VARS['chmodaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (isset($HTTP_POST_VARS['mod'])) {
$mod=$HTTP_POST_VARS['mod'];
}
if (!isset($chmodaction)) {
$chmodaction="0";
}
if (($chmodaction!="form")and($chmodaction!="verify")and($chmodaction!="chmod"))   {
$chmodaction="form";
}

if (($chmodaction=="verify")and(!$fname)) {
$chmodaction="form";
}

if (($chmodaction=="chmod")and(!$fname)) {
$chmodaction="form";
}

if (($chmodaction=="verify")and(!file_exists($fname))) {
$chmodaction="form";
}

if (($chmodaction=="chmod")and(!file_exists($fname))) {
$chmodaction="form";
}

if ($chmodaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">CHMOD A File :: Enter File Name</div><br>
<div align="justify" class="admintext">
Please enter a file that exists else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. Just as a
safeguard you will be asked to confirm the file name on the page which will appear next.
Please note if you enter no chmod value the default is 0755.
<br>
<form method="POST" action="chmod.php">
<div class="admintext">Enter File Name</div>
        <input type="text" value="" name="fname"><br>
<div class="admintext">Enter CHMOD value</div>
        <input type="text" value="0755" name="mod"><br>
<input type="hidden" value="verify" name="chmodaction"><br>
<input type="submit" value="CHMOD">
</form>
</div>

<?php
} elseif ($chmodaction=="verify") {

if (!$mod) {
$mod="0755";
}
?>
<hr width="70%" align="center">
<div class="subadmintitle" align="left">CHMOD A File :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the file name <?php echo"$fname"; ?><br>
<div class="admintext" align="left">You have entered the CHMOD value <?php echo"$mod"; ?><br>
<form method="POST" action="chmod.php">
<input type="hidden" value="<?php echo"$mod"; ?>" name="mod">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="chmod" name="chmodaction">
<input type="submit" value="Continue to CHMOD?">
</form>
<form method="POST" action="chmod.php">
<input type="hidden" value="form" name="chmodaction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($chmodaction=="chmod") {

//chmod file
if (!$mod) {
$mod="0755";
}
chmod($fname,$mod);

//output congrats message
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">CHMOD A File :: Complete</div><br>
<div class="admintext" align="justify">The script should now have hopefully CHMOD the file. Note this is for
*nix servers.<br>
<form method="POST" action="chmod.php">
<input type="hidden" value="form" name="chmodaction"><br>
<input type="submit" value="CHMOD Another?">
</form>


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
