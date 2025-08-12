<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

  $breadcrumb->add(NAVBAR_TITLE, escs_href_link(FILENAME_PRODUCTS_NEW));

  $content = CONTENT_PRODUCTS_NEW;

  require(DIR_WS_TEMPLATES . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
