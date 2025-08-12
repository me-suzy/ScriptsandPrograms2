<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                    page_header.php file                      */
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

$template->getFile(array(
                   'header' => 'install/header.tpl')
);
$template->add_vars(array(
                   'L_ADMIN' => $lang['admin'],
                   'L_HOME' => $lang['home'],
 
                   'U_HOME' => $site_url,
                   'U_ADMIN' => 'admin.php')
);
$template->parse("header");
?>