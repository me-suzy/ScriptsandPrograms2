<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

  class objectInfo {

// class constructor
    function objectInfo($object_array) {
      reset($object_array);
      while (list($key, $value) = each($object_array)) {
        $this->$key = escs_db_prepare_input($value);
      }
    }
  }
?>
