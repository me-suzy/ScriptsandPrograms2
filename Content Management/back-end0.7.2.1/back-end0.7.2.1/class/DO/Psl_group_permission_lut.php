<?php
/**
 * Table Definition for psl_group_permission_lut
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_group_permission_lut extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_group_permission_lut';        // table name
    var $lut_id;                          // int(11)  not_null primary_key multiple_key unsigned
    var $group_id;                        // int(11)  unsigned
    var $permission_id;                   // int(11)  unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_group_permission_lut',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
