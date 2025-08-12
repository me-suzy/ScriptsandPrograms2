<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

  function escs_get_languages_directory($code) {
    global $languages_id;

    $language_query = escs_db_query("select languages_id, directory from " . TABLE_LANGUAGES . " where code = '" . escs_db_input($code) . "'");
    if (escs_db_num_rows($language_query)) {
      $language = escs_db_fetch_array($language_query);
      $languages_id = $language['languages_id'];
      return $language['directory'];
    } else {
      return false;
    }
  }
?>