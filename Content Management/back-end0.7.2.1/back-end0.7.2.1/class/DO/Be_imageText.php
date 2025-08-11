<?php
/**
 * Table Definition for be_imageText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_imageText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_imageText';                    // table name
    var $imageTextID;                     // int(5)  not_null primary_key unsigned auto_increment
    var $imageID;                         // int(5)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $title;                           // string(255)  not_null
    var $description;                     // string(255)  
    var $originalText;                    // int(3)  not_null
    var $commentIDtext;                   // int(7)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_imageText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
