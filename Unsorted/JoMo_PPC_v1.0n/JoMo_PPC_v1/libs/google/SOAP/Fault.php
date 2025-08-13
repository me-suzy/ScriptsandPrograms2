<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo  - Port to PEAR and more                      |
// | Authors: Dietrich Ayala - Original Author                            |
// +----------------------------------------------------------------------+
//
// $Id: Fault.php,v 1.4 2002/04/17 07:42:52 shane Exp $
//
require_once(__GOOGLE_DIR.'PEAR.php');
require_once(__GOOGLE_DIR.'SOAP/Message.php');

/**
* define('SOAP_DEBUG', false);
*
* @package  SOAP
* @access   public
* @author   Shane Caraveo   Port to PEAR and more
* @author   Dietrich Ayala  Original Author
* @version  $Id: Fault.php,v 1.4 2002/04/17 07:42:52 shane Exp $
*/
class SOAP_Fault extends PEAR_Error
{
    
    /**
    *
    * 
    * @param    string 
    * @param    mixed
    * @param    mixed
    * @param    mixed
    * @param    mixed
    */
    function SOAP_Fault($message = 'unknown error', $code = null, $mode = null, $options = null, $userinfo = null)
    {
    
        if (is_array($userinfo)) {
            $actor = $userinfo['actor'];
            $detail = $userinfo['detail'];
        } else {
            $actor = 'Unknown';
            $detail = $userinfo;
        }
        parent::PEAR_Error($message, $code, $mode, $options, $detail);
        $this->error_message_prefix = $actor;
        
    }
    
    // set up a fault
    function message()
    {
        $msg = new SOAP_Message();
        $msg->method('Fault',
                                    array(
                                        new SOAP_Value('faultcode', 'QName', 'SOAP-ENV:'.$this->code),
                                        #'faultcode' => $this->code,
                                        new SOAP_Value('faultstring', 'string', $this->message),
                                        #'faultstring' => $this->message,
                                        new SOAP_Value('faultactor', 'anyURI', $this->error_message_prefix),
                                        #'faultactor' => $this->error_message_prefix,
                                        new SOAP_Value('faultdetail', 'string', $this->userinfo)
                                        #'faultdetail' => $this->userinfo
                                    ),
                                    SOAP_ENVELOP
                                );
        return $msg;
    }
    
    function getFault()
    {
        return array(
                'faultcode' => $this->code,
                'faultstring' => $this->message,
                'faultactor' => $this->error_message_prefix,
                'faultdetail' => $this->userinfo
            );
    }
    
    function getActor()
    {
        return $this->error_message_prefix;
    }
    
    function getDetail()
    {
        return $this->userinfo;
    }
    
}
?>