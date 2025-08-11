<?php
/**
 * Table Definition for pet_letters
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_letters extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_letters';                     // table name
    var $indID;                           // int(7)  not_null primary_key
    var $letterID;                        // int(7)  not_null primary_key
    var $randPassword;                    // string(9)  
    var $outreachDate;                    // date(10)  binary
    var $confirmDate;                     // date(10)  binary

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_letters',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
