<?php
/**
 * Table Definition for be_upload
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_upload extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_upload';                       // table name
    var $uploadID;                        // int(6)  not_null primary_key multiple_key auto_increment
    var $filename;                        // string(255)  not_null multiple_key
    var $path;                            // string(255)  not_null
    var $url;                             // string(255)  not_null
    var $shortDescription;                // string(255)  
    var $longDescription;                 // blob(255)  blob
    var $fileType;                        // string(25)  not_null
    var $uploadedBy;                      // string(25)  
    var $imageHeight;                     // int(10)  
    var $imageWidth;                      // int(10)  
    var $thumbnail;                       // blob(65535)  blob binary
    var $rawSize;                         // int(11)  
    var $time;                            // int(11)  
    var $perm;                            // string(20)  
    var $subsiteID;                       // int(5)  
    var $subdir;                          // string(25)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_upload',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
