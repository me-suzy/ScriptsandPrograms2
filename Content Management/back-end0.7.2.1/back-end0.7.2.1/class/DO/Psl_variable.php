<?php
/**
 * Table Definition for psl_variable
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_variable extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_variable';                    // table name
    var $variable_id;                     // int(10)  not_null multiple_key unsigned
    var $variable_name;                   // string(32)  not_null primary_key
    var $value;                           // string(127)  
    var $description;                     // string(127)  
    var $variable_group;                  // string(20)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_variable',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
