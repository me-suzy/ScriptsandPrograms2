<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                        admin.php file                        */
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
include($phphg_real_path . 'includes/page_header.php');

$template->getFile(array(
                   'admin' => 'admin.tpl')
);
$template->add_vars(array(
                   'L_LOGIN' => $lang['login'],
                   'L_USERNAME' => $lang['username'],
                   'L_PASSWORD' => $lang['password'])
);
$template->parse("admin");
include($phphg_real_path . 'includes/page_footer.php');
?>