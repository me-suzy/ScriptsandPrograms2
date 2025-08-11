<?php
/**
 * Table Definition for psl_poll_question
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_poll_question extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_poll_question';               // table name
    var $question_id;                     // int(10)  not_null primary_key unsigned
    var $question_text;                   // string(255)  not_null
    var $question_total_votes;            // int(11)  
    var $current;                         // int(4)  not_null
    var $date_created;                    // int(11)  
    var $language_id;                     // string(2)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_poll_question',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
