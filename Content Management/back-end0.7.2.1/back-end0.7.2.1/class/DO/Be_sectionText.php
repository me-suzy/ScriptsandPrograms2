<?php
/**
 * Table Definition for be_sectionText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_sectionText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_sectionText';                  // table name
    var $sectionTextID;                   // int(5)  not_null primary_key multiple_key unsigned auto_increment
    var $sectionID;                       // int(5)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $URLname;                         // string(255)  not_null multiple_key
    var $title;                           // string(255)  
    var $blurb;                           // blob(65535)  blob
    var $content;                         // blob(65535)  blob
    var $content_source;                  // blob(65535)  not_null blob
    var $blurb_source;                    // blob(65535)  not_null blob
    var $title_source;                    // string(255)  not_null
    var $meta_keywords;                   // string(255)  
    var $meta_description;                // string(255)  
    var $keywordObjects;                  // blob(255)  blob
    var $template;                        // string(55)  
    var $originalText;                    // int(5)  
    var $commentIDtext;                   // int(7)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_sectionText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
