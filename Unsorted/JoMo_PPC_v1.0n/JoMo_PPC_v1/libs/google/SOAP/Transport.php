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
// $Id: Transport.php,v 1.15 2002/04/15 06:45:43 shane Exp $
//

require_once __GOOGLE_DIR.'SOAP/Base.php';

/**
* SOAP Transport Layer
*
* This layer can use different protocols dependant on the endpoint url provided
* no knowlege of the SOAP protocol is available at this level
* no knowlege of the transport protocols is available at this level
*
* @access   public
* @version  $Id: Transport.php,v 1.15 2002/04/15 06:45:43 shane Exp $
* @package  SOAP::Transport
* @author   Shane Caraveo 
*/
class SOAP_Transport extends SOAP_Base
{

    /**
    * Transport object - build using the constructor as a factory
    * 
    * @var  object  SOAP_Transport_SMTP|HTTP
    */
    var $transport = NULL;
    
    var $encoding = SOAP_DEFAULT_ENCODING;
    var $result_encoding = SOAP_DEFAULT_ENCODING;
    /**
    * SOAP::Transport constructor
    *
    * @param string $url   soap endpoint url
    *
    * @access public
    */
    function SOAP_Transport($url, $encoding = SOAP_DEFAULT_ENCODING)
    {
        parent::SOAP_Base('TRANSPORT');

        $urlparts = @parse_url($url);
        $this->encoding = $encoding;
        
        if (strcasecmp($urlparts['scheme'], 'http') == 0 || strcasecmp($urlparts['scheme'], 'https') == 0) {
            include_once(__GOOGLE_DIR.'SOAP/Transport/HTTP.php');
            $this->transport = new SOAP_Transport_HTTP($url, $encoding);
            return;
        } else if (strcasecmp($urlparts['scheme'], 'mailto') == 0) {
            include_once(__GOOGLE_DIR.'SOAP/Transport/SMTP.php');
            $this->transport = new SOAP_Transport_SMTP($url, $encoding);
            return;
        }
        $this->raiseSoapFault("No Transport for {$urlparts['scheme']}");
    }
    
    /**
    * send a soap package, get a soap response
    *
    * @param string &$soap_data   soap data to be sent (in xml)
    * @param string $action SOAP Action
    * @param int $timeout protocol timeout in seconds
    *
    * @return string &$response   soap response (in xml)
    * @access public
    */
    function &send(&$soap_data, $action = '', $timeout = 0)
    {
        if (!$this->transport) {
            return $this->fault;
        }
        
        $response = $this->transport->send($soap_data, $action, $timeout);
        if (PEAR::isError($response)) {
            return $this->raiseSoapFault($response);
        }
        $this->result_encoding = $this->transport->result_encoding;
        #echo "\n OUTGOING: ".$this->transport->outgoing_payload."\n\n";
        #echo "\n INCOMING: ".preg_replace("/></",">\n<!--CRLF added-->",$this->transport->incoming_payload)."\n\n";
        return $response;
    }

} // end SOAP_Transport
?>