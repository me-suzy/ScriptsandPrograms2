<?php
/**
 * Table Definition for be_blockcache
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_blockcache extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_blockcache';                   // table name
    var $blockID;                         // int(11)  not_null multiple_key
    var $blockTypeID;                     // int(11)  not_null multiple_key
    var $userID;                          // int(11)  not_null multiple_key
    var $languageID;                      // string(3)  not_null multiple_key
    var $subsiteID;                       // int(11)  not_null multiple_key
    var $expiryTime;                      // int(11)  not_null multiple_key
    var $cacheData;                       // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_blockcache',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
