<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                 admin/link_edited.php file                   */
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
      if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['url']) || (!$HTTP_POST_VARS['email']) || (!$HTTP_POST_VARS['desc'])) {
          $display = "Please fill in these required fields.<br>";
          if(!$HTTP_POST_VARS['name']) {
             $display .= "Website Name.<br>";
          }
          if(!$HTTP_POST_VARS['url']) {
             $display .= "Website URL.<br>";
          }
          if(!$HTTP_POST_VARS['desc']) {
             $display .= "Website Description.<br>";
          }
          if(!$HTTP_POST_VARS['email']) {
             $display .= "Email Address<br>";
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

       if($HTTP_POST_VARS['width'] > '468') {
          include('page_header_admin.php');
          $display = "Your banner width is too big. It can be no longer than 468 pixels.";
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

       if($HTTP_POST_VARS['height'] > '60') {
          include('page_header_admin.php');
          $display = "Your banner height is too big. It can be no higher than 60 pixels.";
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

       $sql = "UPDATE ".$prefix."_links SET name='$HTTP_POST_VARS[name]', url='$HTTP_POST_VARS[url]', banner='$HTTP_POST_VARS[banner]', width='$HTTP_POST_VARS[width]', height='$HTTP_POST_VARS[height]', description='$HTTP_POST_VARS[desc]' WHERE id='$HTTP_POST_VARS[id]'";
       $result = $db->query($sql);

       $sql = "UPDATE ".$prefix."_users SET email='$HTTP_POST_VARS[email]' WHERE username='$HTTP_POST_VARS[username2]'";
       $result = $db->query($sql);

       if(!$result) {
          include('page_header_admin.php');
          $display = "Could not update link.";
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
       } else {
          include('page_header_admin.php');
          $display = "The link has been updated.";
          $link = "links.php";
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