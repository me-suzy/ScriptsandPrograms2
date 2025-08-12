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
$sql = "SELECT * FROM ".$prefix."_config";
$result = $db->query($sql);

while($row = $db->fetch($result)) {
     $site_title = $row['title'];
     $message2 = $row['message'];
     $site_url = $row['domain'];
}

$template->getFile(array(
                   'header' => 'header.tpl')
);
$template->add_vars(array(
                   'L_ADMIN' => $lang['admin'],
                   'L_HOME' => $lang['home'],

                   'SITE_TITLE' => $site_title,
                   'MESSAGE' => $message2,
 
                   'U_HOME' => $site_url,
                   'U_ADMIN' => 'admin.php')
);
$template->parse("header");
?>