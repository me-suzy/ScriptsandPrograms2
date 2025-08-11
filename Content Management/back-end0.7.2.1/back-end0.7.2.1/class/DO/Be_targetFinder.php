<?php
/**
 * Table Definition for be_targetFinder
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_targetFinder extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_targetFinder';                 // table name
    var $targetFinderID;                  // int(5)  not_null primary_key unsigned
    var $countryID;                       // string(3)  not_null
    var $targetTypeName;                  // string(30)  not_null
    var $active;                          // int(1)  not_null unsigned
    var $targetFinderClassName;           // string(40)  not_null
    var $targetFinderClassVersion;        // int(4)  not_null unsigned
    var $targetFinderParameters;          // string(200)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_targetFinder',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
