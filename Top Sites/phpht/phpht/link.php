<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                        link.php file                         */
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
$sql = "SELECT * FROM ".$prefix."_links WHERE id='$HTTP_GET_VARS[id]'";
$result = $db->query($sql);

$num = $db->num($result);

if($num < 1) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "There are no websites";
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
   while($row = $db->fetch($result)) {
         $link_url = $row['url'];
         $hits_out = $row['hits_out']+1;
         
         $sql = "UPDATE ".$prefix."_links SET hits_out='$hits_out' WHERE id='$HTTP_GET_VARS[id]'";
         $result = $db->query($sql);

         header("Location: $link_url");
    }
  }
?>