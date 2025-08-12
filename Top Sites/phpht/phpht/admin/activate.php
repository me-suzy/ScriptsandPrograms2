<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/activate.php file                    */
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
      $sql = "SELECT username FROM ".$prefix."_links WHERE id='$HTTP_GET_VARS[link_id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $user = $row['username'];
      }
  
      $sql = "UPDATE ".$prefix."_links SET activated='1' WHERE id='$HTTP_GET_VARS[link_id]'";
      $result = $db->query($sql);

      $sql = "UPDATE ".$prefix."_users SET activated='1' WHERE username='$user'";
      $result = $db->query($sql);

      if(!$result) {
         include('page_header_admin.php');
         $display = "Could not activate link.";
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
         $display = "The link has been activated.";
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