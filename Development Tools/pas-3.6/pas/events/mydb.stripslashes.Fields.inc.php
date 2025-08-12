<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**
   *  Event mydb.stripslashes.fields
   *
   * Event that stripslashes from values in array fields when the GPC in on.
   *  It enable compatibility with site using gpc on and other gpc off
   *  Call the event to be executed before mydb.updateRecord or mydb.addRecord with a level lower than 1000
   *
   * <br>- param Array fields array with all the values of the fiels indexed on the name of the field.
   *
   * @package PASEvents
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0   
   */
    /**
     * webide.stripslashesfields
     * Event that strip slashes to fields from forms
     * when GPC is on
     */
    if (is_array($fields)) {
        while(list($key, $value) = each($fields)) {
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value) ;
            }
            $newfields[$key] = $value  ;
        }
        $fields = $newfields ;
        $this->updateParam("fields", $newfields) ;
    }
?>