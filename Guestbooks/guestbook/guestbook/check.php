<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                        check.php file                        */
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
$phphg_real_path = "./";
include($phphg_real_path . 'common.php');

if((!$HTTP_POST_VARS['username']) || (!$HTTP_POST_VARS['password'])) {
    include($phphg_real_path . 'includes/page_header.php');
    $display = "You didnt fill in your username and password";
    $template->getFile(array(
                       'error' => 'error.tpl')
    );
    $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
                       'DISPLAY' => $display)
    );
    $template->parse("error");
    include($phphg_real_path . 'includes/page_footer.php');
    exit();
}

$password = md5($HTTP_POST_VARS['password']);

$sql = "SELECT * FROM ".$prefix."_admin WHERE username='$HTTP_POST_VARS[username]' AND password='$password' AND activated='1'";
$result = $db->query($sql);

$num = $db->num($result);

if($num > 0) {
    while($row = $db->fetch($result)) {
          foreach($row AS $key => $val) {
                  $$key = stripslashes($val);
          }
          setcookie("loged","yes",time()+3600, $script_path);
          setcookie("username","$username",time()+3600, $script_path);
          setcookie("user_level","$user_level",time()+3600, $script_path);
          $session = uniqid('login_');
          $display = "Thank you for logging in.";
          $link = "admin/index.php";
          include($phphg_real_path . 'includes/page_header.php');
          $template->getFile(array(
                             'success' => 'success.tpl')
          );
          $template->add_vars(array(
                             'L_SUCCESS' => $lang['success'],
                             'DISPLAY' => $display,
                             'LINK' => $link)
          );
          $template->parse("success");
          include($phphg_real_path . 'includes/page_footer.php');
    }
} else {
    include($phphg_real_path . 'includes/page_header.php');
    $display = "You could not be logged in. Please make sure your username and password are correct.";
    $template->getFile(array(
                       'error' => 'error.tpl')
    );
    $template->add_vars(array(
                       'L_ERROR' => $lang['error'],
                       'DISPLAY' => $display)
    );
    $template->parse("error");
    include($phphg_real_path . 'includes/page_footer.php');
    exit();
}
?> 