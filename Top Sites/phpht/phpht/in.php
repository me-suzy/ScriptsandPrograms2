<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                         in.php file                          */
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

$s = $HTTP_SERVER_VARS['REMOTE_ADDR'];

$sql = "SELECT * FROM ".$prefix."_ip WHERE link_id='$HTTP_GET_VARS[link_id]'";
$result = $db->query($sql);

$num = $db->num($result);

if($num > 0) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "You have already voted.";
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
   $sql = "SELECT hits_in FROM ".$prefix."_links WHERE id='$HTTP_GET_VARS[link_id]'";
   $result = $db->query($sql);

   while($row = $db->fetch($result)) {
         $newcount = $row['hits_in']+1;
   }
   $sql = "INSERT INTO ".$prefix."_ip (ip, link_id) VALUES ('$s', '$HTTP_GET_VARS[link_id]')";
   $result = $db->query($sql);

   $sql = "UPDATE ".$prefix."_links SET hits_in='$newcount' WHERE id='$HTTP_GET_VARS[link_id]'";
   $result = $db->query($sql);

   if(!$result) {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "Could not add your vote";
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
      include($phpht_real_path . 'includes/page_header.php');
      $display = "Thank you for voting.";
      $link = "index.php";
      $template->getFile(array(
                         'success' => 'success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
                         'DISPLAY' => $display,
                         'LINK' => $link)
      );
      $template->parse("success");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   }
}
?>