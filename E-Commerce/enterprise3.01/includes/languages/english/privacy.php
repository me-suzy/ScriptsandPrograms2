<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Privacy Notice');
define('HEADING_TITLE', 'Privacy Notice');
$privacy_path = DIR_FS_CATALOG . DIR_WS_INCLUDES . 'editable_privacy.php';
$file = fopen($privacy_path, "r");
$file_contents = fread($file, filesize($privacy_path));
fclose($file);
define('TEXT_INFORMATION', $file_contents);
?>