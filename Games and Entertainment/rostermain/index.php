<?
require "config.php";
?>
<html>
<head>

<link rel="stylesheet" type"text/css" href="standard.css" />

<title>rostermain</title>
</head>
<BODY>
<table align="center" width="950" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<?
if ($_POST['userid'] && $_POST['password'])
{
  // if the user has just tried to log in
  $logquery = "select * from users "
           ."where username='$userid' "
           ." and passwd='$password' ";
  $logresult = mysql_query($logquery, $db_conn);
  $row = mysql_fetch_assoc($logresult);
  if (mysql_num_rows($logresult) >0 && $row['approved'] == 1)
  {
    // if they are in the database register the user id
    $valid_user = $userid;
    $_SESSION['valid_user'] = $valid_user;
    $_SESSION['pass'] = $_POST['password'];
	$_SESSION['id'] = $row['id'];
	$_SESSION['approved'] = $row['approved'];
	$_SESSION['officer'] = $row['officer'];
	$_SESSION['admin'] = $row['admin'];
	$_SESSION['rank'] = $row['rank'];
  }
}
if ($_SESSION['admin']=='1')
{
	include "siteadmin/admenu.php";
}
?>
<table align="left" width="160" cellpadding="0" cellspacing="0" border="0">
<tr>
<td height="220" width="160">
<?
if ($_GET['log']=='') include 'authmain.php';
if ($_GET['log']=='logout') include 'logout.php';
if ($_GET['log']=='change') include 'changepass.php';
if ($_GET['log']=='1') include 'changepass.php';
if ($_GET['log']=='forgot') include 'forgot.php';
?>
</td>
</tr>
<tr>
<td valign="top" height="300">
<?
require "menu.php";
?>
</td>
</tr>
<tr>
<td>
</td>
</tr>
</table>



<table align="center" border="0" cellspacing="5" cellpadding="5">
<tr>
<td align="center">
</td>
</tr>
</table>
<table  bgcolor="#000000" width="750" cellpadding="5" cellspacing="5">
<tr>
<td class="log" align="center">
</td>
</tr>
<tr>
<td class="content" align="left" valign="top">
<br /><br />	
<?
include "content.php";
?>			
</td>
</tr>
<tr>
<td align="center">
<a href="http://www.irealms.co.uk">By Ryan Marshall of www.irealms.co.uk</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</BODY>
</HTML>