<?php
/**
 * Table Definition for be_images
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_images extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_images';                       // table name
    var $imageID;                         // int(11)  not_null primary_key multiple_key
    var $author_id;                       // int(5)  unsigned
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $hide;                            // int(2)  not_null unsigned
    var $restrict2members;                // int(5)  not_null unsigned
    var $views;                           // int(11)  not_null
    var $format;                          // string(32)  not_null
    var $width;                           // int(11)  not_null
    var $height;                          // int(11)  not_null
    var $bytes;                           // int(11)  not_null
    var $image;                           // blob(16777215)  not_null blob binary
    var $thumbnail;                       // blob(16777215)  blob binary
    var $publishedAt;                     // timestamp(19)  not_null unsigned zerofill binary timestamp
    var $shotAt;                          // date(10)  binary
    var $priority;                        // int(5)  not_null
    var $commentID;                       // int(7)  
    var $filename;                        // string(255)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_images',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    var $_languageTable = 'be_imageText';

}
