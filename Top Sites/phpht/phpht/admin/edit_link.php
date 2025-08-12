<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/edit_link.php file                   */
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
      $result = $db->query($sql);
   
      while($row = $db->fetch($result)) {
            $link_name = $row['name'];
            $link_url = $row['url'];
            $link_banner = $row['banner'];
            $link_height = $row['height'];
            $link_width = $row['width'];
            $link_user = $row['username'];
            $link_desc = $row['description'];
            $link_id = $row['id'];

            $sql = "SELECT * FROM ".$prefix."_users WHERE username='$link_user'";
            $result = $db->query($sql);

            while($row = $db->fetch($result)) {
                  $user_email = $row['email'];
            }

            $name = "<input type=\"text\" name=\"name\" id=\"name\" value=\"$link_name\">\r\n";
            $url = "<input type=\"text\" name=\"url\" id=\"url\" value=\"$link_url\">\r\n";
            $banner = "<input type=\"text\" name=\"banner\" id=\"banner\" value=\"$link_banner\">\r\n";
            $height = "<input type=\"text\" name=\"height\" id=\"height\" value=\"$link_height\">\r\n";
            $width = "<input type=\"text\" name=\"width\" id=\"width\" value=\"$link_width\">\r\n";
            $user = $link_user ."\r\n";
            $email = "<input type=\"text\" name=\"email\" id=\"email\" value=\"$user_email\">\r\n";
            $desc = "<textarea name=\"desc\" id=\"desc\" cols=\"40\" rows=\"8\">$link_desc</textarea>\r\n";
            $hidden = "<input type=\"hidden\" name=\"id\" value=\"$link_id\">\r\n";
            $hidden .= "<input type=\"hidden\" name=\"username2\" value=\"$link_user\">\r\n";
     }

     include('page_header_admin.php');
     $template->getFile(array(
                        'edit_link' => 'admin/edit_link.tpl')
     );
     $template->add_vars(array(
                        'L_NAV' => $lang['navigation'],
                        'L_EDIT' => $lang['edit_link'],
                        'L_WEBSITE' => $lang['website'],
                        'L_URL' => $lang['url'],
                        'L_BANNER' => $lang['banner'],
                        'L_WIDTH' => $lang['width'],
                        'L_HEIGHT' => $lang['height'],
                        'L_USERNAME' => $lang['username'],
                        'L_EMAIL' => $lang['email'],
                        'L_DESC' => $lang['desc'],

                        'NAME' => $name,
                        'URL' => $url,
                        'BANNER' => $banner,
                        'HEIGHT' => $height,
                        'WIDTH' => $width,
                        'USER' => $user,
                        'EMAIL' => $email,
                        'DESC' => $desc,
                        'HIDDEN' => $hidden)
      );
      $template->parse("edit_link");
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