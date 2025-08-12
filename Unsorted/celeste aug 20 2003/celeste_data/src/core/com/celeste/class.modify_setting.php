<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

ceUse('function.export_variable');

class modify_setting {
  var $setting_file = '';
  var $settings = '';

  function modify_setting ($setting_file) {
    $this->setting_file = $setting_file;
    $this->settings = readfromfile($setting_file);
  }

  function cover() {
    $this->settings = "<?php\n\n";
  }

  function set($variable, $value, $quote = 1) {
    if(strpos('$', $variable) == false) {
      if ($quote) {
        $this->settings = preg_replace("/define\(\s*\'$variable\'\s*\,\s*\'(.*)\'\s*\)\;/", "define('$variable','$value');", $this->settings);
      } else {
        $this->settings = preg_replace("/define\(\s*\'$variable\'\s*\,\s*(.+)\s*\)\;/", "define('$variable',$value);", $this->settings);
      }
    } else {


    }
  }

  function setArray($variable, &$value) {
    $this->settings .= export_variable($variable, $value);
  }

  function save() {
    writetofile($this->setting_file, $this->settings);
  }

}
