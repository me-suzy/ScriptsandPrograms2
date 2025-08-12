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
<title>Easy Admin :: Upload A File Module</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
<?php include("incs/eacss.inc"); ?>
</style>
</head>
<body bgcolor="#FFFFFF" text="#333333">
<table width="601" border="0" cellspacing="0" cellpadding="0" height="287">
  <tr>
    <td height="67" width="110" align="center" valign="middle"> </td>
    <td height="67" width="491" align="center" valign="middle" class="admintitle"><img src="incs/eallgo.jpg" height="43"></td>
  </tr>
  <tr>
    <td width="110" valign="top" align="left"><?php include("incs/module-list.inc"); ?></td>
    <td width="491" align="center" valign="top"> 
<br>
      <table width="90%" border="0" cellspacing="0" cellpadding="0" height="100%">
        <tr> 
          <td align="left" valign="top" height="25" class="admintitle" width="50%">Upload File Module</td>
          <td align="right" valign="top" height="25" class="subadmintitle" width="50%">by Matthew Randles</td>
        </tr>
        <tr> 
          <td align="left" valign="top" colspan="2">

<?php
if (isset($HTTP_POST_VARS['ufileaction'])) {
$ufileaction=$HTTP_POST_VARS['ufileaction'];
}
if (isset($HTTP_POST_VARS['absolute_directory'])) {
$absolute_directory=$HTTP_POST_VARS['absolute_directory'];
}
if (isset($HTTP_POST_FILES['file']['name'])) {
$file_name=$HTTP_POST_FILES['file']['name'];
}
if (isset($HTTP_POST_FILES['file']['size'])) {
$file_size=$HTTP_POST_FILES['file']['size'];
}
if (isset($HTTP_POST_FILES['file']['tmp_name'])) {
$file=$HTTP_POST_FILES['file']['tmp_name'];
}
if(!isset($ufileaction)) {
$ufileaction = "enterdirform";
}
if (($ufileaction=="enterfileform")and(!$absolute_directory)) {
$ufileaction = "enterdirform";
} else {
if (($ufileaction=="enterfileform")and(!is_dir ("$absolute_directory"))) {
$ufileaction = "enterdirform";
}
}

if (($ufileaction=="uploadfile")and(!$file_name)) {
$ufileaction = "enterdirform";
} else {
if (($ufileaction=="uploadfile")and(file_exists("$absolute_directory/$file_name"))) {
$ufileaction = "enterdirform";
}
}

if ($ufileaction=="enterdirform") {
?>

<hr width="70%" align="center">
<div class="subadmintitle" align="left">Upload A File :: Enter Directory Name</div><br>
<div align="justify" class="admintext">
Please enter an existing directory else you will see this page again! You cannot enter a dir with spaces in the name
due to possible errors. The next page will ask you which file to upload then away you go! Please note - trying to upload a file
with the same name as an existing file in your specified directory will result in no upload.
Tip :: to upload to this dir enter ./
<br>
<form method="POST" action="ufile.php" enctype="multipart/form-data">
<div class="admintext">Enter Directory</div>
        <input type="text" value="" name="absolute_directory"><br>
<input type="hidden" value="enterfileform" name="ufileaction"><br>
<input type="submit" value="Continue">
</form>
</div>

<?php
} elseif ($ufileaction=="enterfileform") {
?>


<hr width="70%" align="center">
<div class="subadmintitle" align="left">Upload A File :: Upload File</div><br>
<div align="justify" class="admintext">
Please enter a file with no spaces in the name and one that does not match the name of an existing file in the directory chosen. Else you will see the first page again! <br>
You are uploading to the dir :: <?php echo"$absolute_directory"; ?>
<br>
<form method="POST" action="ufile.php" enctype="multipart/form-data">
<div class="admintext">Upload File</div>
        <input type=file name=file size=30><br>
<input type="hidden" value="uploadfile" name="ufileaction"><br>
<input type="hidden" value="<?php echo"$absolute_directory"; ?>" name="absolute_directory"><br>
<input type="submit" value="Upload">
</form>
</div>



<?php
} elseif ($ufileaction=="uploadfile") {
?>


<hr width="70%" align="center">
<div class="subadmintitle" align="left">Upload A File :: Report</div><br>
<div align="justify" class="admintext">
Here is a short report on how successfull the script was in uploading your file!<br>
You were uploading to the dir :: <?php echo"$absolute_directory"; ?><br>
You were uploading the file :: <?php echo"$file_name"; ?>
<br>
<?php
$size_limit = "yes";
$limit_size = "2621440";
$endresult = "File Was Uploaded";
if ($file_name == "") {
$endresult = "No file selected";
}else{
if(file_exists("$absolute_directory/$file_name")) {
$endresult = "File Already Existed";
} else {
if (($size_limit == "yes") && ($limit_size < $file_size)) {
$endresult = "File was to big";
} else {
$ext = strrchr($file_name,'.');
@copy($file, "$absolute_directory/$file_name") or $endresult = "Couldn't Copy File To Server";
}
}
}
echo "$endresult ";
?>
<br>
<form method="POST" action="ufile.php" enctype="multipart/form-data">
<input type="hidden" value="enterdirform" name="ufileaction"><br>
<input type="submit" value="Another?">
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
