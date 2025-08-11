<?php
/**
 * Table Definition for psl_infolog
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_infolog extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_infolog';                     // table name
    var $id;                              // int(10)  not_null primary_key unsigned
    var $description;                     // string(50)  
    var $data;                            // string(255)  multiple_key
    var $date_created;                    // int(11)  
    var $userID;                          // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_infolog',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
