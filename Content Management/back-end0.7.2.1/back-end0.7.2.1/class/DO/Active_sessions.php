<?php
/**
 * Table Definition for active_sessions
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Active_sessions extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'active_sessions';                 // table name
    var $sid;                             // string(32)  not_null primary_key
    var $name;                            // string(32)  not_null primary_key
    var $val;                             // blob(65535)  blob
    var $changed;                         // string(14)  not_null multiple_key

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Active_sessions',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
