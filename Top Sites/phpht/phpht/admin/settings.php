<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/settings.php file                    */
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
include($phpht_real_path . 'common.php');

if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_config";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $site_title = $row['title'];
            $email = $row['email'];
            $domain = $row['domain'];
            $script_path = $row['script_path'];
            $lang2 = $row['lang'];
            $theme = $row['theme'];
            $activate = $row['activate'];
            $mail = $row['mail'];
            $limit = $row['link_limit'];
            $message = $row['message'];

            $sql = "SELECT * FROM ".$prefix."_themes";
            $result = $db->query($sql);
 
            $theme2 = "";
            while($row = $db->fetch($result)) {
                  $theme_name = $row['theme'];
                  $theme_id = $row['id'];

                  if($theme == $theme_name) {
                     $selected = "selected";
                  } else {
                     $selected = "";
                  }

                  $theme2 .= "<option value=\"$theme_name\" $selected>$theme_name</option>";
            }

            $sql = "SELECT * FROM ".$prefix."_lang";
            $result = $db->query($sql);

            $langs = "";
            while($row = $db->fetch($result)) {
                  $lang_name = $row['name'];
                  $lang_id = $row['id'];

                  if($lang2 == $lang_name) {
                     $selected = "selected";
                  } else {
                     $selected = "";
                  }

                  $langs .= "<option value=\"$lang_name\" $selected>$lang_name</option>";
            }

            if($activate == "admin") {
               $checked = "<input type=\"radio\" name=\"activate\" id=\"none\" value=\"none\"><font class=\"text\">None</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"user\" value=\"user\"><font class=\"text\">Email</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"admin\" value=\"admin\" checked=\"checked\"><font class=\"text\">Admin</font>";
            } else 
            if($activate == "user") {
               $checked = "<input type=\"radio\" name=\"activate\" id=\"none\" value=\"none\"><font class=\"text\">None</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"user\" value=\"user\" checked=\"checked\"><font class=\"text\">Email</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"admin\" value=\"admin\"><font class=\"text\">Admin</font>";
            } else 
            if($activate == "none") {
               $checked = "<input type=\"radio\" name=\"activate\" id=\"none\" value=\"none\" checked=\"checked\"><font class=\"text\">None</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"user\" value=\"user\"><font class=\"text\">Email</font>";
               $checked .= "<input type=\"radio\" name=\"activate\" id=\"admin\" value=\"admin\"><font class=\"text\">Admin</font>";
            }
            if($mail == "yes") {
               $mail_selected = "<input type=\"radio\" name=\"mail\" id=\"mail\" value=\"yes\" checked=\"checked\"><font class=\"text\">Yes</font>";
               $mail_selected .= "<input type=\"radio\" name=\"mail\" id=\"mail\" value=\"no\"><font class=\"text\">No</font>";
            } else 
            if($mail == "no") {
               $mail_selected = "<input type=\"radio\" name=\"mail\" id=\"mail\" value=\"yes\"><font class=\"text\">Yes</font>";
               $mail_selected .= "<input type=\"radio\" name=\"mail\" id=\"mail\" value=\"no\" checked=\"checked\"><font class=\"text\">No</font>";
            }

            $title = "<input type=\"text\" name=\"title\" value=\"$site_title\" id=\"title\">";
            $board_email = "<input type=\"text\" name=\"email\" id=\"email\" value=\"$email\">";
            $board_domain = "<input type=\"text\" name=\"domain\" id=\"domain\" value=\"$domain\">";
            $board_script = "<input type=\"text\" name=\"script_path\" id=\"script_path\" value=\"$script_path\">";
            $theme3 = "<select name=\"theme\">$theme2</select>";
            $board_mail = $mail_selected;
            $board_activate = $checked;
            $board_limit = "<input type=\"text\" name=\"limit\" id=\"limit\" value=\"$limit\">";
            $wel_message = "<textarea name=\"message\" id=\"message\" cols=\"40\" rows=\"8\">$message</textarea>";
            $langs = "<select name=\"lang\">$langs</select>";
       }

       include('page_header_admin.php');
       $template->getFile(array(
                          'settings' => 'admin/settings.tpl')
       );
       $template->add_vars(array(
                          'L_NAV' => $lang['navigation'],
                          'L_BOARD' => $lang['board'],
                          'L_TITLE' => $lang['site_title'],
                          'L_EMAIL' => $lang['site_email'],
                          'L_DOMAIN' => $lang['domain'],
                          'L_SCRIPT' => $lang['script_path'],
                          'L_THEME' => $lang['default_theme'],
                          'L_LANG' => $lang['default_lang'],
                          'L_ACT' => $lang['activation'],
                          'L_MAIL' => $lang['mail_func'],
                          'L_LIMIT' => $lang['limit'],
                          'L_WEL' => $lang['wel_mess'],

                          'TITLE' => $title,
                          'EMAIL' => $board_email,
                          'DOMAIN' => $board_domain,
                          'SCRIPT_PATH' => $board_script,
                          'THEME' => $theme3,
                          'MAIL' => $board_mail,
                          'ACTIVATE' => $board_activate,
                          'LIMIT' => $board_limit,
                          'MESSAGE' => $wel_message,
                          'LANG' => $langs)
        );
        $template->parse("settings");
        include('page_footer_admin.php');
        exit();
   } else {
      include('page_header_admin.php');
      $display = "You do not have permission to view this page";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer_admin.php');
      exit();
   }
} else {
      include('page_header_admin.php');
      $display = "You are not logged in Please do so <a href=\"../admin.php\">Here</a>.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer_admin.php');
      exit();
}
?>
