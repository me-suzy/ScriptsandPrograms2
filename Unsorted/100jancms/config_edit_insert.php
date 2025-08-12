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

//receive posted data
$app_url=$_POST["app_url"];
$encoding=$_POST["encoding"];
$add1=$_POST["add1"];
$add2=$_POST["add2"];
$edit1=$_POST["edit1"];
$edit2=$_POST["edit2"];
$filesize=$_POST["filesize"];
$image_preview=$_POST["image_preview"];


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


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Admin:<span class="titletext0blue"> View/Edit Configuration</span></td>
  </tr>
</table>
<br>
<br>
<?php 
if ($add1==1) {$add1_save=1;} else {$add1_save=0;}
if ($add2==1) {$add2_save=1;} else {$add2_save=0;}
if ($edit1==1) {$edit1_save=1;} else {$edit1_save=0;}
if ($edit2==1) {$edit2_save=1;} else {$edit2_save=0;}

if (substr($app_url, -1)<>"/") {$app_url=$app_url."/";} else {$app_url=$app_url;}

		//save
		$query = "UPDATE ".$db_table_prefix."config SET config_value='$add1_save' WHERE config_name='articles_editor_add1'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='$add2_save' WHERE config_name='articles_editor_add2'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='$edit1_save' WHERE config_name='articles_editor_edit1'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='$edit2_save' WHERE config_name='articles_editor_edit2'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='".$encoding."' WHERE config_name='encoding'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='".$app_url."' WHERE config_name='app_url'";
		mysql_query($query);		
		$query = "UPDATE ".$db_table_prefix."config SET config_value='".$filesize."' WHERE config_name='articles_image_filesize'";
		mysql_query($query);
		$query = "UPDATE ".$db_table_prefix."config SET config_value='".$image_preview."' WHERE config_name='articles_editor_image_preview'";
		mysql_query($query);		
							
		
		//display message
		echo '<span class="maintext"><strong>Status:</strong> Configuration has been saved!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absbottom"><br>';
		//go back
		echo'<meta http-equiv="Refresh" content="3; url=config_edit.php">' ;
?>
</body>
</html>