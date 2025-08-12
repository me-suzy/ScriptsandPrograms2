<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                      link_edit.php file                      */
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
$phpht_real_path = "./";
include($phpht_real_path . 'common.php');
if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '0') {
      $sql = "SELECT userid FROM ".$prefix."_users WHERE username='$HTTP_COOKIE_VARS[username]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $userid = $row['userid'];
      }
      $sql = "SELECT * FROM ".$prefix."_links WHERE username='$HTTP_COOKIE_VARS[username]'";
      $result = $db->query($sql);
   
      
      while($row = $db->fetch($result)) {
            $link_url = $row['url'];
            $link_banner = $row['banner'];
            $link_height = $row['height'];
            $link_width = $row['width'];
            $link_desc = $row['description'];
            $link_name = $row['name'];
            $link_user = $row['username'];
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
            $link = "in.php?link_id=$link_id";
      }

      include($phpht_real_path . 'includes/page_header.php');
      $template->getFile(array(
                         'link_edit' => 'link_edit.tpl')
      );
      $template->add_vars(array(
                         'L_WEBSITE' => $lang['website'],
                         'L_URL' => $lang['url'],
                         'L_BANNER' => $lang['banner'],
                         'L_WIDTH' => $lang['width'],
                         'L_HEIGHT' => $lang['height'],
                         'L_USERNAME' => $lang['username'],
                         'L_EMAIL' => $lang['email'],
                         'L_PASSWORD' => $lang['password'],
                         'L_PASSWORD2' => $lang['password2'],
                         'L_DESC' => $lang['desc'],
                         'L_EDIT' => $lang['edit_link'],
                         'L_DELETE' => $lang['delete'],
                         'L_VOTEURL' => $lang['vote_url'],

                         'NAME' => $name,
                         'URL' => $url,
                         'BANNER' => $banner,
                         'HEIGHT' => $height,
                         'WIDTH' => $width,
                         'USER' => $user,
                         'EMAIL' => $email,
                         'DESC' => $desc,
                         'HIDDEN' => $hidden,
                         'DOMAIN' => $domain,
                         'DIR' => $dir,
                         'LINK' => $link)
      );
      $template->parse("link_edit");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   } else {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "You do not have permission to view this page";
      $template->getFile(array(
                         'error' => 'error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   }
 } else {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "You are not logged in Please do so <a href=\"edit_link.php\">Here</a>.";
      $template->getFile(array(
                         'error' => 'error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
}
?>