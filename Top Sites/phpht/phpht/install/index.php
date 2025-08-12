<?php
/****************************************************************/
/*                       phpht Topsites                         */
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
$phpht_real_path = "./../";
include($phpht_real_path . "common.php");

if($HTTP_GET_VARS['step'] == '2') {
   if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
      include("header.php");
      $display = "Your passwords no not match.";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   if((!$HTTP_POST_VARS['dbhost']) || (!$HTTP_POST_VARS['dbuser']) || (!$HTTP_POST_VARS['dbpass']) || (!$HTTP_POST_VARS['dbname']) || (!$HTTP_POST_VARS['prefix']) || (!$HTTP_POST_VARS['uemail']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path']) || (!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['password']) || (!$HTTP_POST_VARS['password2'])) {
       include("header.php");
       $display = "Please fill in the required fields.";
       $template->getFile(array(
                          'error' => 'install/error.tpl')
       );
       $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
                          'DISPLAY' => $display)
       );
       $template->parse("error");
       include("footer.php");
       exit();
   }

   if(!eregi("[0-9a-z]{4,10}$", $HTTP_POST_VARS['username'])) {
      include("header.php");
      $display = "Your username has to be atleast 4 chars long.";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['uemail'])) {
      include("header.php");
      $display = "That is not a valid email address.";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $php_beg = "<?php";
   $error = 'if(eregi("config.php",$HTTP_SERVER_VARS[\'PHP_SELF\'])) {' . "\r\n";
   $error .= 'echo "You can\'t access this file directly.";' . "\r\n";
   $error .= '}';
   $dbhost = '$dbhost = "' . $HTTP_POST_VARS['dbhost'] . '";';
   $dbuser = '$dbuser = "' . $HTTP_POST_VARS['dbuser'] . '";';
   $dbpass = '$dbpass = "' . $HTTP_POST_VARS['dbpass'] . '";';
   $dbname = '$dbname = "' . $HTTP_POST_VARS['dbname'] . '";';
   $prefix = '$prefix = "' . $HTTP_POST_VARS['prefix'] . '";';
   $defines = 'define(\'LOGIN_INSTALLED\', true);';
   $php_end = "?>";

   $file = $phpht_real_path . 'config.php';
   $message = $php_beg . "\r\n" . $error . "\r\n" . $dbhost . "\r\n" . $dbuser . "\r\n" . $dbpass . "\r\n" . $dbname . "\r\n" . $prefix . "\r\n" . $defines . "\r\n" . $php_end;

   if(is_writable($file)) {
      if(!$handle = fopen($file, 'a')) {
         include("header.php");
         $display = "Could not open file $file";
         $template->getFile(array(
                            'error' => 'install/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      }

      if(!fwrite($handle, $message)) {
         include("header.php");
         $display = "Could not wtire to file $file";
         $template->getFile(array(
                            'error' => 'install/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      } else {
         include("header.php");
         $display = "<font class=\"text\">Step 2 Complete. Please click the button below to go to step three.</font><br><br>
                     <form method=\"post\" action=\"index.php?step=3\">
                     <input type=\"hidden\" name=\"uemail\" value=\"$HTTP_POST_VARS[uemail]\">
                     <input type=\"hidden\" name=\"domain\" value=\"$HTTP_POST_VARS[domain]\">
                     <input type=\"hidden\" name=\"script_path\" value=\"$HTTP_POST_VARS[script_path]\">
                     <input type=\"hidden\" name=\"username\" value=\"$HTTP_POST_VARS[username]\">
                     <input type=\"hidden\" name=\"password\" value=\"$HTTP_POST_VARS[password]\">
                     <center><input type=\"submit\" name=\"submit\" value=\"Step 3\" class=\"mainoption\"></center>
                     </form>";
         $template->getFile(array(
                            'error' => 'install/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("footer.php");
         exit();
      }
   } else {
      include("header.php");
      $display = "The file $file is not writable.";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }   
}

if($HTTP_GET_VARS['step'] == '3') {
   $sql = "CREATE TABLE ".$prefix."_links (
           id int(35) NOT NULL auto_increment,
           url varchar(255) NOT NULL,
           banner varchar(255) NOT NULL,
           height varchar(50) NOT NULL,
           width varchar(50) NOT NULL,
           description text NOT NULL,
           activated enum('0','1') NOT NULL default '0',
           name varchar(255) NOT NULL,
           hits_out varchar(100) NOT NULL,
           hits_in varchar(100) NOT NULL,
           username varchar(100) NOT NULL,
           PRIMARY KEY(id))";
   $result = $db->query($sql);

   if(!$result) {
      include("header.php");
      $display = "Could not create the table $prefix_links";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $sql2 = "CREATE TABLE ".$prefix."_users (
           userid int(35) NOT NULL auto_increment,
           username varchar(100) NOT NULL,
           email varchar(255) NOT NULL,
           password varchar(255) NOT NULL,
           activated enum('0','1') NOT NULL default'0',
           user_level enum('0','1') NOT NULL default'0',
           ip varchar(100) NOT NULL,
           PRIMARY KEY(userid))";
   $result2 = $db->query($sql2);

   if(!$result2) {
      $sql3 = "DROP TABLE ".$prefix."_links";
      $result3 = $db->query($sql3);
      include("header.php");
      $display = "Could not create the table $prefix_users";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $sql4 = "CREATE TABLE ".$prefix."_config (
        id int(35) NOT NULL auto_increment,
        title varchar(100) NOT NULL,
        email varchar(255) NOT NULL,
        domain varchar(255) NOT NULL,
        script_path varchar(255) NOT NULL,
        copyright text NOT NULL,
        lang varchar(100) NOT NULL,
        theme varchar(100) NOT NULL,
        message text NOT NULL,
        activate varchar(100) NOT NULL,
        mail varchar(5) NOT NULL,
        admin_message text NOT NULL,
        link_limit varchar(50) NOT NULL,
        PRIMARY KEY(id))";
   $result4 = $db->query($sql4);

   if(!$result4) {
      $sql5 = "DROP TABLE ".$prefix."_links,".$prefix."_users";
      $result5 = $db->query($sql5);
      include("header.php");
      $display = "Could not create the table $prefix_config";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $sql8 = "CREATE TABLE ".$prefix."_themes (
              id int(35) NOT NULL auto_increment,
              theme varchar(100) NOT NULL,
              PRIMARY KEY(id))";
   $result8 = $db->query($sql8);

   if(!$result8) {
      $sql9 = "DROP TABLE ".$prefix."_links,".$prefix."_users,".$prefix."_config,".$prefix."_admin";
      $result9 = $db->query($sql9);
      include("header.php");
      $display = "Could not create the table $prefix_themes";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $sql10 = "CREATE TABLE ".$prefix."_ip (
              ip_id int(35) NOT NULL auto_increment,
              link_id int(35) NOT NULL,
              ip varchar(12) NOT NULL,
              PRIMARY KEY(ip_id))";
   $result10 = $db->query($sql10);

   if(!$result10) {
      $sql11 = "DROP TABLE ".$prefix."_links,".$prefix."_users,".$prefix."_config,".$prefix."_admin,".$prefix."_themes";
      $result11 = $db->query($sql11);
      include("header.php");
      $display = "Could not create the table $prefix_ip";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }

   $sql15 = "CREATE TABLE ".$prefix."_lang (
             id int(35) NOT NULL auto_increment,
             name varchar(255) NOT NULL default '',
             PRIMARY KEY(id))";
   $result15 = $db->query($sql15);

   if(!$result15) {
      $sql16 = "DROP TABLE ".$prefix."_links,".$prefix."_users,".$prefix."_config,".$prefix."_admin,".$prefix."_themes,".$prefix."_ip";
      $result16 = $db->query($sql16);
      include("header.php");
      $display = "Could not create the table $prefix_lang";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   } else {
      include("header.php");
      $display = "<font class=\"text\">Step 3 Complete. Please click the button below to go to step 4.</font><br><br>
                  <form method=\"post\" action=\"index.php?step=4\">
                  <input type=\"hidden\" name=\"uemail\" value=\"$HTTP_POST_VARS[uemail]\">
                  <input type=\"hidden\" name=\"domain\" value=\"$HTTP_POST_VARS[domain]\">
                  <input type=\"hidden\" name=\"script_path\" value=\"$HTTP_POST_VARS[script_path]\">
                  <input type=\"hidden\" name=\"username\" value=\"$HTTP_POST_VARS[username]\">
                  <input type=\"hidden\" name=\"password\" value=\"$HTTP_POST_VARS[password]\">
                  <center><input type=\"submit\" name=\"submit\" value=\"Step 4\" class=\"mainoption\"></center>
                  </form>";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   }
}

if($HTTP_GET_VARS['step'] == '4') {
   $copyright = "Powered by <a href=\"http://www.hintondesign.org\" target=\"_blank\">phpht 1.3</a>";

   $welcome = "Put your topsites welcome message here.";

   $welcome_admin = "Welcome to the phpht Admin CP. THank you again for choosing phpht. Here you can edit,add,delete users admins,and links to the topsites list.";

   $dbpass = md5($HTTP_POST_VARS['password']);

   $theme3 = "default";
   $lang3 = "english";

   $sql18 = "INSERT INTO ".$prefix."_config (title,email,domain,script_path,copyright,lang,theme,message,activate,mail,admin_message,link_limit) VALUES ('phpht Topsites', '$HTTP_POST_VARS[uemail]', '$HTTP_POST_VARS[domain]','$HTTP_POST_VARS[script_path]', '$copyright', 'english', 'default', '$welcome', 'none', 'yes', '$welcome_admin', '25')";
   $result18 = $db->query($sql18) or die(mysql_error());

   $sql19 = "INSERT INTO ".$prefix."_themes (theme) VALUES ('$theme3')";
   $result19 = $db->query($sql19) or die(mysql_error());

   $sql20 = "INSERT INTO ".$prefix."_lang (name) VALUES ('$lang3')";
   $result20 = $db->query($sql20) or die(mysql_error());

   $sql17 = "INSERT INTO ".$prefix."_users (username,email,password,activated,user_level,ip) VALUES ('$HTTP_POST_VARS[username]','$HTTP_POST_VARS[uemail]','$dbpass','1','1','$HTTP_SERVER_VARS[REMOTE_ADDR]')";
   $result17 = $db->query($sql17);

   if(!$result17) {
      include("header.php");
      $display = "Could not install the script.";
      $template->getFile(array(
                         'error' => 'install/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include("footer.php");
      exit();
   } else {
      header("Location: ../index.php");
      exit();
   }
}

$domain = $HTTP_SERVER_VARS['HTTP_HOST'];
$script_path = (!empty($HTTP_POST_VARS['script_path'])) ? $HTTP_POST_VARS['script_path'] : str_replace('install', '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
$server_port = $HTTP_SERVER_VARS['SERVER_PORT'];
?>
<html>
<head>
<title>PHPht Installation</title>
<link rel="stylesheet" href="../templates/default/default.css" type="text/css">
</head>
<body>
<table class="bodyline" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" valign="top"><img src="../templates/default/images/phpht_logo.gif" border="0" width="200" height="91"></td>
<td width="50%" align="center"><font class="welcome">Welcome to phpht Installation</font></td>
</tr>
</table>
<br>
<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top"><blockquote><font class="text">Thank you for choosing phpht. In order to complete this install please fill out the details requested below. Please note that the database you install into should already exist.</font></blockquote></td>
</tr>
</table>
<br>
<br>
<table class="gen" cellspacing="5" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center">
<form method="post" action="index.php?step=2">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center" height="20" background="../templates/default/images/header.gif"><font class="block-title">Database Configuration</font></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Database Server:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="dbhost" id="dbhost" value="localhost"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Database Name:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="dbname" id="dbname"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Database Username:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="dbuser" id="dbuser"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Database Password:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="password" name="dbpass" id="dbpass"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Prefix for tables in database:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="prefix" id="prefix" value="phpht"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center" height="20" background="../templates/default/images/header.gif"><font class="block-title">Administator Configuration</font></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Admin Email Address:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="uemail" id="uemail"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Domain Name:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="domain" id="domain" value="<?php echo $domain; ?>"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Script Path:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text"><input type="text" name="script_path" id="script_path" value="<?php echo $script_path; ?>"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Admin Username:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="text" name="username" id="username"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Admin Password:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="password" id="password" name="password"></td>
</tr>
<tr>
<td width="50%" valign="top" bgcolor="#CCCCCC"><font class="text">Admin Password [Confirm]:</font></td>
<td width="50%" valign="top" bgcolor="#CCCCCC"><input type="password" id="password2" name="password2"></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="100%" valign="top" align="center"><input class="mainoption" type="submit" name="submit" value="Step 2"></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>