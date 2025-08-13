<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Setup - Upgrade - Step 2</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/style.css" title="default" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top"><hr width="1" size="1" color="#FFFFFF">
<table class="admin_main" align="center" border="1">
<tr>
<td class="admin_title"><table class="admin_title_table">
<tr>
<td class="large admin_title">Olate Download - Download Management Script</td>
</tr>
</table></td>
</tr>
<tr>
<td class="admin_breadcrumb">
<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="100%"><strong>Setup - Upgrade - Step 2 </strong></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p>During this step, you will be asked to supply your database details and script URL.</p>
<p><strong>Database Details </strong></p>
<p>Please enter your database details below. If you do not know these details, contact your web host. The upgrader will not create the database for you so ensure that it exists.</p>
<form action="upgrade3.php" method="post" name="database" id="database">
<table width="360" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="168">Database Servername:</td>
<td width="190"><input name="db_servername" type="text" id="db_servername" value="localhost" size="20"></td>
</tr>
<tr>
<td>Database Username: </td>
<td><input name="db_username" type="text" id="db_username" size="20"></td>
</tr>
<tr>
<td>Database Password: </td>
<td><input name="db_password" type="password" id="db_password" size="20"></td>
</tr>
<tr>
<td>Database Name: </td>
<td><input name="db_name" type="text" id="db_name" size="20"></td>
</tr>
</table>
<p><strong>Script URL </strong></p>
<p>You need to specify the URL that this script will be availabler at. The upgrader has automatically detected the current URL which has been entered in the field below. Verify this URL is correct and alter if necessary.</p>
<table width="500" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="100">Script URL :</td>
<td width="260"><input name="urlpath" type="text" id="urlpath" value="<?
$path = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
echo substr($path, 0, strrpos($path, '/install'));
?>" size="60"></td>
</tr>
</table>
<p>Do not include a trailing slash. </p>
<p>Please verify that the details you have entered are correct and then select Continue below. Your existing database tables will be updated. </p>
<p>
<input type="submit" name="Submit" value="Continue"> 
</p>
</form>
</td>
</tr>
</table></td>
</tr>
<tr>
<td height="25" valign="middle" bordercolor="#FFFFFF" bgcolor="#E3E8EF">
<!--Begin Credit Line. Please leave-->
<div align="center"><span class="small"><a href="http://www.olate.com" target="_blank">Powered 
by Olate Download v2.2.0 </a></span></div></td>
</tr>
</table>
<hr width="1" size="1" color="#FFFFFF"></td>
</tr>
</table>
</body>
</html>
