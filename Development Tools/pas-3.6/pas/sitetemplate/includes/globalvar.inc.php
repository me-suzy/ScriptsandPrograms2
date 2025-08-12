<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**
   * Global events to manage Event specific global vars.
   * This is where the Event saved in session are managed and killed.
   *
   * @package PASSiteTemplate
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0
   */

  if (!is_array($globalevents)) {
    $globalevents['Init'] = 0 ;
    session_register("globalevents") ;
  } else {
    while (list($key, $value)= each($globalevents)) {
      if ($value) {
        if((eregi($value, $PHP_SELF)) && ($value) && (is_object($$key))) {
            $params = $$key->getParams() ;
            if (is_array($params)) {
                while(list($name, $value) = each($params)) {
                    $$name = $value ;
                }
            }
            $$key->setFree() ;
        } elseif(is_object($$key) && ($$key->isFree())) {
            $$key->free($key) ;
        }
      }
    }
    if (!is_array($garbagevents)) {
      $garbagevents['init'] = 0 ;
      session_register("garbagevents") ;
    }
    while (list($key, $value)= each($garbagevents)) {
      if(($value) && ($mydb_events[20] != "mydb.registerGlobalEvent" ) && (is_object($$key))) {
         $$key->free($key) ;
      }
    }
  }

?>