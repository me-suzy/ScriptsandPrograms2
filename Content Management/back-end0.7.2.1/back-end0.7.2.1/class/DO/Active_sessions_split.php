<?php
/**
 * Table Definition for active_sessions_split
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Active_sessions_split extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'active_sessions_split';           // table name
    var $ct_sid;                          // string(32)  not_null primary_key
    var $ct_name;                         // string(32)  not_null primary_key
    var $ct_pos;                          // string(6)  not_null primary_key
    var $ct_val;                          // blob(65535)  blob
    var $ct_changed;                      // string(14)  not_null multiple_key

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Active_sessions_split',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
