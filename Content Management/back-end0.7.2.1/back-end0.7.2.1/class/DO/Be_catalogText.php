<?php
/**
 * Table Definition for be_catalogText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_catalogText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_catalogText';                  // table name
    var $catalogTextID;                   // int(5)  not_null primary_key auto_increment
    var $catalogID;                       // int(5)  not_null multiple_key
    var $languageID;                      // string(2)  not_null
    var $status;                          // string(100)  not_null
    var $language;                        // string(100)  not_null
    var $condition;                       // string(100)  not_null
    var $interviewDate;                   // string(100)  not_null
    var $interviewee;                     // string(100)  not_null
    var $position;                        // string(100)  not_null
    var $location;                        // string(100)  not_null
    var $interviewer;                     // string(100)  not_null
    var $content;                         // string(100)  not_null
    var $abstract;                        // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_catalogText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
