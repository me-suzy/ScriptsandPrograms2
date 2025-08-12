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
fixed;
}
</style>

<script type="text/javascript" src="checkform.js"></script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" onload="document.addform.marker.focus()">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles:<span class="titletext0blue"> Add new Marker</span></td>
  </tr>
</table>
<br>
<br>

<form action="articles_marker_insert.php" method=post enctype="multipart/form-data" name="addform" onSubmit="return checkform(addform);">
<input name="action" type="hidden" value="new">
  <strong>Marker:</strong><br>
  <input name="marker" type="text" class="formfields" id="marker" size="40" maxlength="255" title="Restriction:
- quotes are not allowed
- spaces are not allowed
- use underscore '_' instead of space"
alt="anything" emsg="Marker">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
  <strong>Comment:</strong><br>
  <textarea name="comment" class="formfields" id="comment" style="width:229; height:120"></textarea>
  
    <br>
  <br>
  <br>
  <br>
  <input type="submit" name="submit" value="Add marker ->" style="width: 90px; height: 26px;" class="formfields2">
</form>

<br>
<br>
<br>

</body>
</html>
