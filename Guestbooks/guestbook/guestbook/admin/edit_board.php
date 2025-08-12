<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                 admin/edit_board.php file                    */
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
      if((!$HTTP_POST_VARS['title']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['limit'])) {
          include("page_header.php");
          $display = "You didnt fill in some required fields.";
          $template->getFile(array(
                             'error' => 'admin/error.tpl')
          );
          $template->add_vars(array(
                             'L_ERROR' => $lang['error'],
                             'DISPLAY' => $display)
          );
          $template->parse("error");
          include("page_footer.php");
          exit();
      }

      if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS[email])) {
         include("page_header.php");
         $display = "That is not a valid email address";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("page_footer.php");
         exit();
      }
      $sql = "UPDATE ".$prefix."_config SET site_title='$HTTP_POST_VARS[title]', domain_url='$HTTP_POST_VARS[domain]', script_path='$HTTP_POST_VARS[script_path]', board_email='$HTTP_POST_VARS[email]', board_limit='$HTTP_POST_VARS[limit]', default_theme='$HTTP_POST_VARS[theme]', default_lang='$HTTP_POST_VARS[lang]'";
      $result = $db->query($sql);

      if(!$result) {
         include("page_header.php");
         $display = "Could not edit the script settings.";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("page_footer.php");
         exit();
      } else {
         include("page_header.php");
         $display = "The script settings have been edited.";
         $link = "index.php";
         $template->getFile(array(
                            'success' => 'admin/success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         include("page_footer.php");
         exit();
      }
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