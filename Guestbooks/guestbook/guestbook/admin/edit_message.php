<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                admin/edit_message.php file                   */
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
      $sql = "SELECT * FROM ".$prefix."_message WHERE id='$HTTP_GET_VARS[id]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $id = $row['id'];
            $name = $row['username'];
            $location = $row['location'];
            $browser = $row['browser'];
            $email = $row['email'];
            $website = $row['website'];
            $date = $row['date'];
            $message = $row['message'];

            $name2 = addslashes($name);
            $message2 = addslashes($message);

            $date = strtotime($date);
            $date = date('D F d Y h:i:s', $date);

            $location = "<input type=\"text\" name=\"location\" id=\"location\" value=\"$location\">";
            $email = "<input type=\"text\" name=\"email\" id=\"email\" value=\"$email\">";
            $website = "<input type=\"text\" name=\"website\" id=\"website\" value=\"$website\">";
            $message = "<textarea name=\"message\" id=\"message\" cols=\"30\" rows=\"8\">$message2</textarea>";
            $hidden = "<input type=\"hidden\" name=\"id\" value=\"$id\">";
      }
      include("page_header.php");
      $template->getFile(array(
                         'edit_message' => 'admin/edit_message.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_HOME' => $lang['home'],
                         'L_EDIT_MESSAGE' => $lang['edit_message'],
                         'L_BAN' => $lang['ban'],
                         'L_EDIT_SMILE' => $lang['edit_smile'],
                         'L_EDIT_WORD' => $lang['edit_word'],
                         'L_LOGOUT' => $lang['logout'],
                         'L_EMAIL' => $lang['email'],
                         'L_NAME' => $lang['names'],
                         'L_WEBSITE' => $lang['website'],
                         'L_MESSAGE' => $lang['message'],
                         'L_LOCATION' => $lang['location'],
                         'L_DATE' => $lang['date'],
                         'L_BROWSER' => $lang['browser'],
                         'L_SETTINGS' => $lang['settings'],
                         'L_THEME' => $lang['themes'],
                         'L_LANG' => $lang['langs'],

                         'LOCATION' => $location,
                         'EMAIL' => $email,
                         'WEBSITE' => $website,
                         'MESSAGE' => $message,
                         'HIDDEN' => $hidden,
                         'DATE' => $date,
                         'NAME' => $name2,
                         'BROWSER' => $browser,
                         
                         'U_HOME' => 'index.php',
                         'U_EDIT_MESSAGE' => 'message.php',
                         'U_BAN' => 'ban.php',
                         'U_EDIT_SMILE' => 'smilie.php',
                         'U_EDIT_WORD' => 'filter.php',
                         'U_LOGOUT' => $phphg_real_path . 'logout.php',
                         'U_SETTINGS' => 'settings.php',
                         'U_THEME' => 'theme.php',
                         'U_LANG' => 'lang.php')
       );
       $template->parse("edit_message");
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