<?php
/**
 * Table Definition for be_articleTextOptions
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_articleTextOptions extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_articleTextOptions';           // table name
    var $articleID;                       // int(5)  not_null primary_key unsigned
    var $languageID;                      // string(3)  not_null primary_key
    var $options;                         // blob(65535)  not_null blob
    var $Author;                          // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_articleTextOptions',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
