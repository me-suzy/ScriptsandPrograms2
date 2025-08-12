<?php
/****************************************************************/
/*                       phphg Guestbook                        */
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
$phphg_real_path = "./../";
include($phphg_real_path . 'common.php');

if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_config";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $site_title2 = $row['site_title'];
            $domain2 = $row['domain_url'];
            $limit = $row['board_limit'];
            $email = $row['board_email'];
            $script_path2 = $row['script_path'];
            $default_theme = $row['default_theme'];
            $default_lang = $row['default_lang'];

            $site_title3 = addslashes($site_title2);

            $site_title4 = "<input type=\"text\" name=\"title\" id=\"title\" value=\"$site_title3\">";
            $domain3 = "<input type=\"text\" name=\"domain\" id=\"domain\" value=\"$domain2\">";
            $script_path3 = "<input type=\"text\" name=\"script_path\" id=\"script_path\" value=\"$script_path2\">";
            $email = "<input type=\"text\" name=\"email\" id=\"email\" value=\"$email\">";
            $limit = "<input type=\"text\" name=\"limit\" id=\"limit\" value=\"$limit\">";
      }

      $sql = "SELECT * FROM ".$prefix."_lang ORDER BY 'name' ASC";
      $result = $db->query($sql);

      $option = "";
      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];

            if($default_lang == $name) {
               $selected = "selected";
            } else {
               $selected = "";
            }

            $option .= "<option value=\"$name\" $selected>$name</option>";
      }

      $sql = "SELECT * FROM ".$prefix."_themes ORDER BY 'name' ASC";
      $result = $db->query($sql);

      $option2 = "";
      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];

            if($default_theme == $name) {
               $selected2 = "selected";
            } else {
               $selected2 = "";
            }

            $option2 .= "<option value=\"$name\" $selected2>$name</option>";
      }

      $select = "<select name=\"lang\">$option</select>";
      $select2 = "<select name=\"theme\">$option2</select>";

      include("page_header.php");
      $template->getFile(array(
                         'settings' => 'admin/settings.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_HOME' => $lang['home'],
                         'L_EDIT_MESSAGE' => $lang['edit_message'],
                         'L_BAN' => $lang['ban'],
                         'L_EDIT_SMILE' => $lang['edit_smile'],
                         'L_EDIT_WORD' => $lang['edit_word'],
                         'L_LOGOUT' => $lang['logout'], 
                         'L_SETTINGS' => $lang['settings'],
                         'L_EDIT_SETTINGS' => $lang['edit_settings'],
                         'L_TITLE' => $lang['title'],
                         'L_DOMAIN' => $lang['domain'],
                         'L_SCRIPT_PATH' => $lang['script_path'],
                         'L_EMAIL' => $lang['board_email'],
                         'L_LIMIT' => $lang['limit'],
                         'L_LANGS' => $lang['lang'],
                         'L_THEMES' => $lang['theme'],
                         'L_THEME' => $lang['themes'],
                         'L_LANG' => $lang['langs'],

                         'SITE_TITLE' => $site_title4,
                         'SCRIPT_PATH' => $script_path3,
                         'EMAIL' => $email,
                         'DOMAIN' => $domain3,
                         'LIMIT' => $limit,
                         'SELECT' => $select,
                         'SELECT2' => $select2,

                         'U_HOME' => 'index.php',
                         'U_EDIT_MESSAGE' => 'message.php',
                         'U_BAN' => 'ban.php',
                         'U_EDIT_SMILE' => 'smilie.php',
                         'U_EDIT_WORD' => 'filter.php',
                         'U_LOGOUT' => $phphg_real_path . 'logout.php',
                         'U_SETTINGS' => 'settings.php',
                         'U_THEME' => 'theme.php',
                         'U_LANG' => 'lang.php')
        );
        $template->parse("settings");
        include("page_footer.php");
        exit();
    } else {
      include('page_header.php');
      $display = "You do not have permission to view this page.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer.php');
      exit();
   }
} else {
      include('page_header.php');
      $display = "You are not logged in. Please do so <a href=\"../admin.php\">Here</a>.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer.php');
      exit();
}
?>    