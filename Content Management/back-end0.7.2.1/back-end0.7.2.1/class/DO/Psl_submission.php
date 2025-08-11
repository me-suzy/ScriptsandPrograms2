<?php
/**
 * Table Definition for psl_submission
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_submission extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_submission';                  // table name
    var $story_id;                        // int(11)  not_null primary_key unsigned
    var $user_id;                         // int(11)  not_null unsigned
    var $title;                           // string(80)  
    var $dept;                            // string(80)  
    var $intro_text;                      // blob(65535)  not_null blob
    var $body_text;                       // blob(65535)  blob
    var $hits;                            // int(11)  unsigned
    var $email;                           // string(50)  
    var $name;                            // string(50)  not_null
    var $topic_cache;                     // blob(65535)  blob
    var $date_created;                    // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_submission',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
