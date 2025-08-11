<?php
/**
 * Table Definition for psl_poll_answer
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_poll_answer extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_poll_answer';                 // table name
    var $question_id;                     // int(10)  not_null primary_key unsigned
    var $answer_id;                       // string(32)  not_null primary_key
    var $answer_text;                     // string(255)  not_null
    var $votes;                           // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_poll_answer',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
