<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                  admin/message.php file                      */
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
      if(!isset($HTTP_GET_VARS['page'])) {
         $page = 1;
      } else {
         $page = $HTTP_GET_VARS['page'];
      }
      
      $limit = $limit;
      $from = ($page * $limit) - $limit;

      $sql = "SELECT * FROM ".$prefix."_message ORDER BY 'name' DESC LIMIT $from, $limit";
      $result = $db->query($sql);

      $num = $db->num($result);
  
      if($num < 1) {
         include('page_header.php');
         $display = "There are no guestbook entries.";
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
      } else {
         $entry = "";
         while($row = $db->fetch($result)) {
               $name = $row['username'];
               $location = $row['location'];
               $email = $row['email'];
               $id = $row['id'];

               $template->add_block_vars("message", array(
                                         'NAME' => $name,
                                         'LOCATION' => $location,
                                         'EMAIL' => $email,
                                         'ID' => $id)
               );
         }

         $sql2 = "SELECT count(*) FROM ".$prefix."_message";
         $result2 = $db->query($sql);
         $total_results = $db->result($result2);
         $total_pages = ceil($total_results / $limit);

         if($page > 1) {
            $pageprev = $page - 1;
            $prev = "<a href=\"message.php?page=$pageprev\">&lt;&lt; Prev</a>&nbsp;";
         }

         for($i = 1; $i <= $numpages; $i++) {
            if($page == $i) {
               $pagenum .= "<font class=\"text\">$i</font>&nbsp";
            } else {
               $pagenum .= "<a href=\"message.php?page=$i\">$i</a>&nbsp;";
            }
         }
   
         if($page < $total_pages) {
             $pagenext = $page + 1;
             $next = "<a href=\"message.php?page=$pagenext\">Next &gt;&gt;</a>&nbsp;";
         }

         include('page_header.php');
         $template->getFile(array(
                            'message' => 'admin/message.tpl')
         );
         $template->add_vars(array(
                            'L_NAME' => $lang['name'],
                            'L_LOCATION' => $lang['location'],
                            'L_EMAIL' => $lang['email'],
                            'L_ACTION' => $lang['action'],
                            'L_NAV' => $lang['navigation'],
                            'L_HOME' => $lang['home'],
                            'L_EDIT_MESSAGE' => $lang['edit_message'],
                            'L_BAN' => $lang['ban'],
                            'L_EDIT_SMILE' => $lang['edit_smile'],
                            'L_EDIT_WORD' => $lang['edit_word'],
                            'L_LOGOUT' => $lang['logout'],
                            'L_SETTINGS' => $lang['settings'],
                            'L_THEME' => $lang['themes'],
                            'L_LANG' => $lang['langs'],

                            'PREV' => $prev,
                            'PAGENUM' => $pagenum,
                            'NEXT' => $next,

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
         $template->parse("message");
         include('page_footer.php');
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