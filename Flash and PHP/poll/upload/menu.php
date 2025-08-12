<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #D0DCE0;
}
.style1 {font-size: 12px}
-->
</style></head>

<body>

<?php 
if (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])) {
?>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>FppS</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;  <span class="style1">&nbsp;Version 1.2.1</span></p>
<p>&nbsp;</p>
<hr noshade>
<table width="176" border="0">
  <tr>
    <td width="47"><img src="graph03.gif" width="32" height="32"></td>
    <td width="119"><a href="npoll.php" target="mainFrame" class="style1">New Poll</a></td>
  </tr>
  <tr>
    <td><img src="contents.gif" width="32" height="32"></td>
    <td><a href="lpoll.php" target="mainFrame" class="style1">Poll List</a> </td>
  </tr>
  <tr>
    <td><img src="settings.jpg" width="32" height="35"></td>
    <td class="style1"><a href="settings.php" target="mainFrame">Settings</a></td>
  </tr>
</table>

<hr noshade>
<?php   
}
else { echo "You are not authorized to view this page";};
?>


</body>
</html>
