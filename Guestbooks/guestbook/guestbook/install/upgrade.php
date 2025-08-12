<?php
$phphg_real_path = "./../";
include($phphg_real_path . 'common.php');

$sql = "ALTER TABLE ".$prefix."_config DROP meta_desc, DROP meta_key, DROP meta_author";
$result = $db->query($sql);

if(!$result) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not alter the config table.<br> <?php echo mysql_error(); ?></font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
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

$sql2 = "ALTER TABLE ".$prefix."_config ADD default_lang varchar(255) NOT NULL, ADD default_theme varchar(255) NOT NULL";
$result2 = $db->query($sql2);

if(!$result2) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not alter the config table.<br><?php echo mysql_error(); ?></font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
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
$sql3 = "CREATE TABLE ".$prefix."_lang (
          id int(35) NOT NULL auto_increment,
          name varchar(50) NOT NULL default '',
          PRIMARY KEY(id))";
$result3 = $db->query($sql3);

if(!$result3) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table lang</font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
exit();
<?php
}

$sql4 = "CREATE TABLE ".$prefix."_themes (
         id int(35) NOT NULL auto_increment,
         name varchar(50) NOT NULL default '',
         PRIMARY KEY(id))";
$result4 = $db->query($sql4);

if(!$result4) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table lang</font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
$sql = "DROP TABLE ".$prefix."_lang";
$result = $db->query($sql);
exit();
}

$sql = "UPDATE ".$prefix."_config SET default_lang='english', default_theme='default'";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_lang(name) VALUES ('english')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_themes(name) VALUES ('default')";
$result = $db->query($sql);

if(!$result) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not update the guestbook.</font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
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
<td width="100%" valign="top" align="center"><font class="text">The guestbook has been update. Click <a href="../index.php">Here</a> to return to the index page.</font></td>
</tr>
</table></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><font class="copyright">Copyright &copy; 2003 Hinton Design</font></td>
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
?>