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


/**
 * export_variable
 *  return a string that assigns variables to the given value
 */
function export_variable($variableName, &$value, $depth = 0) {
  
  if(is_array($value)) {
    /**
     * avoid infinite loop
     */
    if($depth > 3) return '';

    $export = "$".$variableName."=array();\n";
    foreach($value as $k => $v) {
      $export .= export_variable(is_int($k) ? $variableName."[".$k."]" : 
                                              $variableName."['".addslashes($k)."']",
                                 $v, $depth+1);
    }
    return $export;
  } else {
    return (is_scalar($value) && !is_string($value) ?
            "$".$variableName." = ".$value.";\n" :
            "$".$variableName." = '".str_replace('\"', '"', addslashes($value))."';\n");
  }

}

