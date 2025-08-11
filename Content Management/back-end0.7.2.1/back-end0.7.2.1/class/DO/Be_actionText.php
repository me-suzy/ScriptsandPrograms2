<?php
/**
 * Table Definition for be_actionText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_actionText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_actionText';                   // table name
    var $actionTextID;                    // int(5)  not_null primary_key unsigned auto_increment
    var $actionID;                        // int(5)  not_null multiple_key
    var $languageID;                      // string(3)  not_null
    var $title;                           // string(255)  not_null
    var $blurb;                           // blob(65535)  not_null blob
    var $content;                         // blob(65535)  not_null blob
    var $content_htmlsource;              // blob(65535)  not_null blob
    var $thank_you;                       // blob(65535)  blob
    var $spotlight;                       // int(2)  not_null
    var $template;                        // string(55)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_actionText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
