<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                    admin/theme.php file                      */
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

if($_COOKIE['loged'] == 'yes') {
   if($_COOKIE['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_themes ORDER BY 'name' ASC";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];

            $template->add_block_vars("theme", array(
                                      'ID' => $id,
                                      'NAME' => $name)
            );
      }

      include("page_header.php");
      $template->getFile(array(
                         'theme' => 'admin/theme.tpl')
      );
      $template->add_vars(array(
                          'L_NAME' => $lang['theme_name'],
                          'L_NAV' => $lang['navigation'],
                          'L_HOME' => $lang['home'],
                          'L_EDIT_MESSAGE' => $lang['edit_message'],
                          'L_BAN' => $lang['ban'],
                          'L_EDIT_SMILE' => $lang['edit_smile'],
                          'L_EDIT_WORD' => $lang['edit_word'],
                          'L_LOGOUT' => $lang['logout'],
                          'L_SETTINGS' => $lang['settings'],
                          'L_THEME' => $lang['themes'],
                          'L_LANG' => $lang['langs'],
                          'L_ACTION' => $lang['action'],
                          'L_ADD' => $lang['add_theme'],

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
      $template->parse("theme");
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