<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                     admin/links.php file                     */
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
      if(!isset($HTTP_GET_VARS['page'])) {
         $page = 1;
      } else {
         $page = $HTTP_GET_VARS['page'];
      }

      $limit = "25";
      $from = ($page * $limit) - $limit;

      $sql = "SELECT * FROM ".$prefix."_links ORDER BY 'name' DESC LIMIT $from,$limit";
      $result = $db->query($sql);

      if($db->num($result) == 0) {
         include('page_header_admin.php');
         $display = "There are no links.";
         $template->getFile(array(
                            'error_links' => 'admin/error_links.tpl')
         );
         $template->add_vars(array(
                             'L_ERROR' => $lang['error'],
                             'DISPLAY' => $display)
         );
         $template->parse("error_links");
         include('page_footer_admin.php');
         exit();
      }

      $link = "";
      while($row = $db->fetch($result)) {
            $link_id = $row['id'];
            $link_name = $row['name'];
            $link_user = $row['username'];
            $activated = $row['activated'];

            if($activated == '0') {
               $activated = "No";
               $activate = " | <a href=\"activate.php?link_id=$link_id\">Activate</a>";
            } else
            if($activated == '1') {
               $activated = "yes";
               $activate = "";
            }

            $template->add_block_vars("links", array(
                                       'NAME' => $link_name,
                                       'USER' => $link_user,
                                       'ACTIVATED' => $activated,
                                       'ID' => $link_id,
                                       'ACTIVATE' => $activate)
            );
      }

      $sql2 = "SELECT count(*) FROM ".$prefix."links";
      $result2 = $db->query($sql2);
      $total_results = $db->result($result2);
      $total_pages = ceil($total_results / $limit);

      if($page > 1) {
         $pageprev = $page - 1;
         $prev = "<a href=\"links.php?page=$pageprev\">Prev</a>&nbsp;";
      } else {
         $prev = "";
      }
      $pagenum = "";
      for($i = 1; $i <= $total_pages; $i++) {
          if($page == $i) {
             $pagenum .= "<font class=\"text\">$i</font>&nbsp;";
          } else {
             $pagenum .= "<a href=\"links.php?page=$i\">$i</a>&nbsp;";
          }
      }
     
      if($page < $total_pages) {
          $pagenext = $page + 1;
          $next = "<a href=\"link.php?page=$pagenext\">Next</a>&nbsp;";
      } else {
          $next = "";
      }

      include('page_header_admin.php');
      $template->getFile(array(
                         'links' => 'admin/links.tpl')
      );
      $template->add_vars(array(
                          'L_NAV' => $lang['navigation'],
                          'L_NAME' => $lang['web_name'],
                          'L_USERNAME' => $lang['username'],
                          'L_ACT' => $lang['activated'],
                          'L_ACTION' => $lang['action'],
                          'L_ADD' => $lang['add_link'],
                          'L_WEB' => $lang['website'],
                          'L_URL' => $lang['url'],
                          'L_WIDTH' => $lang['width'],
                          'L_HEIGHT' => $lang['height'],
                          'L_DESC' => $lang['desc'],
                          'L_BANNER' => $lang['banner'],

                          'LINK' => $link,
                          'PREV' => $prev,
                          'PAGENUM' => $pagenum,
                          'NEXT' => $next)
      );
      $template->parse("links");
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
      $display = "You are not logged in Please do so <a href=\"edit_link.php\">Here</a>.";
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