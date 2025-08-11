<?php
/**
 * Table Definition for psl_blockText
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_blockText extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_blockText';                   // table name
    var $textID;                          // int(11)  not_null primary_key unsigned
    var $id;                              // int(11)  not_null multiple_key unsigned
    var $languageID;                      // string(3)  not_null
    var $title;                           // string(255)  not_null
    var $location;                        // string(254)  not_null
    var $source_url;                      // string(254)  not_null
    var $cache_data;                      // blob(65535)  not_null blob
    var $date_issued;                     // int(10)  unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_blockText',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
