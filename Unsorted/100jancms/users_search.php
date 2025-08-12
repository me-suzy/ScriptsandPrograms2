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
fixed
}
</style>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.searchform.full_name.focus()" class="maintext">
<?php 
//kill old session
unset($_SESSION["search_query_users"]);
?>
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Users: View/Edit Users: <span class="titletext0blue">Search Users</span></td>
  </tr>
</table>
<br>
<br>
<form name="searchform" action="users_list.php" method="post">
  <span class="maintext"><strong>Full name:</strong></span> <br>
  <input name="full_name" type="text" class="formfields" id="full_name" size="30" maxlength="255">
  <span class="maintext"><br>
  <strong>Username:</strong></span> <br>
  <input name="username" type="text" class="formfields" id="username" size="30" maxlength="255">
  <span class="maintext"><br>
  <br>
  </span><span class="maintext"> Show 
  <select name="pagelimit" class="formfields" id="pagelimit">
    <option value="5">5</option>
    <option value="10">10</option>
    <option value="20" selected>20</option>
    <option value="30">30</option>
    <option value="40">40</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="200">200</option>
    <option value="300">300</option>
    <option value="500">500</option>
    <option value="1000">1000</option>	
  </select>
  results per page.</span>
  <br>
  <br>
  <br>
  <input type="submit" name="submit" value="Search users -&gt;" style="width: 100px; height: 26px;" class="formfields2">
</form>

<br>
<br>
<br>

</body>
</html>
