<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                   admin/smilie.php file                      */
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
      $sql = "SELECT * FROM ".$prefix."_smilies ORDER BY 'name' DESC";
      $result = $db->query($sql);
 
      $smilie = "";

      while($row = $db->fetch($result)) {
            $smilie_url = $row['url'];
            $id = $row['id'];
            $smilie_name = $row['name'];

            $template->add_block_vars("smile", array(
                                       'NAME' => $smilie_name,
                                       'ID' => $id,
                                       'URL' => $smilie_url)
            );
      }

      include("page_header.php");
      $template->getFile(array(
                         'smilie' => 'admin/smile.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_HOME' => $lang['home'],
                         'L_EDIT_MESSAGE' => $lang['edit_message'],
                         'L_BAN' => $lang['ban'],
                         'L_EDIT_SMILE' => $lang['edit_smile'],
                         'L_EDIT_WORD' => $lang['edit_word'],
                         'L_LOGOUT' => $lang['logout'],
                         'L_SMILIE_NAME' => $lang['smilie_name'],
                         'L_FILE_NAME' => $lang['file_name'],
                         'L_ACTION' => $lang['action'],
                         'L_SMILIE_CODE' => $lang['smilie_code'],
                         'L_ADD_SMILIE' => $lang['add_smilie'],
                         'L_SETTINGS' => $lang['settings'],
                         'L_THEME' => $lang['themes'],
                         'L_LANG' => $lang['langs'],

                         'U_HOME' => 'index.php',
                         'U_EDIT_MESSAGE' => 'message.php',
                         'U_BAN' => 'ban.php',
                         'U_EDIT_SMILIE' => 'smilie.php',
                         'U_EDIT_WORD' => 'filter.php',
                         'U_LOGOUT' => $phphg_real_path . 'logout.php',
                         'U_SETTINGS' => 'settings.php',
                         'U_THEME' => 'theme.php',
                         'U_LANG' => 'lang.php')
       );
       $template->parse("smilie");
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