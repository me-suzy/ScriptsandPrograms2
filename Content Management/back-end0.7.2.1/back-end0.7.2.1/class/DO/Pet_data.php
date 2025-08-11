<?php
/**
 * Table Definition for pet_data
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_data extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_data';                        // table name
    var $indID;                           // int(7)  not_null primary_key
    var $petitionID;                      // int(7)  not_null
    var $IPaddress;                       // string(30)  not_null
    var $browser;                         // string(50)  not_null
    var $comments;                        // string(255)  
    var $signedDateOld;                   // date(10)  not_null binary
    var $signedDate;                      // int(10)  not_null unsigned
    var $verified;                        // int(2)  
    var $verifyDateOld;                   // date(10)  not_null binary
    var $verifyDate;                      // int(10)  not_null unsigned
    var $followupContact;                 // int(2)  
    var $public;                          // int(2)  
    var $genRandPassword;                 // string(15)  
    var $approved;                        // int(2)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_data',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
