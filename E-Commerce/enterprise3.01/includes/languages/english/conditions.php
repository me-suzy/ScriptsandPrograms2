<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Conditions of Use');
define('HEADING_TITLE', 'Conditions of Use');
$conditions_path = DIR_FS_CATALOG . DIR_WS_INCLUDES . 'editable_conditions.php';
$file = fopen($conditions_path, "r");
$file_contents = fread($file, filesize($conditions_path));
fclose($file);
define('TEXT_INFORMATION', $file_contents);
?>