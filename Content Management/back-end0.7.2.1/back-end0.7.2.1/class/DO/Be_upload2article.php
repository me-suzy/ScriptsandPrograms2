<?php
/**
 * Table Definition for be_upload2article
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_upload2article extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_upload2article';               // table name
    var $articleTextID;                   // int(5)  not_null multiple_key unsigned
    var $language;                        // string(3)  not_null
    var $filename;                        // string(255)  not_null
    var $caption;                         // string(255)  not_null
    var $description;                     // blob(65535)  not_null blob
    var $dateUploaded;                    // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_upload2article',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
