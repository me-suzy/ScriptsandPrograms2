<?php
/**
 * Table Definition for psl_block
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Psl_block extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_block';                       // table name
    var $id;                              // int(11)  not_null primary_key multiple_key unsigned
    var $type;                            // int(11)  not_null
    var $title;                           // string(255)  not_null
    var $expire_length;                   // int(11)  not_null
    var $location;                        // string(254)  not_null
    var $source_url;                      // string(254)  not_null
    var $cache_data;                      // blob(65535)  not_null blob
    var $block_options;                   // blob(65535)  blob
    var $ordernum;                        // int(10)  not_null unsigned
    var $date_issued;                     // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Psl_block',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
