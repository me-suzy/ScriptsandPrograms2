<?php
/**
 * Table Definition for be_linkTextValidation
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_linkTextValidation extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_linkTextValidation';           // table name
    var $linkTextID;                      // int(5)  not_null primary_key
    var $validationState;                 // string(17)  enum
    var $dateValid;                       // int(10)  not_null unsigned
    var $dateChecked;                     // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_linkTextValidation',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
