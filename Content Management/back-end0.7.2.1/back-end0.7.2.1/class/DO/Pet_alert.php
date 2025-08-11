<?php
/**
 * Table Definition for pet_alert
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_alert extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_alert';                       // table name
    var $alertID;                         // int(7)  not_null primary_key auto_increment
    var $sender;                          // string(50)  
    var $senderName;                      // string(50)  
    var $receiver;                        // string(50)  
    var $petitionID;                      // int(7)  not_null
    var $date;                            // int(10)  not_null unsigned
    var $IPaddress;                       // string(30)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_alert',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
