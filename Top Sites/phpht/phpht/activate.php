<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                      activate.php file                       */
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

$id = $HTTP_GET_VARS['id'];
$code = $HTTP_GET_VARS['code'];

$sql_check = "SELECT * FROM ".$prefix."_users WHERE userid='$id' AND password='$code' AND activated='1'";
$result = $db->query($sql_check2);

$sql_check2 = "SELECT * FROM ".$prefix."_links WHERE id='$id' AND activated='1'";
$result2 = $db->query($sql_check2);

$num = $db->num($result);
$num2 = $db->num($result2);

if(($num > 0) || ($num2 > 0)) {
    include($phpht_real_path . 'includes/page_header.php');
    $display = "Your are already activated. Click <a href=\"index.php\">Here</a> to go back to the main page.";
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
    $sql = "UPDATE ".$prefix."_users SET activated='1' WHERE userid='$id'";
    $result = $db->query($sql);

    $sql = "UPDATE ".$prefix."_links SET activated='1' WHERE id='$id'";
    $result = $db->query($sql);

    if(!$result) {
       include($phpht_real_path . 'includes/page_header.php');
       $display = "Your could not be activated.";
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
       $display = "You have been activated.";
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