<?php
/**
 * Table Definition for be_articles
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_articles extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_articles';                     // table name
    var $articleID;                       // int(5)  not_null primary_key multiple_key unsigned auto_increment
    var $URLname;                         // string(20)  not_null primary_key multiple_key
    var $author_id;                       // int(5)  multiple_key unsigned
    var $subsiteID;                       // int(5)  unsigned
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $dateForSort;                     // int(10)  not_null unsigned
    var $content_type;                    // string(8)  not_null
    var $main_languageID;                 // string(2)  not_null
    var $hide;                            // int(2)  not_null multiple_key unsigned
    var $deleted;                         // int(2)  not_null unsigned
    var $restrict2members;                // int(5)  not_null unsigned
    var $spotlight;                       // int(2)  
    var $showPrint;                       // int(2)  
    var $useIcons;                        // int(2)  
    var $hitCounter;                      // int(10)  not_null
    var $priority;                        // int(5)  not_null
    var $commentID;                       // int(7)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_articles',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    var $_languageTable = 'be_articleText';
}
