<?php
/**
 * Table Definition for be_action
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_LanguageDataObject.class');

class DO_Be_action extends BE_LanguageDataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_action';                       // table name
    var $actionID;                        // int(5)  not_null primary_key unsigned auto_increment
    var $URLname;                         // string(20)  not_null unique_key
    var $author_id;                       // int(5)  multiple_key unsigned
    var $subsiteID;                       // int(5)  not_null multiple_key
    var $dateCreated;                     // int(10)  not_null unsigned
    var $dateModified;                    // int(10)  not_null unsigned
    var $dateAvailable;                   // int(10)  not_null unsigned
    var $dateRemoved;                     // int(10)  not_null unsigned
    var $hide;                            // int(2)  
    var $restrict2members;                // int(5)  
    var $customize;                       // int(5)  
    var $targetType;                      // int(5)  
    var $actionCounter;                   // int(10)  not_null unsigned
    var $priority;                        // int(5)  not_null
    var $actionType;                      // int(5)  not_null unsigned
    var $hitCounter;                      // int(10)  not_null unsigned
    var $content_type;                    // int(1)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return BE_LanguageDataObject::staticGet('DO_Be_action',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // define our display page
    var $displayPage = 'action.php';

    // define our admin page
    var $adminPage = 'BE_actionAdmin.php';

    function getID() { return $this->actionID; }

    function delete($useWhere = false) {
       $ret = parent::delete($useWhere);
       // cascade joins...
       return $ret;
    }

   function fillFromAry($ary, $prefix = '', $suffix='') {
      $ret = parent::fillFromAry($ary, $prefix, $suffix);
      // set defaults
      if (!$this->dateAvailable) {
         $this->dateAvailable = time();
      }
      return $ret;
   }

    // will be filled by default
    function ValidateHitCounter() {
      return true;
    }
    // will be filled by default
    function ValidateActionCounter() {
      return true;
    }
}
