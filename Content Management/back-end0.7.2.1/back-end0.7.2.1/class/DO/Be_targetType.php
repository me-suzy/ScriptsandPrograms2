<?php
/**
 * Table Definition for be_targetType
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_targetType extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_targetType';                   // table name
    var $targetTypeID;                    // int(5)  not_null primary_key unsigned
    var $description;                     // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_targetType',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
