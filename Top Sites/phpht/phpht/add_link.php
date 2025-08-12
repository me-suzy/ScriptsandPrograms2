<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                      add_link.php file                       */
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
include($phpht_real_path . 'includes/page_header.php');
   $template->getFile(array(
                      'add_link' => 'add_link.tpl')
   );
   $template->add_vars(array(
                      'L_LINK' => $lang['link'],
                      'L_WEBSITE' => $lang['website'],
                      'L_URL' => $lang['url'],
                      'L_BANNER' => $lang['banner'],
                      'L_HEIGHT' => $lang['height'],
                      'L_WIDTH' => $lang['width'],
                      'L_USERNAME' => $lang['username'],
                      'L_PASSWORD' => $lang['password'],
                      'L_PASSWORD2' => $lang['password2'],
                      'L_DESC' => $lang['desc'],
                      'L_EMAIL' => $lang['email'])
   );
   $template->parse("add_link");
   include($phpht_real_path . 'includes/page_footer.php');
?>