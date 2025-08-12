<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/edit_lang.php file                   */
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
      $sql = "SELECT * FROM ".$prefix."_lang WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['name'];

            $hidden = "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\">";
            $name2 = "<input type=\"text\" name=\"name\" id=\"name\" value=\"$name\">";
      }

      include("page_header_admin.php");
      $template->getFile(array(
                         'edit_lang' => 'admin/edit_lang.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_EDIT' => $lang['edit_lang'],
                         'L_NAME' => $lang['lang_name'],

                         'HIDDEN' => $hidden,
                         'NAME' => $name2)
      );
      $template->parse("edit_lang");
      include("page_footer_admin.php");
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