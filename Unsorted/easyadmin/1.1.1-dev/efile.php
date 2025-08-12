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
<title>Easy Admin :: Edit A File Module</title>
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
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Edit File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">
<?php
if (isset($HTTP_POST_VARS['efileaction'])) {
$efileaction=$HTTP_POST_VARS['efileaction'];
}
if (isset($HTTP_POST_VARS['fname'])) {
$fname=$HTTP_POST_VARS['fname'];
}
if (isset($HTTP_GET_VARS['efileaction'])) {
$efileaction=$HTTP_GET_VARS['efileaction'];
}
if (isset($HTTP_GET_VARS['fname'])) {
$fname=$HTTP_GET_VARS['fname'];
}
if (isset($HTTP_POST_VARS['content'])) {
$content=$HTTP_POST_VARS['content'];
}
if ((isset($fname))and($fname=="efile.php")){
$efileaction="0";
}
if (!isset($efileaction)) {
$efileaction="0";
}
if (($efileaction!="form")and($efileaction!="edit")and($efileaction!="efile"))   {
$efileaction="form";
}

if (($efileaction=="edit")and(!$fname)) {
$efileaction="form";
}

if (($efileaction=="efile")and(!$fname)) {
$efileaction="form";
}

if (($efileaction=="edit")and(!file_exists($fname))) {
$efileaction="form";
}

if (($efileaction=="efile")and(!file_exists($fname))) {
$efileaction="form";
}

if ($efileaction=="form") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Edit A File :: Enter File Name</div><br>
<div align="justify" class="admintext">
Please enter a file that exists else you will see this page again! You also cannot
enter '0' as a file name or leave it blank else you will see this page again. The editor will
appear on the next page, please note there is no undo, verify screen then check to see
if the edit has worked. To find out you should load the 'edited' file.
<br>
<form method="POST" action="efile.php">
<div class="admintext">Enter File Name</div>
        <input type="text" value="" name="fname"><br>
<input type="hidden" value="edit" name="efileaction"><br>
<input type="submit" value="Edit File">
</form>
</div>

<?php
} elseif ($efileaction=="edit") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Edit A File :: Do Your Editing</div><br>
<div class="admintext" align="left">You editing <?php echo"$fname"; ?>
<form method="POST" action="efile.php">
<textarea name="content" rows="12" cols="45">
<?php

if (file_exists($fname)) {
$fp = fopen($fname,"r");
$content = fread($fp,filesize($fname));
fclose($fp);
echo "$content";
 }
else {
echo "File specified does not exist! or There has been a script error";
 }


?>
</textarea>
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="efile" name="efileaction"><br>
<input type="submit" value="Save Changes">
</form>
<form method="POST" action="efile.php">
<input type="hidden" value="<?php echo"$fname"; ?>" name="fname">
<input type="hidden" value="edit" name="efileaction"><br>
<input type="submit" value="Reload (dont save)">
</form>

</div>

<?php
} elseif ($efileaction=="efile") {

//edit actions
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
<div class="subadmintitle" align="left">Edit A File :: Complete</div><br>
<div class="admintext" align="justify">
<?php echo "Congratulations $fname has been edited."; ?> Go to your page to verify changes has been made
remember to refresh the page as some browsers cache pages and dont look for changes everyday. 
Please note that if this file is below your root directory or is  you will probably get a 404 error and if you do not have the correct permissions
then the file will not have changed. <br><br>
<form method="POST" action="efile.php">
<input type="hidden" value="form" name="efileaction">
<input type="submit" value="Edit Another File">
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
