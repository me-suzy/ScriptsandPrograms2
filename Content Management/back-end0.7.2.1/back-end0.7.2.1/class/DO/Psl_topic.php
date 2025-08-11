<?php
/**
 * Table Definition for psl_topic
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_topic extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_topic';                       // table name
    var $topic_id;                        // int(10)  not_null primary_key unsigned
    var $topic_name;                      // string(60)  not_null unique_key
    var $image;                           // string(30)  
    var $alt_text;                        // string(100)  
    var $width;                           // int(11)  
    var $height;                          // int(11)  
    var $onlinkbar;                       // int(1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_topic',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
