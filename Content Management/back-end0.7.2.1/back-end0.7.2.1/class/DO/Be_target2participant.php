<?php
/**
 * Table Definition for be_target2participant
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_target2participant extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_target2participant';           // table name
    var $targetFinderID;                  // int(5)  not_null primary_key unsigned
    var $participantID;                   // int(5)  not_null primary_key unsigned
    var $targetID;                        // int(5)  not_null unsigned
    var $lastChecked;                     // int(10)  unsigned
    var $success;                         // int(1)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_target2participant',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
