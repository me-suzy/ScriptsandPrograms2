<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                    link_edited.php file                      */
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
      if($HTTP_POST_VARS['delete']) {
         setcookie("loged","no",time()-3600, $dir);
         setcookie("username","",time()-3600, $dir);
         setcookie("user_level","",time()-3600, $dir);
         setcookie("userid","",time()-3600,$dir);
         $session = uniqid('logout_');
         $sql = "DELETE FROM ".$prefix."_links WHERE id='$HTTP_POST_VARS[id]'";
         $result = $db->query($sql);
     
         $sql = "DELETE FROM ".$prefix."_users WHERE username='$HTTP_POST_VARS[username2]'";
         $result = $db->query($sql);

         if(!$result) {
            include($phpht_real_path . 'includes/page_header.php');
            $display = "Could not delete your link.";
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
         } else {
            include($phpht_real_path . 'includes/page_header.php');
            $display = "Your link has been deleted.";
            $link = "index.php";
            $template->getFile(array(
                               'success' => 'success.tpl')
            );
            $template->add_vars(array(
                               'L_SUCCESS' => $lang['success'],
                               'DISPLAY' => $display,
                               'LINK' => $link)
            );
            $template->parse("success");
            include($phpht_real_path . 'includes/page_footer.php');
            exit();
         }
      }
      
      if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Your password dont match";
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
          include($phpht_real_path . 'includes/page_header.php');
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

      if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
         include($phpht_real_path . 'includes/page_header.php');
         $display = "That is not a valid email address.";
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

      if($HTTP_POST_VARS['width'] > '468') {
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Your banner width is too big. It can be no longer than 468 pixels.";
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

      if($HTTP_POST_VARS['height'] > '60') {
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Your banner height is too big. It can be no higher than 60 pixels.";
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

      $sql = "SELECT password FROM ".$prefix."_users WHERE username='$HTTP_POST_VARS[username2]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $pass = $row['password'];
      }

      if(!$HTTP_POST_VARS['password']) {
         $db_password = $pass;
      } else {
         $db_password = md5($_POST['password']);
      }

      $sql = "UPDATE ".$prefix."_links SET name='$HTTP_POST_VARS[name]', url='$HTTP_POST_VARS[url]', banner='$HTTP_POST_VARS[banner]', width='$HTTP_POST_VARS[width]', height='$HTTP_POST_VARS[height]', description='$HTTP_POST_VARS[desc]' WHERE id='$HTTP_POST_VARS[id]'";
      $result = $db->query($sql);

      $sql = "UPDATE ".$prefix."_users SET password='$db_password', email='$HTTP_POST_VARS[email]' WHERE username='$HTTP_POST_VARS[username2]'";
      $result = $db->query($sql);

      if(!$result) {
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Could not update link.";
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
      } else {
         setcookie("loged","no",time()-3600, $dir);
         setcookie("username","",time()-3600, $dir);
         setcookie("user_level","",time()-3600, $dir);
         setcookie("userid","",time()-3600,$dir);
         $session = uniqid('logout_');
         include($phpht_real_path . 'includes/page_header.php');
         $display = "The link has been updated.";
         $link = "index.php";
         $template->getFile(array(
                            'success' => 'success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         include($phpht_real_path . 'includes/page_footer.php');
         exit();
      }
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