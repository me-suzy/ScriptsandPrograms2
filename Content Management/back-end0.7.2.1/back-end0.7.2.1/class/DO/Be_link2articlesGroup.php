<?php
/**
 * Table Definition for be_link2articlesGroup
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_link2articlesGroup extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_link2articlesGroup';           // table name
    var $linkID;                          // int(5)  not_null multiple_key unsigned
    var $articleID;                       // int(5)  not_null multiple_key unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_link2articlesGroup',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
