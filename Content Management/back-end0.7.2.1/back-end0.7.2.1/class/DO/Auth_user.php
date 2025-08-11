<?php
/**
 * Table Definition for auth_user
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Auth_user extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'auth_user';                       // table name
    var $user_id;                         // string(32)  not_null primary_key
    var $username;                        // string(32)  not_null unique_key
    var $password;                        // string(32)  not_null
    var $perms;                           // string(255)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Auth_user',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
