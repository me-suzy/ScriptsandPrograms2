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
// $Id: globals.php,v 1.16 2002/04/17 07:42:52 shane Exp $
//

/**
* Global variables and constants of the SOAP classes
* 
* @module   globals 
* @package  SOAP
* @version  $Id: globals.php,v 1.16 2002/04/17 07:42:52 shane Exp $
* @author   Shane Caraveo   Port to PEAR and more
* @author   Dietrich Ayala  Original Author
*/
// make errors handle properly in windows
error_reporting(2039);

/**
* Enable debugging informations?
*
* @const    SOAP_DEBUG
*/
define('SOAP_DEBUG', false);

if (!function_exists('version_compare') ||
    version_compare(phpversion(), '4.1', '<')) {
    die('requires PHP 4.1 or higher\n');
}
if (version_compare(phpversion(), '4.1', '>=') &&
    version_compare(phpversion(), '4.2', '<')) {
    define('FLOAT', 'double');
} else {
    define('FLOAT', 'float');
}

# for float support
# is there a way to calculate INF for the platform?
define('INF',   1.8e307); 
define('NAN',   0.0);

# define types for value
define('SOAP_VALUE_SCALAR',  1);
define('SOAP_VALUE_ARRAY',   2);
define('SOAP_VALUE_STRUCT',  3);


define('SOAP_LIBRARY_NAME', 'PEAR-SOAPx4 0.6');
// set schema version
define('SOAP_XML_SCHEMA_VERSION',   'http://www.w3.org/2001/XMLSchema');
define('SOAP_XML_SCHEMA_1999',      'http://www.w3.org/1999/XMLSchema');
define('SOAP_SCHEMA',               'http://schemas.xmlsoap.org/wsdl/soap/');
define('SOAP_SCHEMA_ENCODING',      'http://schemas.xmlsoap.org/soap/encoding/');
define('SOAP_ENVELOP',              'http://schemas.xmlsoap.org/soap/envelope/');
define('SOAP_INTEROPORG',           'http://soapinterop.org/xsd');
#define('SOAP_APACHE',               'http://xml.apache.org/xml-soap');

$SOAP_XMLSchema = array(SOAP_XML_SCHEMA_VERSION, SOAP_XML_SCHEMA_1999);

// load types into typemap array
$SOAP_typemap[SOAP_XML_SCHEMA_VERSION] = array(
	'string' => 'string',
        'boolean' => 'boolean',
        'float' => FLOAT,
        'double' => 'double',
        'decimal' => FLOAT,
        'duration' => 'integer',
        'dateTime' => 'string',
        'time' => 'string',
	'date' => 'string',
        'gYearMonth' => 'integer',
        'gYear' => 'integer',
        'gMonthDay' => 'integer',
        'gDay' => 'integer',
        'gMonth' => 'integer',
        'hexBinary' => 'string',
        'base64Binary' => 'string',
	// derived datatypes
	'normalizedString' => 'string',
        'token' => 'string',
        'language' => 'string',
        'NMTOKEN' => 'string',
        'NMTOKENS' => 'string',
        'Name' => 'string',
        'NCName' => 'string',
        'ID' => 'string',
	'IDREF' => 'string',
        'IDREFS' => 'string',
        'ENTITY' => 'string',
        'ENTITIES' => 'string',
        'integer' => 'integer',
        'nonPositiveInteger' => 'integer',
	'negativeInteger' => 'integer',
        'long' => 'integer',
        'int' => 'integer',
        'short' => 'integer',
        'byte' => 'string',
        'nonNegativeInteger' => 'integer',
	'unsignedLong' => 'integer',
        'unsignedInt' => 'integer',
        'unsignedShort' => 'integer',
        'unsignedByte' => 'integer',
        'positiveInteger'  => 'integer',
	'anyType' => 'string',
	'anyURI' => 'string',
	'QName' => 'string'
        );
$SOAP_typemap[SOAP_XML_SCHEMA_1999] = array(
	'i4' => 'integer',
        'int' => 'integer',
        'boolean' => 'boolean',
        'string' => 'string',
        'double' => 'double',
        'float' => FLOAT,
        'dateTime' => 'string',
	'timeInstant' => 'string',
        'base64Binary' => 'string',
        'base64' => 'string',
        'ur-type' => 'string'
        );
$SOAP_typemap[SOAP_INTEROPORG] = array('SOAPStruct' => 'array');
$SOAP_typemap[SOAP_SCHEMA_ENCODING] = array('base64' => 'string','array' => 'array','Array' => 'array', 'Struct'=>'array');
#$SOAP_typemap[SOAP_APACHE] = array('Map' => 'array');

// load namespace uris into an array of uri => prefix
$SOAP_namespaces_default = array(
	SOAP_ENVELOP => 'SOAP-ENV',
	SOAP_XML_SCHEMA_VERSION => 'xsd',
	SOAP_XML_SCHEMA_VERSION.'-instance' => 'xsi',
	SOAP_SCHEMA_ENCODING => 'SOAP-ENC',
	SOAP_INTEROPORG=>'si');

$SOAP_namespaces = $SOAP_namespaces_default;

function soap_reset_namespaces() {
    global $SOAP_namespaces, $SOAP_namespaces_default;
    $SOAP_namespaces = $SOAP_namespaces_default;
}

# supported encodings, limited by XML extension
$SOAP_Encodings = array('ISO-8859-1','US-ASCII','UTF-8');
define('SOAP_DEFAULT_ENCODING',  'US-ASCII');

$SOAP_xmlEntities = array ( '&' => '&amp;', '<' => '&lt;', '>' => '&gt;', "'" => '&apos;', '"' => '&quot;' );
    
?>