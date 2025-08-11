<?php
/**
 * Table Definition for pet_petition
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Pet_petition extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_petition';                    // table name
    var $petitionID;                      // int(7)  not_null primary_key auto_increment
    var $URLname;                         // string(20)  
    var $author_id;                       // int(11)  not_null
    var $petitionAuthorName;              // string(255)  
    var $petitionAuthorEmail;             // string(255)  
    var $petitionAuthorOrganization;      // string(255)  
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $dateEnded;                       // int(10)  unsigned
    var $hide;                            // string(1)  
    var $restrict2members;                // int(1)  
    var $priority;                        // int(5)  unsigned
    var $petitionCounter;                 // int(10)  not_null unsigned
    var $hitCounter;                      // int(10)  not_null unsigned
    var $sectionID;                       // int(10)  not_null
    var $subsiteID;                       // int(10)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Pet_petition',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
