<?php
/**
 * Table Definition for be_linkText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_linkText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_linkText';                     // table name
    var $linkTextID;                      // int(5)  not_null primary_key multiple_key unsigned auto_increment
    var $linkID;                          // int(5)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $title;                           // string(255)  not_null
    var $url;                             // string(255)  
    var $description;                     // blob(65535)  blob
    var $description_source;              // blob(65535)  blob
    var $title_source;                    // string(255)  not_null
    var $originalText;                    // int(5)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_linkText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
