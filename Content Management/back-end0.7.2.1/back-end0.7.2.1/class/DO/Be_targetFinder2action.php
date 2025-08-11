<?php
/**
 * Table Definition for be_targetFinder2action
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_targetFinder2action extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_targetFinder2action';          // table name
    var $targetFinderID;                  // int(5)  not_null primary_key unsigned
    var $actionID;                        // int(5)  not_null primary_key multiple_key unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_targetFinder2action',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
