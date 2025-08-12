<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                   install/index.php file                     */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
$url = $HTTP_SERVER_VARS['HTTP_HOST'];
$script_path = (!empty($HTTP_POST_VARS['script_path'])) ? $HTTP_POST_VARS['script_path'] : str_replace('install', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
?>
<html>
<head>
<title>PHPHG Guestbook Installation</title>
<link rel="stylesheet" href="../templates/default/default.css" type="text/css">
</head>
<body>
<table class="bodyline" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center">
<table border="0" cellsapcing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><a href="../index.php"><img src="../templates/default/images/logo.jpg" border="0" width="300" height="80"></a></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top">
<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Welcome to the PHPHG guestbook installation</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center">
<form method="post" action="install.php">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="text">Database Info</font></td>
</tr>
</table>
<br>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Host:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="host" id="host" value="localhost"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Name:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="dbname" id="dbname"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Username:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="dbuser" id="dbuser"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Password:</font></td>
<td width="50%" valign="top" align="left"><input type="password" id="dbpass" name="dbpass"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Database Prefix:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="prefix" id="prefix" value="phphg"></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellapdding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="text">Site Info</font></td>
</tr>
</table>
<br>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" align="right"><font class="text">Site Name:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="site_name" id="site_name"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Site Email:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="email" id="email"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Domain name:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="domain" id="domain" value="<?php echo $url; ?>"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Script Path:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="script_path" id="script_path" value="<?php echo $script_path; ?>"></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="text">Admin Info</font></td>
</tr>
</table>
<br>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" align="right"><font class="text">Admin Username:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="admin_username" id="admin_username"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Admin Email:</font></td>
<td width="50%" valign="top" align="left"><input type="text" name="admin_email" id="admin_email"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Admin Passowrd:</font></td>
<td width="50%" valign="top" align="left"><input type="password" id="admin_pass" name="admin_pass"></td>
</tr>
<tr>
<td width="50%" valign="top" align="right"><font class="text">Re-enter Passowrd:</font></td>
<td width="50%" valign="top" align="left"><input type="password" id="admin_pass2" name="admin_pass2"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><input type="submit" class="mainoption" name="submit" value="Install"></td>
</tr>
</table>
</form></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Powered By <a href="http://www.hintondesign.org" target="_blank">PHPHG 1.2</a></font></td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>