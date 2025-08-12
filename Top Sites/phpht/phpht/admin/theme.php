<?php
/****************************************************************/
/*                       phpht Topsites                         */
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
$phpht_real_path = "./../";
include($phpht_real_path . 'common.php');

if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      $sql = "SELECT * FROM ".$prefix."_themes ORDER BY 'theme' DESC";
      $result = $db->query($sql);
    
      while($row = $db->fetch($result)) {
            $theme_id = $row['id'];
            $theme = $row['theme'];

            $template->add_block_vars("themes", array(
                                       'ID' => $theme_id,
                                       'NAME' => $theme)
            );
      }

      include('page_header_admin.php');
      $template->getFile(array(
                         'theme' => 'admin/theme.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_NAME' => $lang['theme_name'],
                         'L_ACTION' => $lang['action'],
                         'L_ADD' => $lang['add_theme'])
      );
      $template->parse("theme");
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
                