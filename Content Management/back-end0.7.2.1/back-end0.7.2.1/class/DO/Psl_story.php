<?php
/**
 * Table Definition for psl_story
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_story extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_story';                       // table name
    var $story_id;                        // int(11)  not_null primary_key unsigned
    var $user_id;                         // int(11)  not_null unsigned
    var $order_no;                        // int(10)  not_null unsigned
    var $title;                           // string(80)  
    var $dept;                            // string(80)  
    var $intro_text;                      // blob(65535)  not_null blob
    var $body_text;                       // blob(65535)  blob
    var $hits;                            // int(11)  unsigned
    var $topic_cache;                     // blob(65535)  blob
    var $story_options;                   // blob(65535)  blob
    var $date_available;                  // int(11)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_story',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
