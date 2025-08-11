<?php
/**
 * Table Definition for psl_quote
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_quote extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_quote';                       // table name
    var $quote;                           // blob(65535)  not_null blob
    var $author;                          // string(40)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_quote',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
