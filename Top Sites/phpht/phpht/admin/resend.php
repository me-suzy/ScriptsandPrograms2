<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                    admin/resend.php file                     */
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
      $sql = "SELECT * FROM ".$prefix."_links WHERE id='$HTTP_GET_VARS[link_id]'";
      $result = $db->query($sql) or die(mysql_error());

      $row = $db->fetch($result);
      $link_name = $row['name'];
      $link_user = $row['username'];

      $sql2 = "SELECT * FROM ".$prefix."_users WHERE username='$link_user'";
      $result2 = $db->query($sql2);

      $row2 = $db->fetch($result2);
      $link_email = $row2['email'];

      $subject = "Your vote link for $link_name";
      $link_url = "in.php?link_id=$HTTP_GET_VARS[link_id]";
      $message = "The admin from $site_title has sent you your vote url
                  for $link_name it is below.

                  http://$domain$dir$link_url

                  Thank you
           
                  Please do not reply to this email or it will bounce.";
      
      mail($link_email,$subject,$message,"FROM: $site_title Admin<$uemail>");
      $link = "links.php";
      include("page_header_admin.php");
      $display = "The vote link has been sent.";
      $template->getFile(array(
                         'success' => 'admin/success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
                         'DISPLAY' => $display,
                         'LINK' => $link)
      );
      $template->parse("success");
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
      $display = "You are not logged in Please do so <a href=\"edit_link.php\">Here</a>.";
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