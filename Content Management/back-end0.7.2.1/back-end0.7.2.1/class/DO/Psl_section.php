<?php
/**
 * Table Definition for psl_section
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_section extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_section';                     // table name
    var $section_id;                      // int(11)  not_null primary_key unsigned
    var $section_name;                    // string(32)  not_null unique_key
    var $description;                     // string(128)  
    var $artcount;                        // int(11)  
    var $section_options;                 // blob(65535)  blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_section',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
