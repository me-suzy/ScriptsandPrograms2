<?php
/**
 * Table Definition for be_target
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_target extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_target';                       // table name
    var $actionID;                        // int(5)  not_null primary_key unsigned
    var $contactID;                       // int(5)  not_null primary_key unsigned
    var $notes;                           // blob(65535)  not_null blob
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_target',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
