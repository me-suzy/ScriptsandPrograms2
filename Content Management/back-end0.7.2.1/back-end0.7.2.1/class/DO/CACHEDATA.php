<?php
/**
 * Table Definition for CACHEDATA
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_CACHEDATA extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'CACHEDATA';                       // table name
    var $CACHEKEY;                        // string(255)  not_null primary_key
    var $CACHEEXPIRATION;                 // int(11)  not_null
    var $GZDATA;                          // blob(65535)  blob binary
    var $DATASIZE;                        // int(11)  
    var $DATACRC;                         // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_CACHEDATA',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
