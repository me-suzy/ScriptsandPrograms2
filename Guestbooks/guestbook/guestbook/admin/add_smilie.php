<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                  admin/add_smilie.php file                   */
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
      if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['file_name']) || (!$HTTP_POST_VARS['code'])) {
          include("page_header.php");
          $display = "You did not fill in the required fields. Please go back and try again.";
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

      $sql = "SELECT name FROM ".$prefix."_smilies WHERE name='$HTTP_POST_VARS[name]'";
      $result = $db->query($sql);

      $sql2 = "SELECT url FROM ".$prefix."_smilies WHERE url='$HTTP_POST_VARS[file_name]'";
      $result2 = $db->query($sql2);

      $sql3 = "SELECT code FROM ".$prefix."_smilies WHERE code='$HTTP_POST_VARS[code]'";
      $result3 = $db->query($sql3);

      $num = $db->num($result);
      $num2 = $db->num($result2);
      $num3 = $db->num($result3);

      if(($num > 0) || ($num2 > 0) || ($num3 > 0)) {
          $display = "Please fix the following errors<br>";
          if($num > 0) {
             $display .= "That smilie name is already taken. Please choose another one.<br>";
             unset($HTTP_POST_VARS['name']);
          }
          if($num2 > 0) {
             $display .= "That Image name is already taken. Please choose another one.<br>";
             unset($HTTP_POST_VARS['file_name']);
          }
          if($num3 > 0) {
             $display .= "That smilie code is already taken. Please choose another one.<br>";
             unset($HTTP_POST_VARS['code']);
          }
          include("page_header.php");
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

      $sql = "INSERT INTO ".$prefix."_smilies (name, code, url) VALUES ('$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[code]', '$HTTP_POST_VARS[file_name]')";
      $result = $db->query($sql);

      if(!$result) {
         include("page_header.php");
         $display = "Could not add the smilie";
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
         $display = "The smilie has been added.";
         $link = "smilie.php";
         $template->getFile(array(
                            'success' => 'admin/success.tpl')
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