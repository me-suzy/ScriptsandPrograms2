<?php
/**
 * Table Definition for be_subsite_block_lut
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_subsite_block_lut extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_subsite_block_lut';            // table name
    var $ID;                              // int(5)  not_null primary_key unsigned auto_increment
    var $subsite_id;                      // int(5)  not_null multiple_key unsigned
    var $block_id;                        // int(5)  not_null multiple_key unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_subsite_block_lut',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
