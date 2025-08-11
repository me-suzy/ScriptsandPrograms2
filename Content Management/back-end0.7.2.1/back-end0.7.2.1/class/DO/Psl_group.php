<?php
/**
 * Table Definition for psl_group
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_group extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_group';                       // table name
    var $group_id;                        // int(10)  not_null primary_key unsigned
    var $group_name;                      // string(60)  not_null unique_key
    var $group_description;               // blob(65535)  blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_group',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
