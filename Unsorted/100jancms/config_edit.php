<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (
	'0' => 'ADMIN',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_add1'";
$result=mysql_query($query);
$articles_editor_add1=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_add2'";
$result=mysql_query($query);
$articles_editor_add2=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_edit1'";
$result=mysql_query($query);
$articles_editor_edit1=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_edit2'";
$result=mysql_query($query);
$articles_editor_edit2=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='encoding'";
$result=mysql_query($query);
$encoding=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_image_preview'";
$result=mysql_query($query);
$articles_editor_image_preview=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='app_url'";
$result=mysql_query($query);
$app_url=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_image_filesize'";
$result=mysql_query($query);
$articles_image_filesize=mysql_result($result,0,"config_value");



?>

<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed
}
</style>

<script type="text/javascript" src="checkform.js"></script>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.configform.app_url.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Admin:<span class="titletext0blue"> View/Edit Configuration</span></td>
  </tr>
</table>
<br>
<br>
<form action="config_edit_insert.php" method="post" name="configform" id="configform" onSubmit="return checkform(configform);">
  <span class="titletext0blue"> General configuration:</span><br>
<br>
  <strong>Application URL:</strong><br>
  <input name="app_url" type="text" class="formfields" id="app_url" size="75" maxlength="255" <?php echo 'value="'.$app_url.'"';?> alt="anything" emsg="Root URL">
  <br>
  <strong>Encoding meta tag:</strong><br>
  <input name="encoding" type="text" class="formfields" id="encoding2" size="75" maxlength="255"   <?php echo 'value=\''.$encoding.'\'';?> alt="anything" emsg="Encoding" title="Restriction:
- use double quotes">
  <br>
<br>
  <span class="titletext0blue">Articles configuration:</span><br>
<br>
  <strong>Add new article:</strong><br>
  <input name="add1" type="checkbox" id="add1" value="1" <?php if ($articles_editor_add1==1) {echo 'checked';}?>>
  Start WYSIWYG editor in html mode (Opening text)<br>
  <input name="add2" type="checkbox" id="add2" value="1"  <?php if ($articles_editor_add2==1) {echo 'checked';}?>>
  Start WYSIWYG editor in html mode (Full text)<br>
  <strong>Edit article:</strong><br>
  <input name="edit1" type="checkbox" id="edit1" value="1"  <?php if ($articles_editor_edit1==1) {echo 'checked';}?>>
  Start WYSIWYG editor in html mode (Opening text)<br>
  <input name="edit2" type="checkbox" id="edit2" value="1"  <?php if ($articles_editor_edit2==1) {echo 'checked';}?>>
  Start WYSIWYG editor in html mode (Full text)<br>
  <br>
  <strong>Article image max upload filesize:</strong><br>
  <input name="filesize" type="text" class="formfields" id="filesize" size="5" maxlength="255" <?php echo 'value="'.$articles_image_filesize.'"';?> alt="numeric" min="1" emsg="Article image max upload filesize">
  kb.<br>
  <strong>Internal image preview:</strong> <br>
  <select name="image_preview" class="formfields" id="image_preview" style="width:80px">
    <option value="full" <?php if ($articles_editor_image_preview=="full") {echo 'selected';}?>>Full</option>
    <option value="no" <?php if ($articles_editor_image_preview=="no") {echo 'selected';}?>>No</option>
<!--    <option value="thumbnail" < ? php if ($articles_editor_image_preview=="thumbnail") {echo 'selected';} ? >>Thumbnail</option> -->
  </select>
  <br>
  <br>
  <br>
  <br>
  <br>
  <input type="submit" name="submit" value="Save config -&gt;" style="width: 100px; height: 26px;" class="formfields2">
</form>

<br>
<br>
<br>

</body>
</html>
