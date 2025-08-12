<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                  admin/edit_board.php file                   */
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
      if((!$HTTP_POST_VARS['title']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['domain']) || (!$HTTP_POST_VARS['script_path']) || (!$HTTP_POST_VARS['limit']) || (!$HTTP_POST_VARS['message'])) {
          $display = "Please fill i nthese required fields.<br>";
          if(!$HTTP_POST_VARS['title']) {
             $display .= "Site Title<br>";
          } 
          if(!$HTTP_POST_VARS['email']) {
             $display .= "Email Address<br>";
          }
          if(!$HTTP_POST_VARS['domain']) {
             $display .= "Site URL<br>";
          }
          if(!$HTTP_POST_VARS['script_path']) {
             $display .= "Script Path<br>";
          }
          if(!$HTTP_POST_VARS['limit']) {
             $display .= "Limit Per Page<br>";
          }
          if(!$HTTP_POST_VARS['message']) {
             $display .= "Welcome Message";
          }
          include('page_header_admin.php');
          $template->getFile(array(
                             'error' => 'error.tpl')
          );
          $template->add_vars(array(
                             'L_ERROR' => $lang['error'],
                             'DISPLAY' => $display)
          );
          $template->parse("error");
          include('page_footer_admin.php');
          exit();
       }

       if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
          include('page_header_admin.php');
          $display = "That is not a valid email address.";
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

       if($HTTP_POST_VARS['activate'] == "none") {
          $activate = "none";
       } else 
       if($HTTP_POST_VARS['activate'] == "user") {
          $activate = "user";
       } else
       if($HTTP_POST_VARS['activate'] == "admin") {
          $activate = "admin";
       }

       if($HTTP_POST_VARS['mail'] == "yes") {
          $mail = "yes";
       } else
       if($HTTP_POST_VARS['mail'] == "no") {
          $mail = "no";
       }
        
       
       $sql = "UPDATE ".$prefix."_config SET title='$HTTP_POST_VARS[title]', email='$HTTP_POST_VARS[email]', domain='$HTTP_POST_VARS[domain]', script_path='$HTTP_POST_VARS[script_path]', theme='$HTTP_POST_VARS[theme]', activate='$activate', mail='$mail', link_limit='$HTTP_POST_VARS[limit]', message='$HTTP_POST_VARS[message]', lang='$HTTP_POST_VARS[lang]'";
       $result = $db->query($sql);

       if(!$result) {
          include('page_header_admin.php');
          $display = "Could not update config";
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
       } else {
          include('page_header_admin.php');
          $display = "The board config has been edited.";
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
          include('page_footer_admin.php');
          exit();
       }
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