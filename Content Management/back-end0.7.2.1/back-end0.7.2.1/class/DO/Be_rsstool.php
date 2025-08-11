<?php
/**
 * Table Definition for be_rsstool
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_rsstool extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_rsstool';                      // table name
    var $md5;                             // string(50)  not_null primary_key multiple_key
    var $url;                             // string(255)  
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $requests;                        // blob(65535)  not_null blob
    var $DATA;                            // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_rsstool',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
