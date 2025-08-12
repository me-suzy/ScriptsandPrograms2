<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                    page_footer.php file                      */
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
$sql = "SELECT copyright FROM ".$prefix."_config";
$result = $db->query($sql);

while($row = $db->fetch($result)) {
	$copyright = $row['copyright'];
}

if($_COOKIE['loged'] == 'yes') {
   if($_COOKIE['user_level'] == '1') {
      $admin = "<a href=\"admin\">Admin CP</a>";
   }
}

$template->getFile(array(
		   'footer' => 'footer.tpl')
);
$template->add_vars(array(
		    'COPYRIGHT' => $copyright,
                    'ADMIN' => $admin)
);
$template->parse("footer");
?>
