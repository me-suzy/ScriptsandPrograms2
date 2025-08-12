<?php
/****************************************************************/
/*                        phpht Topsites                        */
/*                        index.php file                        */
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

$install = @opendir($phpht_real_path . 'install/');

if($install) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Please delete the install directory and chmodd config.php to 644.";
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
   if(!isset($HTTP_GET_VARS['page'])) {
      $page = 1;
   } else {
      $page = $HTTP_GET_VARS['page'];
   }

   $limit = $limit;
   $from = ($page * $limit) - $limit;

   $sql = "SELECT * FROM ".$prefix."_links WHERE activated='1' ORDER BY 'hits_in' DESC LIMIT $from, $limit";
   $result = $db->query($sql);

   $number = 1;
 
   if($db->num($result) == 0) {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "There are no websites";
      if(isset($HTTP_COOKIE_VARS['loged']) == "yes") {
         if(isset($HTTP_COOKIE_VARS['user_level']) == '1') {
            $admin = "<a href=\"admin/\">Admin CP</a>";
         } else {
            $admin = "";
         }
      } else {
         $admin = "";
      }

      $template->getFile(array(
                         'error' => 'error_index.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display,
                         'ADMIN' => $admin,
                         'U_ADMIN' => "admin.php")
      );
      $template->parse("error");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   }
   $list = "";
   while($row = $db->fetch($result)) {
         $link_id = $row['id'];
         $link_url = $row['url'];
         $banner = $row['banner'];
         $height = $row['height'];
         $width = $row['width'];
         $desc = $row['description'];
         $name = $row['name'];
         $hits_in = $row['hits_in'];
         $hits_out = $row['hits_out'];

         if($banner == "") {
            $banner = "";
         } else {
            $banner = "<img src=\"$banner\" border=\"0\" width=\"$width\" height=\"$height\" alt=\"$name\">";
         }

         if($hits_in == "") {
            $hits_in = "0";
         } else {
            $hits_in = $hits_in;
         }

         if($hits_out ==	"") {
            $hits_out =	"0";
         } else {
            $hits_out =	$hits_out;
         }

         if($number%2) {
            $template->add_block_vars("topsites", array(
                                       'NUMBER' => $number,
                                       'ID' => $link_id,
                                       'BANNER' => $banner,
                                       'NAME' => $name,
                                       'DESC' => $desc,
                                       'HITS_IN' => $hits_in,
                                       'HITS_OUT' => $hits_out)
            );
         } else {
            $template->add_block_vars("topsites", array(
                                       'NUMBER' => $number,
                                       'ID' => $link_id,
                                       'BANNER' => $banner,
                                       'NAME' => $name,
                                       'DESC' => $desc,
                                       'HITS_IN' => $hits_in,
                                       'HITS_OUT' => $hits_out)
            );
         }
         $number++;
   }

   $sql2 = "SELECT count(*) FROM ".$prefix."_links WHERE activated='1'";
   $result2 = $db->query($sql2);
   $total_results = $db->result($result2);
   $total_pages = ceil($total_results / $limit);

   if($page > 1) {
      $pageprev = $page - 1;
      $prev = "<a href=\"index.php?page=$pageprev\"><< Prev</a>&nbsp;";
   } else {
      $prev = "";
   }

   $pagenum = "";

   for($i = 1; $i <= $total_pages; $i++) {
       if($page == $i) {
          $pagenum .= "<font class=\"text\">$i</font>&nbsp;";
       } else {
          $pagenum .= "<a href=\"index.php?page=$i\">$i</a>&nbsp;";
       }
   }

   if($page < $total_pages) {
      $pagenext = $page + 1;
      $next = "<a href=\"index.php?page=$pagenext\">Next</a>";
   } else {
      $next = "";
   }

   if(isset($HTTP_COOKIE_VARS['loged']) == "yes") {
         if(isset($HTTP_COOKIE_VARS['user_level']) == '1') {
            $admin = "<a href=\"admin/\">Admin CP</a>";
         } else {
            $admin = "";
         }
      } else {
         $admin = "";
      }

   include($phpht_real_path . 'includes/page_header.php');
   $template->getFile(array(
                      'index' => 'index.tpl')
   );
   $template->add_vars(array(
                      'SITE' => $lang['site'],
                      'HITS_IN' => $lang['hits_in'],
                      'HITS_OUT' => $lang['hits_out'],
                      'L_HOME' => $lang['home'],
                      'L_ADMIN' => $lang['admin'],

                      'PREV' => $prev,
                      'PAGENUM' => $pagenum,
                      'NEXT' => $next,
                      'ADMIN' => $admin,

                      'U_HOME' => $site_url,
                      'U_ADMIN' => "admin.php")
   );
   $template->parse("index");
   include($phpht_real_path . 'includes/page_footer.php');
}
?>