<?php
/**
 * Table Definition for pet_petitionText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Pet_petitionText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'pet_petitionText';                // table name
    var $petitionTextID;                  // int(7)  not_null primary_key multiple_key auto_increment
    var $petitionID;                      // int(7)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $title;                           // string(255)  
    var $title_source;                    // string(255)  
    var $blurb;                           // blob(65535)  blob
    var $blurb_source;                    // blob(65535)  blob
    var $content;                         // blob(65535)  blob
    var $content_source;                  // blob(65535)  blob
    var $credits;                         // blob(65535)  blob
    var $credits_source;                  // blob(65535)  blob
    var $support;                         // blob(65535)  blob
    var $support_source;                  // blob(65535)  blob
    var $faq;                             // blob(65535)  blob
    var $faq_source;                      // blob(65535)  blob
    var $meta_keywords;                   // string(255)  
    var $meta_description;                // string(255)  
    var $template;                        // string(55)  
    var $confirmEmail;                    // blob(65535)  blob
    var $alertEmail;                      // blob(65535)  blob
    var $originalText;                    // int(5)  
    var $spotlight;                       // int(1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Pet_petitionText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
