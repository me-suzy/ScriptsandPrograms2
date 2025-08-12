<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                  install/install2.php file                   */
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
$default_lang = "english";
include($phphg_real_path . 'common.php');

$sql = "CREATE TABLE ".$prefix."_admin (
           userid int(35) NOT NULL auto_increment,
           username varchar(100) NOT NULL default '',
           password varchar(255) NOT NULL default '',
           email varchar(255) NOT NULL default '',
           activated enum('0','1') NOT NULL default '0',
           user_level enum('0','1') NOT NULL default '0',
           PRIMARY KEY(userid))";
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table Admin.<br><?php echo mysql_error(); ?></font></td>
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

$sql2 = "CREATE TABLE ".$prefix."_banned (
           id int(35) NOT NULL auto_increment,
           ip varchar(100) NOT NULL default '',
           username varchar(100) NOT NULL default '',
           userid int(35) NOT NULL default '0',
           PRIMARY KEY(id))";
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table banned.<br><?php echo mysql_error(); ?></font></td>
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
$sql4 = "DROP TABLE ".$prefix."_admin";
$result4 = $db->query($sql4);
exit();
}

$sql3 = "CREATE TABLE ".$prefix."_config (
          id int(35) NOT NULL auto_increment,
          site_title varchar(100) NOT NULL default '',
          domain_url varchar(255) NOT NULL default '',
          copyright text NOT NULL,
          board_limit varchar(100) NOT NULL default '',
          board_email varchar(255) NOT NULL default '',
          script_path varchar(255) NOT NULL default '',
          default_lang varchar(255) NOT NULL default '',
          default_theme varchar(255) NOT NULL default '',
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table config.<br><?php echo mysql_error(); ?></font></td>
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
$sql5 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned";
$result5 = $db->query($sql5);
exit();
}

$sql6 = "CREATE TABLE ".$prefix."_filter (
           id int(35) NOT NULL auto_increment,
           filter_word varchar(100) NOT NULL default '',
           filter_replace varchar(100) NOT NULL default '',
           PRIMARY KEY(id))";
$result6 = $db->query($sql6);

if(!$result6) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table filter.<br><?php echo mysql_error(); ?></font></td>
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
$sql7 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned,".$prefix."_config";
$result7 = $db->query($sql7);
exit();
}

$sql8 = "CREATE TABLE ".$prefix."_message (
          id int(35) NOT NULL auto_increment,
          username varchar(100) NOT NULL default '',
          location varchar(100) NOT NULL default '',
          ip varchar(100) NOT NULL default '',
          browser varchar(100) NOT NULL default '',
          email varchar(255) NOT NULL default '',
          website varchar(255) NOT NULL default '',
          date datetime NOT NULL default '0000-00-00 00:00:00',
          message text NOT NULL,
          PRIMARY KEY(id))";
$result8 = $db->query($sql8);

if(!$result8) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table message.<br><?php echo mysql_error(); ?></font></td>
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
$sql9 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned,".$prefix."_config,".$prefix."_filter";
$result9 = $db->query($sql9);
exit();
}

$sql10 = "CREATE TABLE ".$prefix."_smilies (
          id int(35) NOT NULL auto_increment,
          code varchar(50) NOT NULL default '',
          url varchar(100) NOT NULL default '',
          name varchar(100) NOT NULL default '',
          PRIMARY KEY(id))";
$result10 = $db->query($sql10);

if(!$result10) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table smilies.<br><?php echo mysql_error(); ?></font></td>
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
$sql11 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned,".$prefix."_config,".$prefix."_filter,".$prefix."_message";
$result11 = $db->query($sql11);
exit();
}

$sql13 = "CREATE TABLE ".$prefix."_lang (
          id int(35) NOT NULL auto_increment,
          name varchar(50) NOT NULL default '',
          PRIMARY KEY(id))";
$result13 = $db->query($sql13);

if(!$result13) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table lang.<br><?php echo mysql_error(); ?></font></td>
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
$sql14 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned,".$prefix."_config,".$prefix."_filter,".$prefix."_message,".$prefix."_smilies";
$result14 = $db->query($sql14);
exit();
}

$sql15 = "CREATE TABLE ".$prefix."_themes (
          id int(35) NOT NULL auto_increment,
          name varchar(50) NOT NULL default '',
          PRIMARY KEY(id))";
$result15 = $db->query($sql15);

if(!$result15) {
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
<td width="100%" valign="top" align="center"><font class="text">Could not create the table themes.<br><?php echo mysql_error(); ?></font></td>
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
$sql11 = "DROP TABLE ".$prefix."_admin,".$prefix."_banned,".$prefix."_config,".$prefix."_filter,".$prefix."_message,".$prefix."_smilies";
$result11 = $db->query($sql11);
exit();
}

$sql12 = "INSERT INTO ".$prefix."_smilies VALUES (1, ':0', 'smiley_cool.gif', 'Cool')";
$result12 = $db->query($sql12);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (2, ':g', 'smiley_green.gif', 'Green')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (3, ':|', 'smiley_none.gif', 'None')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (4, ':o', 'smiley_ooh.gif', 'OHH')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (5, ':(', 'smiley_sad.gif', 'Sad')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (6, ':)', 'smiley_smile.gif', 'Happy')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_smilies VALUES (7, ':^', 'smiley_wink.gif', 'Wink')";
$result = $db->query($sql);

$default_lang = "english";
$default_theme = "default";
$copyright = "Powered By <a href=\"http://www.hintondesign.org\" target=\"_blank\">PHPHG 1.2</a>";
$board_limit = "10";

$sql = "INSERT INTO ".$prefix."_config (site_title, domain_url, copyright, board_limit, board_email, script_path, default_lang, default_theme)
        VALUES ('$HTTP_POST_VARS[site_name]', '$HTTP_POST_VARS[domain]', '$copyright', '$board_limit', '$HTTP_POST_VARS[email]', '$HTTP_POST_VARS[script_path]', '$default_lang', '$default_theme')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_lang (name) VALUES ('$default_lang')";
$result = $db->query($sql);

$sql = "INSERT INTO ".$prefix."_themes (name) VALUES ('$default_theme')";
$result = $db->query($sql);

$db_password = md5($HTTP_POST_VARS['admin_pass']);

$sql = "INSERT INTO ".$prefix."_admin (username, email, password, activated, user_level) VALUES ('$HTTP_POST_VARS[admin_user]', '$HTTP_POST_VARS[admin_email]', '$db_password', '1', '1')";
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
<td width="100%" valign="top" align="center"><font class="text">The script could not be installed <br><?php echo mysql_error(); ?></font></td>
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
header("Location: ".$phphg_real_path."index.php");
}
?>