<?php
/**
 * Table Definition for be_contact
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_contact extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_contact';                      // table name
    var $contactID;                       // int(5)  not_null primary_key unsigned auto_increment
    var $contactType;                     // int(5)  not_null unsigned
    var $firstName;                       // string(50)  not_null
    var $lastName;                        // string(50)  not_null
    var $companyName;                     // string(100)  
    var $displayName;                     // string(100)  not_null
    var $gender;                          // string(2)  
    var $title;                           // string(50)  
    var $email;                           // string(100)  multiple_key
    var $phoneNumber;                     // string(50)  
    var $faxNumber;                       // string(50)  
    var $address;                         // string(100)  
    var $city;                            // string(50)  
    var $province;                        // string(20)  
    var $postalCode;                      // string(20)  
    var $country;                         // string(20)  
    var $notes;                           // blob(65535)  blob
    var $target;                          // int(2)  
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $followupGlobal;                  // int(2)  not_null
    var $verified;                        // int(1)  unsigned
    var $randomKey;                       // string(10)  multiple_key
    var $sameContactAs;                   // int(5)  unsigned
    var $author_id;                       // int(11)  unsigned multiple_key
    var $enteredBy;                       // int(11)  unsigned
    var $dateVerified;                    // int(10)  unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_contact',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // define our display page
    var $displayPage = 'contact.php';

    // define our admin page
    var $adminPage = 'BE_contactAdmin.php';

    function getID() { return $this->contactID; }

    // will be filled by default
    function ValidateContactType() {
      return true;
    }
    function ValidateDisplayName() {
      return true;
    }

}
