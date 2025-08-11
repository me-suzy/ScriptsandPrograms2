<?php
/**
 * Table Definition for be_catalog
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_catalog extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_catalog';                      // table name
    var $catalogID;                       // int(5)  not_null primary_key auto_increment
    var $transcriptFile;                  // string(100)  not_null
    var $audioFile;                       // string(100)  not_null
    var $imageFile;                       // string(100)  not_null
    var $dateCreated;                     // int(11)  not_null
    var $dateModified;                    // int(11)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_catalog',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
