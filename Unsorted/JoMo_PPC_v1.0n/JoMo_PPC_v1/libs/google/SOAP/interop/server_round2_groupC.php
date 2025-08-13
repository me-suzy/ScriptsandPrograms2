<?
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
// | Authors: Shane Caraveo                                               |
// +----------------------------------------------------------------------+
//
// $Id: server_round2_groupC.php,v 1.2 2002/04/15 06:46:09 shane Exp $
//
require_once 'SOAP/Server.php';
require_once 'SOAP/Header.php';

class SOAP_Interop_GroupC {
    var $method_namespace = 'http://soapinterop.org/echoheader/';
    
    function echoMeStringRequest($string)
    {
	return new SOAP_Value('echoMeStringResponse','string',$string, $this->method_namespace);
    }

    function echoMeStructRequest($struct)
    {
	return new SOAP_Value('echoMeStructResponse','SOAPStruct',$struct, $this->method_namespace);
    }
}

$groupc = new SOAP_Interop_GroupC();
$server->addObjectMap($groupc);

?>