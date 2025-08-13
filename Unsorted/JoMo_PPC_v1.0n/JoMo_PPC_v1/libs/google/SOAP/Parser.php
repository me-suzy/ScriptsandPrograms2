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
// $Id: Parser.php,v 1.20 2002/04/16 07:41:11 shane Exp $
//

require_once __GOOGLE_DIR.'SOAP/globals.php';
require_once __GOOGLE_DIR.'SOAP/Base.php';
require_once __GOOGLE_DIR.'SOAP/Value.php';

/**
* SOAP Parser
* this class is used by SOAP::Message and SOAP::Server to parse soap packets
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* @access public
* @version $Id: Parser.php,v 1.20 2002/04/16 07:41:11 shane Exp $
* @package SOAP::Parser
* @author Shane Caraveo  Conversion to PEAR and updates
* @author Dietrich Ayala Original Author
*/
class SOAP_Parser extends SOAP_Base
{
    var $status = '';
    var $position = 0;
    var $pos_stat = 0;
    var $depth = 0;
    var $default_namespace = '';
    var $namespaces = array();
    var $message = array();
    var $depth_array = array();
    var $previous_element = '';
    var $soapresponse = NULL;
    var $parent = 0;
    var $root_struct_name = array();
    var $header_struct_name = array();
    var $curent_root_struct_name = '';
    var $entities = array ( '&' => '&amp;', '<' => '&lt;', '>' => '&gt;', "'" => '&apos;', '"' => '&quot;' );
    var $xml = '';
    var $xml_encoding = '';
    var $root_struct = array();
    var $header_struct = array();
    var $curent_root_struct = 0;
    var $references = array();
    var $need_references = array();
    var $XMLSchemaVersion;
    var $bodyDepth; // used to handle non-root elements before root body element
    
    function SOAP_Parser($xml, $encoding = SOAP_DEFAULT_ENCODING)
    {
        parent::SOAP_Base('Parser');
        $this->setSchemaVersion(SOAP_XML_SCHEMA_VERSION);
        
        $this->xml = $xml;
        $this->xml_encoding = $encoding;

        // check the xml tag for encoding
        if (preg_match('/<\?xml.*?encoding=(?:[\'|"])([\w\d-]*)?.*?(?:[\'|"])\?>/',$xml,$m)) {
            $this->xml_encoding = strtoupper($m[1]);
        }
        
        # XXX need to do this better, remove newlines after > or before <
        $this->xml = preg_replace("/>[\r\n|\n]/", '>', $this->xml);
        $this->xml = preg_replace("/[\r\n|\n]</", '<', $this->xml);
        
        // determines where in the message we are (envelope,header,body,method)
        // Check whether content has been read.
        if (!empty($this->xml)) {
            // prepare the xml parser
            $parser = xml_parser_create($this->xml_encoding);
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            xml_set_object($parser, $this);
            xml_set_element_handler($parser, 'startElement','endElement');
            xml_set_character_data_handler($parser,'characterData');

            // Parse the XML file.
            if (!xml_parse($parser,$this->xml,true)) {
                $err = sprintf('XML error on line %d: %s',
                    xml_get_current_line_number($parser),
                    xml_error_string(xml_get_error_code($parser)));
                $this->raiseSoapFault($err,htmlspecialchars($this->xml));
            } else {
                // build the response
                if (count($this->root_struct))
                    $this->soapresponse = $this->buildResponse($this->root_struct[0]);
                if (count($this->header_struct))
                    $this->soapheaders = $this->buildResponse($this->header_struct[0]);
            }
            xml_parser_free($parser);
        }
    }
    
    function setSchemaVersion($schemaVersion)
    {
        global $SOAP_namespaces;
        $this->XMLSchemaVersion = $schemaVersion;
        $tmpNS = array_flip($SOAP_namespaces);
        $tmpNS['xsd'] = $this->XMLSchemaVersion;
        $tmpNS['xsi'] = $this->XMLSchemaVersion.'-instance';
        $SOAP_namespaces = array_flip($tmpNS);
    }
    
    // recurse to build a multi-dim array
    function domulti($d, &$ar, &$r, &$v, $ad=0)
    {
        if ($d) {
            $this->domulti($d-1, $ar, $r[$ar[$ad]], $v, $ad+1);
        } else {
            $r = $v;
        }
    }
    
    // loop through msg, building response structures
    function buildResponse($pos)
    {
        $response = NULL;

        if ($this->message[$pos]['children'] != '') {
            $children = explode('|',$this->message[$pos]['children']);

            foreach ($children as $c => $child_pos) {
                if ($this->message[$child_pos]['type'] != NULL) {
                    $response[] = $this->buildResponse($child_pos);
                }
            }
            if (array_key_exists('arraySize',$this->message[$pos])) {
                $ardepth = count($this->message[$pos]['arraySize']);
                if ($ardepth > 1) {
                    $ar = array_pad(array(), $ardepth, 0);
                    if (array_key_exists('arrayOffset',$this->message[$pos])) {
                        for ($i = 0; $i < $ardepth; $i++) {
                            $ar[$i] += $this->message[$pos]['arrayOffset'][$i];
                        }
                    }
                    $elc = count($response);
                    for ($i = 0; $i < $elc; $i++) {
                        // recurse to build a multi-dim array
                        $this->domulti($ardepth, $ar, $newresp, $response[$i]);
                        
                        # increment our array pointers
                        $ad = $ardepth - 1;
                        $ar[$ad]++;
                        while ($ad > 0 && $ar[$ad] >= $this->message[$pos]['arraySize'][$ad]) {
                            $ar[$ad] = 0;
                            $ad--;
                            $ar[$ad]++;
                        }
                    }
                    $response = $newresp;
                } else if ($this->message[$pos]['arrayOffset'][0] > 0) {
                    # check for padding
                    $pad = $this->message[$pos]['arrayOffset'][0]+count($response)*-1;
                    $response = array_pad($response,$pad,NULL);
                }
            }
        }
        // add current node's value
        if ($response) {
            $response = new SOAP_Value($this->message[$pos]["name"], $this->message[$pos]["type"] , $response, $this->message[$pos]["namespace"], $this->message[$pos]['type_namespace']);
        } else {
            $response = new SOAP_Value($this->message[$pos]['name'], $this->message[$pos]['type'] , $this->message[$pos]['cdata'], $this->message[$pos]["namespace"],  $this->message[$pos]['type_namespace']);
        }
        // handle header attribute that we need
        if (array_key_exists('actor',$this->message[$pos])) {
            $response->actor = $this->message[$pos]['actor'];
        }
        if (array_key_exists('mustUnderstand',$this->message[$pos])) {
            $response->mustunderstand = $this->message[$pos]['mustUnderstand'];
        }
        return $response;
    }
    
    // start-element handler
    function startElement($parser, $name, $attrs)
    {
        global $SOAP_XMLSchema;
        // position in a total number of elements, starting from 0
        // update class level pos
        $pos = $this->position++;

        // and set mine
        $this->message[$pos] = array();
        $this->message[$pos]['pos'] = $pos;
        $this->message[$pos]['id'] = '';
        
        // parent/child/depth determinations
        
        // depth = how many levels removed from root?
        // set mine as current global depth and increment global depth value
        $this->message[$pos]['depth'] = $this->depth++;
        
        // else add self as child to whoever the current parent is
        if ($pos != 0) {
            $this->message[$this->parent]['children'] .= "|$pos";
        }

        // set my parent
        $this->message[$pos]['parent'] = $this->parent;

        // set self as current value for this depth
        $this->depth_array[$this->depth] = $pos;
        // set self as current parent
        $this->parent = $pos;
        $qname = new QName($name);
        // set status
        if (strcasecmp('envelope',$qname->name)==0) {
            $this->status = 'envelope';
        } elseif (strcasecmp('header',$qname->name)==0) {
            $this->status = 'header';
            $this->header_struct_name[] = $this->curent_root_struct_name = $qname->name;
            $this->header_struct[] = $this->curent_root_struct = $pos;
            $this->message[$pos]['type'] = 'Struct';
        } elseif (strcasecmp('body',$qname->name)==0) {
            $this->status = 'body';
            $this->bodyDepth = $this->depth;
        // set method
        } elseif ($this->status == 'body') {
            // is this element allowed to be a root?
            // XXX this needs to be optimized, we loop through attrs twice now
            $can_root = $this->depth == $this->bodyDepth + 1;
            if ($can_root) {
                foreach ($attrs as $key => $value) {
                    if (stristr($key, ':root') && !$value) {
                        $can_root = FALSE;
                    }
                }
            }

            if ($can_root) {
                $this->status = 'method';
                $this->root_struct_name[] = $this->curent_root_struct_name = $qname->name;
                $this->root_struct[] = $this->curent_root_struct = $pos;
                $this->message[$pos]['type'] = 'Struct';
            }
        }

        // set my status
        $this->message[$pos]['status'] = $this->status;
        
        // set name
        $this->message[$pos]['name'] = htmlspecialchars($qname->name);

        // set attrs
        $this->message[$pos]['attrs'] = $attrs;

        // see if namespace is defined in tag
        if (array_key_exists('xmlns:'.$qname->ns,$attrs)) {
            $namespace = $attrs['xmlns:'.$qname->ns];
        } else {
        // get namespace
            $namespace = $qname->ns?$this->namespaces[$qname->ns]:$this->default_namespace;
        }
        $this->message[$pos]['namespace'] = $namespace;
        $this->default_namespace = $namespace;

        // loop through atts, logging ns and type declarations
        foreach ($attrs as $key => $value) {
            // if ns declarations, add to class level array of valid namespaces
            $kqn = new QName($key);
            if ($kqn->ns == 'xmlns') {
                $prefix = $kqn->name;

                if (in_array($value, $SOAP_XMLSchema)) {
                    $this->setSchemaVersion($value);
                }

                $this->namespaces[$prefix] = $value;

                // set method namespace
                # XXX unused???
                #if ($name == $this->curent_root_struct_name) {
                #    $this->methodNamespace = $value;
                #}
            
            } elseif ($kqn->name == 'actor') {
                $this->message[$pos]['actor'] = $value;
            } elseif ($kqn->name == 'mustUnderstand') {
                $this->message[$pos]['mustUnderstand'] = $value;
                
            // if it's a type declaration, set type
            } elseif ($kqn->name == 'type') {
                $vqn = new QName($value);
                $this->message[$pos]['type'] = $vqn->name;
                $this->message[$pos]['type_namespace'] = $this->namespaces[$vqn->ns];
                #print "set type for {$this->message[$pos]['name']} to {$this->message[$pos]['type']}\n";
                // should do something here with the namespace of specified type?
                
            } elseif ($kqn->name == 'arrayType') {
                $vqn = new QName($value);
                $this->message[$pos]['type'] = 'Array';
                $type = $vqn->name;
                $sa = strpos($type,'[');
                if ($sa > 0) {
                    $this->message[$pos]['arraySize'] = split(',',substr($type,$sa+1, strlen($type)-$sa-2));
                    $type = substr($type, 0, $sa);
                }
                $this->message[$pos]['arrayType'] = $type;
                
            } elseif ($kqn->name == 'offset') {
                $this->message[$pos]['arrayOffset'] = split(',',substr($value, 1, strlen($value)-2));
                
            } elseif ($kqn->name == 'id') {
                # save id to reference array
                $this->references[$value] = $pos;
                $this->message[$pos]['id'] = $value;
                
            } elseif ($kqn->name == 'href' && $value[0] == '#') {
                $ref = substr($value, 1);
                if (array_key_exists($ref,$this->references)) {
                    # cdata, type, inval
                    $ref_pos = $this->references[$ref];
                    $this->message[$pos]['children'] = &$this->message[$ref_pos]['children'];
                    $this->message[$pos]['cdata'] = &$this->message[$ref_pos]['cdata'];
                    $this->message[$pos]['type'] = &$this->message[$ref_pos]['type'];
                    $this->message[$pos]['arraySize'] = &$this->message[$ref_pos]['arraySize'];
                    $this->message[$pos]['arrayType'] = &$this->message[$ref_pos]['arrayType'];
                } else {
                    # reverse reference, store in 'need reference'
                    if (!is_array($this->need_references[$ref])) $this->need_references[$ref] = array();
                    $this->need_references[$ref][] = $pos;
                }
            }
        }
    }
    
    // end-element handler
    function endElement($parser, $name)
    {
        // position of current element is equal to the last value left in depth_array for my depth
        $pos = $this->depth_array[$this->depth];
        // bring depth down a notch
        $this->depth--;
        $qname = new QName($name);
        
        // get type if not explicitly declared in an xsi:type attribute
        // XXX check on integrating wsdl validation here
        if ($this->message[$pos]['type'] == '') {
            if ($this->message[$pos]['children'] != '') {
                /* this is slow, need to look at some faster method
                $children = explode('|',$this->message[$pos]['children']);
                if (count($children) > 2 &&
                    $this->message[$children[1]]['name'] == $this->message[$children[2]]['name']) {
                    $this->message[$pos]['type'] = 'Array';
                } else { 
                    $this->message[$pos]['type'] = 'Struct';
                }*/
                $this->message[$pos]['type'] = 'Struct';
            } else {
                $parent = $this->message[$pos]['parent'];
                if ($this->message[$parent]['type'] == 'Array' &&
                  array_key_exists('arrayType', $this->message[$parent])) {
                    $this->message[$pos]['type'] = $this->message[$parent]['arrayType'];
                } else {
                    $this->message[$pos]['type'] = 'string';
                }
            }
        }
        
        // if tag we are currently closing is the method wrapper
        if ($pos == $this->curent_root_struct) {
            $this->status = 'body';
        } elseif ($qname->name == 'Body' || $qname->name == 'Header') {
            $this->status = 'envelope';
        }

        // set parent back to my parent
        $this->parent = $this->message[$pos]['parent'];
        
        # handle any reverse references now
        $idref = $this->message[$pos]['id'];

        if ($idref != '' && array_key_exists($idref,$this->need_references)) {
            foreach ($this->need_references[$idref] as $ref_pos) {
                #XXX is this stuff there already?
                $this->message[$ref_pos]['children'] = &$this->message[$pos]['children'];
                $this->message[$ref_pos]['cdata'] = &$this->message[$pos]['cdata'];
                $this->message[$ref_pos]['type'] = &$this->message[$pos]['type'];
                $this->message[$ref_pos]['arraySize'] = &$this->message[$pos]['arraySize'];
                $this->message[$ref_pos]['arrayType'] = &$this->message[$pos]['arrayType'];
            }
            # wipe out our waiting list
            # $this->need_references[$idref] = array();
        }
    }
    
    // element content handler
    function characterData($parser, $data)
    {
        $pos = $this->depth_array[$this->depth];
        $this->message[$pos]['cdata'] .= $data;
    }
    
    // have this return a soap_val object
    function getResponse()
    {
        if ($this->soapresponse) {
            return $this->soapresponse;
        }
        return $this->raiseSoapFault("couldn't build response");
    }

    // have this return a soap_val object
    function getHeaders()
    {
        if ($this->soapheaders) {
            return $this->soapheaders;
        }
        // we don't fault if there are no headers
        // that can be handled by the app if necessary
        return NULL;
    }
    
    function decodeEntities($text)
    {
        $trans_tbl = array_flip($this->entities);
        return strtr($text, $trans_tbl);
    }
}

?>
