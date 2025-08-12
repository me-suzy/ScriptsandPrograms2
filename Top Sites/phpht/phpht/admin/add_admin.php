<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/add_admin.php file                   */
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
      if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
         include('page_header_admin.php');
         $display = "Your passwords dont match";
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

      if((!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['password']) || (!$HTTP_POST_VARS['password2'])) {
          $display = "Please fill in these required fields.<br>";
          if(!$HTTP_POST_VARS['username']) {
             $display .= "Username<br>";
          }
          if(!$HTTP_POST_VARS['email']) {
             $display .= "Email Address<br>";
          }
          if(!$HTTP_POST_VARS['password']) {
             $display .= "Password<br>";
          }
          if(!$HTTP_POST_VARS['password2']) {
             $display .= "Password Again<br>";
          }
          include('page_header_admin.php');
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

      if(!eregi("[0-9a-z]{4,10}$", $HTTP_POST_VARS['username'])) {
         include('page_header_admin.php');
         $display = "Your username must be atleast four characters long.";
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

      $sql = "SELECT username FROM ".$prefix."_users WHERE username='$HTTP_POST_VARS[username]'";
      $result = $db->query($sql);

      $num = $db->num($result);

      if($num > 0) {
         include('page_header_admin.php');
         $display = "That username is already taken.";
         unset($HTTP_POST_VARS['username']);
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
         
      $db_password = md5($HTTP_POST_VARS['password']);

      $sql = "INSERT INTO ".$prefix."_users (username, email, password, activated, user_level) VALUES ('$HTTP_POST_VARS[username]', '$HTTP_POST_VARS[email]', '$db_password', '1', '1')";
      $result = $db->query($sql);

      if(!$result) {
         include('page_header_admin.php');
         $display = "Could not add the admin.";
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
         $display = "The admin has been added.";
         $link = "admin.php";
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