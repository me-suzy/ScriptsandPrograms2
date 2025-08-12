<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: template.php                                         *
 *        Copyright: (C) 2002 Jan Sorgalla                                *
 *            Email: jan@4homepages.de                                    *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.7                                                  *
 *                                                                        *
 *    Never released without support from: Nicky (http://www.nicky.net)   *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/
if (!defined('ROOT_PATH')) {
  die("Security violation");
}

class Template {
  
  var $no_error = 0;
  var $key_cache = array();
  var $val_cache = array();
  var $template_cache = array();
  var $template_path;
  var $template_extension = "html";
  var $start = "{";
  var $end = "}";

  function Template($template_path = "") {
    if (!@is_dir($template_path)) {
      $this->error("Couldn't open Template-Pack ".$template_path, 1);
    }
    $this->template_path = $template_path;
  }

  function set_identifiers($start, $end){
    $this->start = $start;
    $this->end = $end;
  }

  function register_vars($var_name, $value = "") {
    if (!is_array($var_name)) {
      if (!empty($var_name)) {
        $value = preg_replace(array('/\$([0-9])/', '/\\\\([0-9])/'), array('&#36;\1', '&#92;\1'), $value);
        $this->key_cache[$var_name] = "/".$this->add_identifiers($var_name)."/";
        $this->val_cache[$var_name] = $value;
      }
    }
    else {
      foreach ($var_name as $key => $val) {
        if (!empty($key)) {
          $val = preg_replace(array('/\$([0-9])/', '/\\\\([0-9])/'), array('&#36;\1', '&#92;\1'), $val);
          $this->key_cache[$key] = "/".$this->add_identifiers($key)."/";
          $this->val_cache[$key] = $val;
        }
      }
    }
    return;
  }

  function un_register_vars($var_list) {
    $vars = explode(",", $var_list);
    foreach ($vars as $key => $val) {
      unset($this->key_cache[$val]);
      unset($this->val_cache[$val]);
    }
    return;
  }

  function add_identifiers($var_name) {
    return preg_quote($this->start.$var_name.$this->end);
  }

  function cache_templates($template_list) {
    $template_list = explode(",", $template_list);
    foreach ($template_list as $val) {
      if (!isset($this->template_cache[$val])) {
        $this->template_cache[$val] = $this->get_template($val);
      }
    }
  }

  function get_template($template) {
    if (!isset($this->template_cache[$template])) {
      $path = $this->template_path."/".$template.".".$this->template_extension;
      $line = @implode("", @file($path));
      if (empty($line)) {
        $this->error("Couldn't open Template ".$path, 1);
      }
      $this->template_cache[$template] = $line;
    }
    return $this->template_cache[$template];
  }

  function replace_if($template) {
    foreach ($this->key_cache as $key => $val) {
      if (empty($this->val_cache[$key]) || $this->val_cache[$key] == REPLACE_EMPTY) {
        $reg = "/".$this->start."if[ \t\r\n]+".$key.$this->end."(.*)".$this->start."endif[ \t\r\n]+".$key.$this->end."/siU";
        $template = preg_replace($reg, "", $template);
      }
    }
    return $template;
  }

  function parse_template($template_name) {
    $template = $this->get_template($template_name);
    $template = $this->replace_if ($template);
    $template = preg_replace($this->key_cache, $this->val_cache, $template);
    return $template;
  }

  function parse_array($array) {
    foreach ($array as $key => $val) {
      $array[$key] = (is_array($val)) ? $this->parse_array($val) : preg_replace($this->key_cache, $this->val_cache, $val);
    }
    return $array;
  }

  function print_template($template) {
    $template = $this->clean_template($template);
    if (EXEC_PHP_CODE) {
      $code = $this->exec_php_code($template);
      eval($code);
    }
    else {
      $code = preg_replace("/<\[\?|%](php|=)+( \r\n)*(.*)[\?|%]>/siU", "", $template);
      echo $code;
    }
  }

  function exec_php_code($code) {
    $code = str_replace('\\', '\\\\', $code);
    $code = str_replace('\'', '\\\'', $code);
    $new_lines = array();
    $is_code = 0;
    $lines = explode("\n", $code);
    foreach ($lines as $line) {
      //$line = trim($line);
      if (preg_match('/<[\?|%](php|=)+/', $line, $regs)) {
        $line = preg_replace('/<[\?|%](php|=)?/i', '', $line);
        $line = ((!empty($regs[1]) && $regs[1] == "=") ? "echo " : "").$line;
        $is_code = 1;
      }
      if ($is_code) {
        $line = str_replace ('\\\'', '\'', $line);
        $line = str_replace ('\\\\', '\\', $line);
        if (preg_match('/[\?|%]>/', $line)) {
          $line = preg_replace('/[\?|%]>/', '', $line);
          $is_code = 0;
        }
      }
      else {
        $line = 'echo \''.$line.'\'."\\n";';
      }
      $new_lines[] = $line;
    }
    return implode("\n", $new_lines);
  }

  function clean_template($template) {
    $search_array = array(
      "/".$this->start."[^ \t\r\n".$this->end."]+".$this->end."/",
      "/".$this->start."if[ \t\r\n]+[^ \t\r\n".$this->end."]+".$this->end."/",
      "/".$this->start."endif[ \t\r\n]+[^ \t\r\n".$this->end."]+".$this->end."/",
      "/&#36;([0-9])/",
      "/&#92;([0-9])/"
    );
    $replace_array = array(
      "",
      "",
      "",
      '$\1',
      '\\\1'
    );
    $template = preg_replace($search_array, $replace_array, $template);
    return $template;
  }

  function error($errmsg, $halt = 0) {
    if (!$this->no_error) {
      echo "<br /><font color='#FF0000'><b>Template Error</b></font>: ".$errmsg."<br />";
      if ($halt) {
        exit;
      }
    }
  }
} // end of class
?>