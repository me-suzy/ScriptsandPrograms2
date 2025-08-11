<?php
/**
 * Table Definition for be_feedback
 */
global $_PSL;
require_once($_PSL['classdir'] . '/BE_DataObject.class');

class DO_Be_feedback extends BE_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'be_feedback';                     // table name
    var $id;                              // int(5)  multiple_key
    var $SubmitterName;                   // string(255)  
    var $SubmitterEmail;                  // string(255)  
    var $Location;                        // string(255)  
    var $ReferringPage;                   // string(255)  
    var $CupeMember;                      // int(3)  
    var $CupeLocal;                       // string(255)  
    var $KnowsCupeMember;                 // int(3)  
    var $Comments;                        // blob(65535)  blob
    var $TimeSubmitted;                   // int(10)  
    var $TimeRespondedTo;                 // int(10)  
    var $Responded;                       // int(3)  
    var $ForwardedTo;                     // string(255)  
    var $RespondedBy;                     // string(50)  
    var $Response;                        // blob(65535)  blob
    var $Browser;                         // string(255)  
    var $UserIP;                          // string(63)  
    var $RemoteHost;                      // string(255)  
    var $FeedbackComments;                // blob(65535)  blob
    var $ForwardComments;                 // blob(65535)  blob
    var $subsite_id;                      // int(5)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Be_feedback',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
