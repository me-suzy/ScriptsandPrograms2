<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/edit_theme.php file                  */
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
      $sql = "SELECT * FROM ".$prefix."_themes WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $theme_id = $row['id'];
            $theme = $row['theme'];

            $theme_name = "<input type=\"text\" name=\"theme\" id=\"theme\" value=\"$theme\">";
            $hidden = "<input type=\"hidden\" name=\"id\" value=\"$theme_id\">";
      }
    
      include('page_header_admin.php');
      $template->getFile(array(
                         'edit_theme' => 'admin/edit_theme.tpl')
      );
      $template->add_vars(array(
                         'L_NAME' => $lang['theme_name'],
                         'L_NAV' => $lang['navigation'],
                         'L_EDIT' => $lang['edit_theme'],

                         'THEME' => $theme_name,
                         'HIDDEN' => $hidden)
      );
      $template->parse("edit_theme");
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