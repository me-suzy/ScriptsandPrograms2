<?php
/**
 * Table Definition for pet_main
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_main extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_main';                        // table name
    var $indID;                           // int(7)  not_null primary_key auto_increment
    var $firstName;                       // string(30)  not_null
    var $middleName;                      // string(30)  not_null
    var $lastName;                        // string(30)  not_null
    var $email;                           // string(100)  not_null
    var $organization;                    // string(50)  
    var $organizationVerified;            // string(1)  
    var $webSite;                         // string(100)  
    var $address;                         // string(100)  
    var $city;                            // string(100)  
    var $state;                           // string(100)  
    var $countryID;                       // string(20)  
    var $postalCode;                      // string(20)  
    var $PGP;                             // string(20)  
    var $birthNumber;                     // string(20)  
    var $contact;                         // string(3)  
    var $password;                        // string(20)  
    var $creationDateOld;                 // date(10)  not_null binary
    var $creationDate;                    // int(10)  not_null unsigned
    var $outreachDate;                    // int(10)  not_null unsigned
    var $outreachNumber;                  // int(5)  not_null unsigned
    var $outreachID;                      // int(5)  not_null unsigned
    var $rejectedMember;                  // string(3)  
    var $featuredMember;                  // string(3)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_main',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
