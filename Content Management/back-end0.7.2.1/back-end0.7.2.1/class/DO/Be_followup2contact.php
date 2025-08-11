<?php
/**
 * Table Definition for be_followup2contact
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_followup2contact extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_followup2contact';             // table name
    var $id;                              // int(10)  not_null primary_key auto_increment
    var $followupID;                      // int(10)  not_null
    var $contactID;                       // int(10)  not_null
    var $dateDelivered;                   // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_followup2contact',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
