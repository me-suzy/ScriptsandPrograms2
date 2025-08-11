<?php
/**
 * Table Definition for be_subsites
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_subsites extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_subsites';                     // table name
    var $subsite_id;                      // int(5)  not_null primary_key unsigned
    var $name;                            // string(32)  not_null
    var $description;                     // string(255)  not_null
    var $subsite_type_id;                 // int(5)  not_null multiple_key
    var $sectionID;                       // int(5)  not_null multiple_key unsigned
    var $url;                             // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_subsites',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
