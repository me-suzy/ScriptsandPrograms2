<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  OSC-Affiliate

  Contribution based on:

  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

  function affiliate_check_url($url) {
    return eregi("^https?://[a-z0-9]([-_.]?[a-z0-9])+[.][a-z0-9][a-z0-9/=?.&\~_-]+$",$url);
  }

  function affiliate_insert ($sql_data_array, $affiliate_parent = 0) {
    // LOCK TABLES
    escs_db_query("LOCK TABLES " . TABLE_AFFILIATE . " WRITE");
    if ($affiliate_parent > 0) {
      $affiliate_root_query = escs_db_query("select affiliate_root, affiliate_rgt, affiliate_lft from  " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_parent . "' ");
      // Check if we have a parent affiliate
      if ($affiliate_root_array = escs_db_fetch_array($affiliate_root_query)) {
        escs_db_query("update " . TABLE_AFFILIATE . " SET affiliate_lft = affiliate_lft + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_lft > "  . $affiliate_root_array['affiliate_rgt'] . "  AND affiliate_rgt >= " . $affiliate_root_array['affiliate_rgt'] . " ");
        escs_db_query("update " . TABLE_AFFILIATE . " SET affiliate_rgt = affiliate_rgt + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_rgt >= "  . $affiliate_root_array['affiliate_rgt'] . "  ");


        $sql_data_array['affiliate_root'] = $affiliate_root_array['affiliate_root'];
        $sql_data_array['affiliate_lft'] = $affiliate_root_array['affiliate_rgt'];
        $sql_data_array['affiliate_rgt'] = ($affiliate_root_array['affiliate_rgt'] + 1);
        escs_db_perform(TABLE_AFFILIATE, $sql_data_array);
        $affiliate_id = escs_db_insert_id();
      }
    // no parent -> new root
    } else {
      $sql_data_array['affiliate_lft'] = '1';
      $sql_data_array['affiliate_rgt'] = '2';
      escs_db_perform(TABLE_AFFILIATE, $sql_data_array);
      $affiliate_id = escs_db_insert_id();
      escs_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");
    }
    // UNLOCK TABLES
    escs_db_query("UNLOCK TABLES");
    return $affiliate_id;

  }



////
// Compatibility to older Snapshots
  if (!function_exists('escs_round')) {
    function escs_round($value, $precision) {
      if (PHP_VERSION < 4) {
        $exp = pow(10, $precision);
        return round($value * $exp) / $exp;
      } else {
        return round($value, $precision);
      }
    }
  }

////
// Output a form
  if (!function_exists('escs_draw_form')) {
    function escs_draw_form($name, $action, $method = 'post', $parameters = '') {
      $form = '<form name="' . escs_parse_input_field_data($name, array('"' => '&quot;')) . '" action="' . escs_parse_input_field_data($action, array('"' => '&quot;')) . '" method="' . escs_parse_input_field_data($method, array('"' => '&quot;')) . '"';

      if (escs_not_null($parameters)) $form .= ' ' . $parameters;

      $form .= '>';

      return $form;
    }
  }

////
// This funstion validates a plain text password with an encrpyted password
  if (!function_exists('escs_validate_password')) {
    function escs_validate_password($plain, $encrypted) {
      if (escs_not_null($plain) && escs_not_null($encrypted)) {
// split apart the hash / salt
        $stack = explode(':', $encrypted);

        if (sizeof($stack) != 2) return false;

        if (md5($stack[1] . $plain) == $stack[0]) {
          return true;
        }
      }

      return false;
    }
  }

////
// This function makes a new password from a plaintext password.
  if (!function_exists('escs_encrypt_password')) {
    function escs_encrypt_password($plain) {
      $password = '';

      for ($i=0; $i<10; $i++) {
        $password .= escs_rand();
      }

      $salt = substr(md5($password), 0, 2);

      $password = md5($salt . $plain) . ':' . $salt;

      return $password;
    }
  }

////
// Return a random value
  if (!function_exists('escs_rand')) {
    function escs_rand($min = null, $max = null) {
      static $seeded;

      if (!isset($seeded)) {
        mt_srand((double)microtime()*1000000);
        $seeded = true;
      }

      if (isset($min) && isset($max)) {
        if ($min >= $max) {
          return $min;
        } else {
          return mt_rand($min, $max);
        }
      } else {
        return mt_rand();
      }
    }
  }
?>