<?php
/**
 * Table Definition for be_cardText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_cardText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_cardText';                     // table name
    var $cardTextID;                      // int(11)  not_null primary_key unsigned auto_increment
    var $cardID;                          // int(11)  not_null multiple_key unsigned
    var $languageID;                      // string(3)  not_null multiple_key
    var $cardTitle;                       // string(255)  not_null
    var $cardBlurb;                       // blob(65535)  not_null blob
    var $cardText;                        // blob(65535)  not_null blob
    var $cardImage;                       // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_cardText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
