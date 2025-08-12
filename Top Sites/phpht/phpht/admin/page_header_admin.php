<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*             admin/page_header_admin.php file                 */
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
$sql = "SELECT * FROM ".$prefix."config";
$result = $db->query($sql);

while($row = $db->fetch($result)) {
     $site_title = $row['title'];
     $site_url = $row['domain'];
}

$template->getFile(array(
                   'header' => 'admin/header.tpl')
);
$template->add_vars(array(
                   'SITE_TITLE' => $site_title)
);
$template->parse("header");
?>