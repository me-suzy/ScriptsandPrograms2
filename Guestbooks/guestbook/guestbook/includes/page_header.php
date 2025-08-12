<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                    page_header.php file                      */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                   support@hintondesign.org                   */
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
	$site_title = $row['site_title'];
        $site_title2 = addslashes($site_title);
}

$template->getFile(array(
		   'header' => 'header.tpl')
);
$template->add_vars(array(
                    'L_VIEW' => $lang['view'],
                    'L_SIGN' => $lang['sign'],
                    'L_ADMIN' => $lang['admin'],

                    'SITE_TITLE' => $site_title2,
                    'U_VIEW' => 'index.php',
                    'U_SIGN' => 'sign.php',
                    'U_ADMIN' => 'admin.php')
);
$template->parse("header");
?>
