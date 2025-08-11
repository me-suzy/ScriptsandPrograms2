<?php
/**
 * Table Definition for pet_petition2contact
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_petition2contact extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_petition2contact';            // table name
    var $petitionID;                      // int(5)  not_null primary_key unsigned
    var $contactID;                       // int(5)  not_null primary_key unsigned
    var $targetID;                        // int(5)  unsigned
    var $petitionComment;                 // blob(65535)  not_null blob
    var $followup;                        // int(5)  not_null
    var $public;                          // int(5)  
    var $organization;                    // string(255)  
    var $organizationalEndorsement;       // int(5)  
    var $organizationApproved;            // int(5)  
    var $dateDelivered;                   // int(10)  unsigned
    var $dateVerified;                    // int(10)  unsigned
    var $dateReminded;                    // int(10)  unsigned
    var $browserInfo;                     // string(255)  not_null
    var $IPaddress;                       // string(50)  not_null
    var $randomKey;                       // string(25)  not_null
    var $extraAttribute1;                 // string(255)  
    var $approved;                        // int(5)  
    var $verified;                        // int(5)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_petition2contact',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
