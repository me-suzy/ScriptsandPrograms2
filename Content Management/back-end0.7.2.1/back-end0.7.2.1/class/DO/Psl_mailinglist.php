<?php
/**
 * Table Definition for psl_mailinglist
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_mailinglist extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_mailinglist';                 // table name
    var $id;                              // int(10)  not_null primary_key unique_key multiple_key unsigned
    var $email;                           // string(100)  not_null
    var $name;                            // string(100)  
    var $date_created;                    // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_mailinglist',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
