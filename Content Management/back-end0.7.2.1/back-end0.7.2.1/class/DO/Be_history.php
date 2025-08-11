<?php
/**
 * Table Definition for be_history
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_history extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_history';                      // table name
    var $id;                              // int(11)  not_null primary_key auto_increment
    var $itemTable;                       // string(32)  not_null multiple_key
    var $itemKey;                         // string(32)  not_null
    var $versionMajor;                    // int(11)  not_null multiple_key
    var $versionMinor;                    // int(11)  not_null
    var $userId;                          // string(32)  not_null
    var $date;                            // int(11)  not_null
    var $content;                         // blob(65535)  blob
    var $hash;                            // string(32)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_history',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
