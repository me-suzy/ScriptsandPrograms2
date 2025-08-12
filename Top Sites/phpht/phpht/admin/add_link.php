<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/add_link.php file                    */
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
      if((!$HTTP_POST_VARS['name']) || (!$HTTP_POST_VARS['url']) || (!$HTTP_POST_VARS['desc'])) {
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
          include('page_header_admin.php');
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

       if($HTTP_POST_VARS['width'] > '468') {
          include('page_header_admin.php');
          $display = "Your banner width is too big. It can be no longer than 468 pixels.";
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

       if($HTTP_POST_VARS['height'] > '60') {
          include('page_header_admin.php');
          $display = "Your banner height is too big. It can be no higher than 60 pixels.";
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

       $sql = "SELECT name FROM ".$prefix."_links WHERE name='$HTTP_POST_VARS[name]'";
       $result = $db->query($sql);
    
       $sql2 = "SELECT url FROM ".$prefix."_links WHERE url='$HTTP_POST_VARS[url]'";
       $result2 = $db->query($sql2);

       $num = $db->num($result);
       $num2 = $db->num($result2);

       if(($num > 0) || ($num2 > 0)) {
           $display = "Please fix the following errors.<br>";
           if($num > 0) {
              $display .= "That website has already been submited.<br>";
              unset($HTTP_POST_VARS['name']);
           }
           if($num2 > 0) {
              $display .= "That website url has already been submited.<br>";
              unset($HTTP_POST_VARS['url']);
           }
           include('page_header_admin.php');
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

       $sql = "INSERT INTO ".$prefix."_links (name, url, banner, width, height, description, username, activated) VALUES ('$HTTP_POST_VARS[name]', '$HTTP_POST_VARS[url]', '$HTTP_POST_VARS[banner]', '$HTTP_POST_VARS[width]', '$HTTP_POST_VARS[height]', '$HTTP_POST_VARS[desc]', '$HTTP_COOKIE_VARS[username]', '1')";
       $result = $db->query($sql);

       if(!$result) {
          include('page_header_admin.php');
          $display = "Could not add your website.";
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
       } else {
          include('page_header_admin.php');
          $display = "Website added.";
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