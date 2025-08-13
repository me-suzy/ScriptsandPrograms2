<?php
//
// +----------------------------------------------------------------------+
// | PHP Interface to the Google API                                      |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Sebastian Bergmann                                           |
// +----------------------------------------------------------------------+
//

require_once __GOOGLE_DIR.'SOAP/Client.php';

/**
* PHP Interface to the Google API
*
* @author  Sebastian Bergmann
* @access  public
*/
class SOAP_Google {
    /**
    * @var    string
    * @access private
    */
    var $_licenseKey = '';

    /**
    * @var    object
    * @access private
    */
    var $_soapClient = NULL;

    /**
    * Constructor.
    *
    * @param  string
    * @access public
    */
    function SOAP_Google($licenseKey) {
        $this->_licenseKey = $licenseKey;
        //$this->_licenseKey = "DccRTSDhAARiU8EI/B+vuYxWXwZHf/bi";

        $this->_soapClient = new SOAP_Client(
          'http://api.google.com/search/beta2'
        );
    }

    /**
    * Retrieves a page by URL from the Google Cache.
    *
    * @param  string
    * @return mixed
    * @access public
    */
    function getCachedPage($url) {
        $result = $this->_performAPICall(
          'doGetCachedPage',

          array(
            'key' => $this->_licenseKey,
            'url' => $url
          )
        );

        if ($result) {
            $result = base64_decode($result);
        }

        return $result;
    }

    /**
    * Retrieves a spelling suggestion for a phrase.
    *
    * @param  string
    * @return mixed
    * @access public
    */
    function getSpellingSuggestion($phrase) {
        return $this->_performAPICall(
          'doSpellingSuggestion',

          array(
            'key'    => $this->_licenseKey,
            'phrase' => $phrase
          )
        );
    }

    /**
    * Performs a web search.
    *
    * @param  array
    * @return mixed
    * @access public
    */
    function search($parameters = array()) {
        if (!isset($parameters['query'])) {
            return false;
        }

        return $this->_performAPICall(
          'doGoogleSearch',

          array(
            'key'         => $this->_licenseKey,
            'q'           => $parameters['query'],
            'start'       => isset($parameters['start'])      ? $parameters['start']      : 0,
            'maxResults'  => isset($parameters['maxResults']) ? $parameters['maxResults'] : 10,
            'filter'      => isset($parameters['filter'])     ? $parameters['filter']     : false,
            'restrict'    => isset($parameters['restrict'])   ? $parameters['restrict']   : '',
            'safeSearch'  => isset($parameters['safeSearch']) ? $parameters['safeSearch'] : false,
            'lr'          => isset($parameters['lr'])         ? $parameters['lr']         : '',
            'ie'          => isset($parameters['ie'])         ? $parameters['ie']         : '',
            'oe'          => isset($parameters['oe'])         ? $parameters['oe']         : ''
          )
        );
    }

    /**
    * @param  string
    * @param  array
    * @return mixed
    * @access private
    */
    function _performAPICall($apiCall, $parameters) {
        $result = $this->_soapClient->call(
          $apiCall,
          $parameters,
          'urn:GoogleSearch'
        );

        if (!PEAR::isError($result)) {
            return $result;
        } else {
            return false;
        }
    }
}
?>
