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
$copyright = "Powered by <a href=\"http://www.hintondesign.org\">phpht 1.2</a>";

$template->getFile(array( 
                   'footer' => 'install/footer.tpl')
);
$template->add_vars(array(
                    'COPYRIGHT' => $copyright)
);
$template->parse("footer");
?>