<?php
/**
 * Table Definition for be_link
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_link extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_link';                         // table name
    var $linkID;                          // int(5)  not_null primary_key multiple_key unsigned auto_increment
    var $url;                             // string(255)  multiple_key
    var $author_id;                       // int(5)  unsigned
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $content_type;                    // string(8)  not_null
    var $hide;                            // int(2)  
    var $restrict2members;                // int(5)  
    var $hitCounter;                      // int(10)  not_null
    var $priority;                        // int(5)  unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_link',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
