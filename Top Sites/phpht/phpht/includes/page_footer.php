<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                    page_footer.php file                      */
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
$sql = "SELECT copyright FROM ".$prefix."_config";
$result = $db->query($sql);

$row = $db->fetch($result); 
$copyright = $row['copyright'];


$template->getFile(array( 
                   'footer' => 'footer.tpl')
);
$template->add_vars(array(
                    'COPYRIGHT' => $copyright)
);
$template->parse("footer");
?>