<?php
/**
 * Table Definition for be_event
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_event extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_event';                        // table name
    var $eventID;                         // int(5)  not_null primary_key unique_key multiple_key
    var $draft;                           // int(3)  not_null
    var $calendar;                        // string(255)  not_null
    var $url;                             // string(255)  not_null
    var $contact;                         // string(255)  not_null
    var $location;                        // string(255)  not_null
    var $email;                           // string(255)  not_null
    var $startDate;                       // int(10)  not_null unsigned
    var $endDate;                         // int(10)  not_null unsigned
    var $author_id;                       // int(11)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_event',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
