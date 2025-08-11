<?php
/**
 * Table Definition for UidNumber
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_UidNumber extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'UidNumber';                       // table name
    var $Uid;                             // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_UidNumber',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
