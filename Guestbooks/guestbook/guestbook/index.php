<?php
/****************************************************************/
/*                       phphg Guestbook                        */
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
$phphg_real_path = "./";
include($phphg_real_path . 'common.php');

$install = @opendir($phphg_real_path . 'install/');

if($install) {
   include($phphg_real_path . 'includes/page_header.php');
   $display = "Please make sure you delete the install directory";
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
} else {
   $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

   $sql = "SELECT ip FROM ".$prefix."_banned WHERE ip='$ip'";
   $result = $db->query($sql);

   $num2 = $db->num($result);

   if($num2 > 0) {
      include($phphg_real_path . 'includes/page_header.php');
      $display = "Sorry but you were banned. Please contact the admin.";
      $template->getFile(array(
			 'error' => 'error.tpl')
      );
      $template->add_vars(array(
                          'L_ERROR' => $lang['error'],
       
                          'L_DISPLAY' => $display)
      );
      $template->parse("error");
      include($phphg_real_path . 'includes/page_footer.php');
      exit();
   } else {
      $sql = "SELECT * FROM ".$prefix."_smilies";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            if(!isset($smilie_code)) {
               $smilie_code = array($row['code']);
            } else {
               array_push($smilie_code, $row['code']);
            }
       
            if(!isset($smilie_url)) {
               $smilie_url = array($row['url']);
            } else {
               array_push($smilie_url, $row['url']);
            }

            if(!isset($smilie_name)) {
               $smilie_name = array($row['name']);
            } else {
               array_push($smilie_name, $row['name']);
            }
      }

      $code = count($smilie_code);
      $url = count($smilie_url);

      $sql = "SELECT * FROM ".$prefix."_filter";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            if(!isset($filter_word)) {
               $filter_word = array($row['word']);
            } else {
               array_push($filter_word, $row['word']);
            }

            if(!isset($filter_replace)) {
               $filter_replace = array($row['replace']);
            } else {
               array_push($filter_replace, $row['replace']);
            }
      }

      $word = count($filter_word);
      $replace = count($filter_replace);

      if(!isset($HTTP_GET_VARS['page'])) {
         $page = 1;
      } else {
         $page = $HTTP_GET_VARS['page'];
      }

      $limit = $limit;
      $from = ($page * $limit) - $limit;

      $sql = "SELECT * FROM ".$prefix."_message ORDER BY 'date' DESC LIMIT $from,$limit";
      $result = $db->query($sql);

      $num = $db->num($result);

      if($num < 1) {
         include($phphg_real_path . 'includes/page_header.php');
         $display = "There are no guestbook entires";
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
      } else {
         $entry = "";
         while($row = $db->fetch($result)) {
               $message = $row['message'];
               $name = $row['username'];
	       $location = $row['location'];
	       $date = $row['date'];
	       $email = $row['email'];
	       $web_site = $row['website'];
	       $browser = $row['browser'];

               $message2 = addslashes($message);
               $name2 = addslashes($name);
           

               $date = strtotime($date);
	       $post_date = date('D F d Y h:i:s', $date);
		
	      if($web_site == "") {
	         $website = "";
              } else {
                 $website = "<a href=\"$web_site\" target=\"_blank\"><img src=\"templates/" . $default_theme . "/images/home.gif\" border=\"0\" width=\"15\" height=\"15\" alt=\"Visit Website\" title=\"Visit Website\"></a>";
              }

              for($i = 0; $i < $code; $i++) {
                  $image = "<img src=\"$smilie_dir/$smilie_url[$i]\" border=\"0\" alt=\"$smilie_name[$i]\">";
                  $output = str_replace($smilie_code[$i], $image, $message2);
                  $message2 = $output;
              }

              for($j = 0; $j < $word; $j++) {
                  $output = str_replace($filter_word[$j], $filter_replace[$j], $message2);
                  $message2 = $output;
              }

              $template->add_block_vars("message", array(
                                         'L_POST' => $lang['posted'],
                                         'L_LOCATION' => $lang['location'],
                                         'L_DATE' => $lang['date'],
                                         
                                         'NAME' => $name2,
                                         'LOCATION' => $location,
                                         'BROWSER' => $browser,
                                         'EMAIL' => $email,
                                         'WEBSITE' => $website,
                                         'DATE' => $post_date,
                                         'MESSAGE' => $message2)
              );

              $entry .= "<td width=\"20%\" valign=\"top\"><font class=\"text\">$lang[posted] " .$name2 . "<br><br>$lang[location]<br>" .$location. "<br><br><img src=\"templates/default/images/ip.gif\" width=\"13\" height=\"15\" border=\"0\" alt=\"Ip Logged\" title=\"Ip Logged\">&nbsp;<img src=\"templates/default/images/browser.gif\" border=\"0\" width=\"16\" height=\"16\" alt=\"Browser: $browser\" title=\"Browser: $browser\">&nbsp;<a href=\"mailto:$email\"><img src=\"templates/default/images/email.gif\" border=\"0\" width=\"15\" height=\"15\" alt=\"Email User\" title=\"Email User\"></a>&nbsp;$website</td>\r\n
                         <td width=\"80%\" valign=\"top\"><font class=\"text\">$lang[date] " .$post_date . "</font><br><hr><font class=\"text\">" . $message2 . "</font></td>\r\n
                         </tr>\r\n";
         }
         $sql2 = "SELECT count(*) FROM ".$prefix."_message";
         $result2 = $db->query($sql2);
         $total_results = $db->result($result2);
         $total_pages = ceil($total_results / $limit);

         if($page > 1) {
            $pageprev = $page - 1;
            $prev = "<a href=\"index.php?page=$pageprev\"><< Prev</a>&nbsp;";
         }

         for($i = 1; $i <= $total_pages; $i++) {
             if($page == $i) {
                $pagenum .= "<font class=\"text\">$i</font>&nbsp";
             } else {
                $pagenum .= "<a href=\"index.php?page=$i\">$i</a>&nbsp;";
             }
         }
   
     if($page < $total_pages) {
         $pagenext = $page + 1;
         $next = "<a href=\"index.php?page=$pagenext\">Next &gt;&gt;</a>&nbsp;";
     }

     include($phphg_real_path . 'includes/page_header.php');
     $template->getFile(array(
			'index' => 'index.tpl')
     );
     $template->add_vars(array(
                        'L_NAME' => $lang['name'],
                        'L_COMMENTS' => $lang['comments'],

                        'PREV' => $prev,
                        'PAGENUM' => $pagenum,
                        'NEXT' => $next)
     );
     $template->parse("index");
     include($phphg_real_path . 'includes/page_footer.php');
  }
}
}
?>