<?php

# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * functions and definitions related to handling errod
 *
 * do not confuse with...
 * - pages/error.inc (display an error-page)
 *
 * 
 * requires: php5
 *
 * called from: index.php
 *
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby:
 *
 */

DEFINE('ERROR_NOTE', 1);
DEFINE('ERROR_WARNING', 2);
DEFINE('ERROR_FATAL', 3);
DEFINE('ERROR_BUG', 4);
DEFINE('ERROR_RIGHTS', 5);
DEFINE('ERROR_DATASTRUCTURE', 6);

/**
* custom exception provides formatted output
*
*/
class myException extends Exception {
  public $backtrace;

  public function __construct($message=false, $code=false) {
    parent::__construct($message, $code);
    $this->backtrace= debug_backtrace();
  }

  public function __toString() {
    $buffer='';
    $buffer.= "<pre>";

    $buffer.=renderBacktrace($this->backtrace);
    $buffer.= "Exception";
    $buffer.= "</pre>";
    return $buffer;
  }
}



?>