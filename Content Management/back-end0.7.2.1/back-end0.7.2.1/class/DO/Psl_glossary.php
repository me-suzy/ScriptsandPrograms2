<?php
/**
 * Table Definition for psl_glossary
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_glossary extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_glossary';                    // table name
    var $id;                              // int(10)  not_null primary_key unique_key unsigned
    var $term;                            // string(255)  not_null multiple_key
    var $def;                             // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_glossary',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
