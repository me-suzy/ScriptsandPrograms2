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
<title>Easy Admin :: Create A Directory Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Create Directory Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
//I appologise for the 'sloppy' code - it will be neatened up in later versions!
if (isset($HTTP_POST_VARS['cdiraction'])) {
$cdiraction=$HTTP_POST_VARS['cdiraction'];
}
if (isset($HTTP_POST_VARS['dname'])) {
$dname=$HTTP_POST_VARS['dname'];
}
if (!isset($cdiraction)) {
$cdiraction="0";
}
if (($cdiraction!="form")and($cdiraction!="verify")and($cdiraction!="cdir"))   {
$cdiraction="form";
}

if (($cdiraction=="verify")and(!$dname)) {
$cdiraction="form";
}

if (($cdiraction=="cdir")and(!$dname)) {
$cdiraction="form";
}

if (($cdiraction=="verify")and(is_dir($dname))) {
$cdiraction="form";
}

if (($cdiraction=="cdir")and(is_dir($dname))) {
$cdiraction="form";
}

if ($cdiraction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A Directory :: Enter Directory Name</div><br>
<div align="justify" class="admintext">
Please enter a directory that does not exist else you will see this page again! You also cannot
enter '0' as a directory name or leave it blank else you will see this page again. Great care 
has been taken in coding to make sure you cannot
write over existing directories. Just in case the safeguards fail you will be asked to confirm the 
directory name on the page which will appear next.
<br>
<form method="POST" action="cdir.php">
<div class="admintext">Enter Directory Name</div>
        <input type="text" value="" name="dname"><br>
<input type="hidden" value="verify" name="cdiraction"><br>
<input type="submit" value="Create">
</form>
</div>

<?php
} elseif ($cdiraction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A Directory :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the directory name <?php echo"$dname"; ?>
<form method="POST" action="cdir.php">
<input type="hidden" value="<?php echo"$dname"; ?>" name="dname">
<input type="hidden" value="cdir" name="cdiraction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="cdir.php">
<input type="hidden" value="form" name="cdiraction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($cdiraction=="cdir") {


//Create folder
mkdir ("$dname", 0755);


//Check that the folder has been created and send out corrosponding message
if (is_dir($dname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A Directory :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $dname has been created."; ?> <br><br>
<form method="POST" action="cdir.php">
<input type="hidden" value="form" name="cdiraction">
<input type="submit" value="Create Another Directory">
</form>
</div>

<?php
chmod($dname,0755);

} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Create A Directory :: Sorry</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $dname cannot be created by this script. Are you sure you have the correct permissions?"; ?><br><br>
<form method="POST" action="cdir.php">
<input type="hidden" value="form" name="cdiraction">
<input type="submit" value="Create Another Directory">
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
