<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                  install/install.php file                    */
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
$phphg_real_path = "./../";
if($HTTP_POST_VARS['admin_pass'] !== $HTTP_POST_VARS['admin_pass2']) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">Your admin passwords don't match"</font></td>
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
<?php
exit();
} else {
if((!$HTTP_POST_VARS['host']) || (!$HTTP_POST_VARS['dbname']) || (!$HTTP_POST_VARS['dbuser']) || (!$HTTP_POST_VARS['dbpass']) || (!$HTTP_POST_VARS['prefix']) || (!$HTTP_POST_VARS['site_name']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path']) || (!$HTTP_POST_VARS['admin_username']) || (!$HTTP_POST_VARS['admin_email']) || (!$HTTP_POST_VARS['admin_pass']) || (!$HTTP_POST_VARS['admin_pass2'])) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">You didn't fill in some fields.</font></td>
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
<?php
exit();
}

if(!eregi("[0-9a-z]{4,10}$", $HTTP_POST_VARS['admin_username'])) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">Your username must be atleast 4 characters long.</font></td>
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
<?php
exit();
}
if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">That is not a valid site email address.</font></td>
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
<?php
exit();
}

if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['admin_email'])) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">That is not a valid site Admin Email Address</font></td>
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
<?php
exit();
}

$php_beg = "<?php";
$dbhost = '$dbhost = "' . $HTTP_POST_VARS['host'] . '";';
$dbuser = '$dbuser = "' . $HTTP_POST_VARS['dbuser'] . '";';
$dbpass = '$dbpass = "' . $HTTP_POST_VARS['dbpass'] . '";';
$dbname = '$dbname = "' . $HTTP_POST_VARS['dbname'] . '";';
$prefix = '$prefix = "' . $HTTP_POST_VARS['prefix'] . '";';
$defines = 'define(\'PHPHG_INSTALLED\', true);';
$php_end = "?>";

$file = $phphg_real_path . 'config.php';
$message = $php_beg . "\r\n" . $dbhost . "\r\n" . $dbuser . "\r\n" . $dbpass . "\r\n" . $dbname . "\r\n" . $prefix . "\r\n" . $defines . "\r\n" . $php_end;

if(is_writable($file)) {
   if(!$handle = fopen($file, 'a')) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">Could not open file <?php echo $file; ?></font></td>
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
<?php
exit();
}
if(!fwrite($handle, $message)) {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">Could not write to file <?php echo $file; ?></font></td>
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
<?php
exit();
} else {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">The script has been installed please click the button below to finish installation.</font><br>
<form method="post" action="install2.php">
<input type="hidden" name="site_name" value="<?php echo $HTTP_POST_VARS['site_name']; ?>">
<input type="hidden" name="email" value="<?php echo $HTTP_POST_VARS['email']; ?>">
<input type="hidden" name="domain" value="<?php echo $HTTP_POST_VARS['domain']; ?>">
<input type="hidden" name="script_path" value="<?php echo $HTTP_POST_VARS['script_path']; ?>">
<input type="hidden" name="admin_user" value="<?php echo $HTTP_POST_VARS['admin_username']; ?>">
<input type="hidden" name="admin_email" value="<?php echo $HTTP_POST_VARS['admin_email']; ?>">
<input type="hidden" name="admin_pass" value="<?php echo $HTTP_POST_VARS['admin_pass']; ?>">
<input type="submit" class="mainoption" name="submit" value="Finish Installation"></form></td>
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
<?php
exit();
}
} else {
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
<td width="100%" valign="top" bgcolor="#0099FF" align="center"><font class="block-title">Error</font></td>
</tr>
<tr>
<td width="100%" valign="top" align="center"><font class="text">The File <?php echo $file; ?> is not writable please chmodd it to 777</font></td>
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
<?php
exit();
}
}
?>