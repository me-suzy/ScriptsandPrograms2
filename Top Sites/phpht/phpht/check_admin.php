<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                     check_admin.php file                     */
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

if((!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['password'])) {
     include($phpht_real_path . 'includes/page_header.php');          
     $display = "Please enter in your username and password.";
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

$password = md5($HTTP_POST_VARS['password']);

$sql = "SELECT * FROM ".$prefix."_users WHERE username='$HTTP_POST_VARS[username]' AND password='$password' AND activated='1'";
$result = $db->query($sql);

$num = $db->num($result);
if($num > 0) {
   while($row = $db->fetch($result)) {
         foreach($row AS $key => $val) {
                 $$key = stripslashes($val);
         }
         setcookie("loged","yes",time()+3600,$dir);
         setcookie("username","$username",time()+3600,$dir);
         setcookie("user_level","$user_level",time()+3600,$dir);
         setcookie("userid","$userid",time()+3600,$dir);
         $session = uniqid('login_');
         include($phpht_real_path . 'includes/page_header.php');
         $display = "Thank you for logging in $username.";
         $link = "admin/index.php";
         $template->getFile(array(
                            'success' => 'success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         exit();
    }
} else {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "You could not be logged in. Please make sure that your username and password are correct and that your account has been activated.";
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