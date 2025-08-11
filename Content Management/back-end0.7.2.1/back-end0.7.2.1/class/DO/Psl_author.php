<?php
/**
 * Table Definition for psl_author
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Psl_author extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'psl_author';                      // table name
    var $author_id;                       // int(11)  not_null primary_key unsigned
    var $author_name;                     // string(50)  not_null unique_key
    var $author_realname;                 // string(60)  
    var $url;                             // string(50)  
    var $email;                           // string(50)  
    var $quote;                           // string(50)  
    var $password;                        // string(64)  not_null
    var $seclev;                          // int(11)  not_null
    var $perms;                           // string(255)  
    var $author_options;                  // blob(65535)  blob
    var $question;                        // string(255)  
    var $answer;                          // string(255)  
    var $defaultCommentThreshold;         // int(5)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Psl_author',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
