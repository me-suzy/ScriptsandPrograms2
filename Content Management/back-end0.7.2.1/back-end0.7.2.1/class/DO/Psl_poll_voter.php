<?php
/**
 * Table Definition for psl_poll_voter
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_poll_voter extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_poll_voter';                  // table name
    var $question_id;                     // int(10)  not_null unsigned
    var $voter_id;                        // string(30)  
    var $user_id;                         // int(11)  not_null
    var $date_created;                    // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_poll_voter',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
