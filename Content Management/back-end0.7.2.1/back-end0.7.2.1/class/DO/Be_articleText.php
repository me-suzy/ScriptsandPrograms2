<?php
/**
 * Table Definition for be_articleText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_articleText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_articleText';                  // table name
    var $articleTextID;                   // int(5)  not_null primary_key unsigned auto_increment
    var $articleID;                       // int(5)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $URLname;                         // string(255)  not_null multiple_key
    var $title;                           // string(255)  not_null
    var $blurb;                           // blob(65535)  not_null blob
    var $content;                         // blob(65535)  not_null blob
    var $content_source;                  // blob(65535)  not_null blob
    var $blurb_source;                    // blob(65535)  not_null blob
    var $title_source;                    // string(255)  not_null
    var $spotlight;                       // int(2)  not_null
    var $meta_keywords;                   // string(255)  
    var $meta_description;                // string(255)  
    var $template;                        // string(55)  
    var $originalText;                    // int(5)  
    var $commentIDtext;                   // int(7)  
    var $dateCreated;                     // int(11)  not_null multiple_key unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_articleText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
