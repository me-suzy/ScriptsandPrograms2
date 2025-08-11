<?php
/**
 * Table Definition for psl_comment
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_comment extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_comment';                     // table name
    var $comment_id;                      // int(11)  not_null primary_key
    var $parent_id;                       // int(11)  not_null
    var $story_id;                        // int(11)  not_null primary_key
    var $user_id;                         // int(15)  not_null
    var $name;                            // string(50)  not_null
    var $email;                           // string(50)  
    var $ip;                              // string(50)  
    var $subject;                         // string(50)  not_null
    var $comment_text;                    // blob(65535)  not_null blob
    var $pending;                         // int(3)  not_null unsigned
    var $date_created;                    // int(11)  
    var $rating;                          // int(5)  not_null multiple_key

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_comment',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
