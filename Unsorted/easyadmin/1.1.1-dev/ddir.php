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
<title>Easy Admin :: Delete A Directory Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Delete Directory Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
if (isset($HTTP_POST_VARS['ddiraction'])) {
$ddiraction=$HTTP_POST_VARS['ddiraction'];
}
if (isset($HTTP_POST_VARS['dname'])) {
$dname=$HTTP_POST_VARS['dname'];
}
if (!isset($ddiraction)) {
$ddiraction="0";
}
if (($ddiraction!="form")and($ddiraction!="verify")and($ddiraction!="ddir"))   {
$ddiraction="form";
}

if (($ddiraction=="verify")and(!$dname)) {
$ddiraction="form";
}

if (($ddiraction=="ddir")and(!$dname)) {
$ddiraction="form";
}

if (($ddiraction=="verify")and(!is_dir($dname))) {
$ddiraction="form";
}

if (($ddiraction=="ddir")and(!is_dir($dname))) {
$ddiraction="form";
}

if ($ddiraction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A Directory :: Enter Directory Name</div><br>
<div align="justify" class="admintext">
Please enter a directory that exists else you will see this page again! You also cannot
enter '0' as a directory name or leave it blank else you will see this page again. Great care 
has been taken in coding to make sure you cannot
accidently deleting directories. You will be asked to confirm the 
directory name on the page which will appear next.
<br>
<form method="POST" action="ddir.php">
<div class="admintext">Enter Directory Name</div>
        <input type="text" value="" name="dname"><br>
<input type="hidden" value="verify" name="ddiraction"><br>
<input type="submit" value="Delete">
</form>
</div>

<?php
} elseif ($ddiraction=="verify") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A Directory :: Please Verify</div><br>
<div class="admintext" align="left">You have entered the directory name <?php echo"$dname"; ?>
<form method="POST" action="ddir.php">
<input type="hidden" value="<?php echo"$dname"; ?>" name="dname">
<input type="hidden" value="ddir" name="ddiraction">
<input type="submit" value="Continue?">
</form>
<form method="POST" action="ddir.php">
<input type="hidden" value="form" name="ddiraction">
<input type="submit" value="Go Back?">
</form>
</div>

<?php
} elseif ($ddiraction=="ddir") {


//Delete folder
rmdir ("$dname");


//Check that the folder has been deleted and send out corrosponding message
if (!is_dir($dname)) {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A Directory :: Congratulations</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $dname has been deleted."; ?> <br><br>
<form method="POST" action="ddir.php">
<input type="hidden" value="form" name="ddiraction">
<input type="submit" value="Delete Another Directory">
</form>
</div>

<?php

} else {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Delete A Directory :: Cannot Tell</div><br>
<div class="admintext" align="justify">
<?php echo "Sorry but $dname either cannot be deleted by this script or the script cannot tell whether it has been deleted. 
Are you sure you have the correct permissions? Please check to see if the dir exists. If not try again before checking permissions"; ?><br>
One Way to check would be to try and delete it again, if the main form reloads it means the folder doesnt exist.
<br>
<form method="POST" action="ddir.php">
<input type="hidden" value="form" name="ddiraction">
<input type="submit" value="Delete Another Directory">
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
