<?php
/**
 * Table Definition for be_card
 */
global $_PSL;
require_once ($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_card extends BE_LanguageDataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_card';                         // table name
    var $cardID;                          // int(11)  not_null primary_key unsigned auto_increment
    var $customize;                       // int(4)  not_null unsigned
    var $defaultCard;                     // int(4)  not_null multiple_key unsigned
    var $senderName;                      // string(255)  not_null
    var $senderEmail;                     // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_card',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}

?>
