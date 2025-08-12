<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                    admin/add_theme.php file                  */
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

if($_COOKIE['loged'] == 'yes') {
   if($_COOKIE['user_level'] == '1') {
      if(!$HTTP_POST_VARS['name']) {
         include("page_header.php");
         $display = "Please fill in the name field.";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("page_footer.php");
         exit();
      }

      $sql = "SELECT name FROM ".$prefix."_themes WHERE name='$HTTP_POST_VARS[name]'";
      $result = $db->query($sql);

      $num = $db->num($result);
     
      if($num > 0) {
         include("page_header.php");
         $display = "That Theme has already been entered. Please choose another one.";
         unset($HTTP_POST_VARS['name']);
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("page_footer.php");
         exit();
      }

      $sql = "INSERT INTO ".$prefix."_themes (name) VALUES ('$HTTP_POST_VARS[name]')";
      $result = $db->query($sql);

      if(!$result) {
         include("page_header.php");
         $display = "Could not add the Theme.";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->add_vars(array(
                            'L_ERROR' => $lang['error'],
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include("page_footer.php");
         exit();
      } else {
         include("page_header.php");
         $display = "The Theme has been added.";
         $link = "theme.php";
         $template->getFile(array(
                            'success' => 'admin/success.tpl')
         );
         $template->add_vars(array(
                            'L_SUCCESS' => $lang['success'],
                            'DISPLAY' => $display,
                            'LINK' => $link)
         );
         $template->parse("success");
         include("page_footer.php");
         exit();
      }
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