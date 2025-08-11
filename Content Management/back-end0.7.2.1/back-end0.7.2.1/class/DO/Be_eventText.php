<?php
/**
 * Table Definition for be_eventText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_eventText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_eventText';                    // table name
    var $eventID;                         // int(5)  not_null multiple_key
    var $eventTextID;                     // int(5)  not_null primary_key unique_key
    var $language;                        // string(3)  not_null multiple_key
    var $name;                            // string(255)  not_null
    var $description;                     // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_eventText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
