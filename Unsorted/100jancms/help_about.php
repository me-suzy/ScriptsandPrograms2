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
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='version'";
$result=mysql_query($query);
$version=mysql_result($result,0,"config_value");


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


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Help:<span class="titletext0blue"> About</span></td>
  </tr>
</table>
<br>
<br>
<span class="titletext0">100janCMS </span><span class="titletext0blue">Articles 
Control</span><span class="maintext"><strong><br>
</strong><br>
<br>
version <?php echo "$version";?><br>
<br>
This product is licensed under the terms of<br>
<a href="help_eula.php">End User License Agreement</a>.<br>
<br>
Copyright &copy; 2004 100jan Design Studio.</span><span class="maintext"> All 
Rights Reserved.</span><span class="maintext"><br>
Nullified by GTT '2004</span> 
</body>
</html>
