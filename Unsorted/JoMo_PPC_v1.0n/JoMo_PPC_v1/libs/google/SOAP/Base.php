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
// $Id: Base.php,v 1.6 2002/04/08 07:11:51 shane Exp $
//

/**
* Common base class of all Soap lclasses
* 
* @version  $Id: Base.php,v 1.6 2002/04/08 07:11:51 shane Exp $
* @package  SOAP::Client
* @author   Shane Caraveo  - Port to PEAR and more
* @author   Dietrich Ayala - Original Author
*/

require_once __GOOGLE_DIR.'PEAR.php';
#require_once __GOOGLE_DIR.'SOAP/Fault.php';

class SOAP_Base extends PEAR
{
    
    /**
    * Store debugging information in $debug_data?
    * 
    * @var  boolean if true debugging informations will be store in $debug_data
    * @see  $debug_data, SOAP_Base
    */
    var $debug_flag = SOAP_DEBUG;
    
    /**
    * String containing debugging informations if $debug_flag is set to true
    *
    * @var      string  debugging informations - mostyl error messages
    * @see      $debug_flag, SOAP_Base
    * @access   public
    */
    var $debug_data = '';
    
    /**
    * Fault code
    * 
    * @var  string
    */
    var $myfaultcode = '';
    
    /**
    * Recent PEAR error object
    * 
    * @var  object  PEAR Error
    */
    var $fault = NULL;
    
    
    /**
    * ???
    *
    * @param    string  error code 
    * @see  $debug_data, debug()
    */
    function SOAP_Base($faultcode)
    {
        $this->myfaultcode = $faultcode;
        parent::PEAR('SOAP_Fault');
    }
    
    
    /**
    * Raise a soap error
    * 
    * Please referr to the SOAP definition for an impression of what a certain parameter
    * stands for.
    *
    * Use $debug_flag to store errors to the member variable $debug_data
    * 
    * @param    string  error message
    * @param    string  detailed error message.
    * @param    string  actor
    * @param    mixed
    * @param    mixed
    * @param    mixed
    * @param    boolean
    * @see      $debug_flag, $debug_data
    */
    function &raiseSoapFault($str, $detail = '', $actorURI = '', $code = null, $mode = null, $options = null, $skipmsg = false)
    {
        # pass through previous faults
        if (is_object($str)) {
            $this->fault = $str;
        } else {
            if (!$code) $code = $this->myfaultcode;
            $this->fault =  $this->raiseError($str,
                             $code,
                             $mode,
                             $options,
                             array('actor' => $actorURI, 'detail' => $detail),
                             'SOAP_Fault',
                             $skipmsg);
        }
        return $this->fault;
    }
    
    /**
    * maintains a string of debug data
    *
    * @param    debugging message - sometimes an error message
    */
    function debug($string)
    {
        if ($this->debug_flag) {
            $this->debug_data .= get_class($this) . ': ' . preg_replace("/>/", ">\r\n", $string) . "\n";
        }
    }
    
}

class QName
{
    var $name = '';
    var $ns = '';
    #var $arrayInfo = '';
    
    function QName($name) {
        if (strpos($name, ':') != FALSE) {
            $s = split(':',$name);
            $s = array_reverse($s);
            $this->name = $s[0];
            $this->ns = $s[1];
        } else {
            $this->name = $name;
        }
        
        # a little more magic than should be in a qname
        #$p = strpos($this->name, '[');
        #if ($p) {
        #    # XXX need to re-examine this logic later
        #    # chop off []
        #    $this->arrayInfo = substr($this->name, $p);
        #    $this->name = substr($this->name, 0, $p);
        #}
    }
}
?>
