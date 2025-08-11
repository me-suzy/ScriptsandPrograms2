<?php
/**
 * Table Definition for be_category2item
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_category2item extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_category2item';                // table name
    var $id;                              // int(5)  not_null primary_key auto_increment
    var $category_type;                   // string(8)  not_null multiple_key
    var $category_code;                   // string(8)  not_null
    var $item_type;                       // string(16)  not_null multiple_key
    var $item_id;                         // string(50)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_category2item',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
