<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  16th August 2005                       #||
||#     Filename: wbxmlUpdate.php                        #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package Update Service
*/

if (!defined('wbnews'))
	die('Hacking Attempt');


class wbxmlUpdate
{

/**
    @var private String element     - next set of elements
    @var private Array lastElement   - The very last set of elements
    @var private Array apiInfo      - api information
    @var private String currentTag  - the current tag where in
    @var private Resource xmlParser - The XML Parser
*/
var $element;
var $lastElement = array();
var $apiInfo = array();
var $currentTag = "";
var $xmlParser;

    /**
        
        @access public
        @param String elementName - The Element we will store the current into cache
    */
    function wbxmlUpdate($elementName)
    {
        $this->element = strtoupper($elementName);
        $this->xmlParser = xml_parser_create();
        xml_set_object($this->xmlParser, $this);
        xml_set_element_handler($this->xmlParser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->xmlParser, "cdata");
        
        // kill objects
        register_shutdown_function(array( &$this, "destroy"));
    }
    
    /**
        Parsers the XML String
    
        @access public
        @param String $xmlData - An XML String
    */
    function parseXML($xmlData)
    {
        xml_parse($this->xmlParser, $xmlData);
    }
    
    /**
        Gets the opening tag and attributes
        
        @access private
        @param Resource parser - XML Parser
        @param String tagName - the current tag name
        @param Array attrs - An Array of Attributes
    */
    function tag_open($parser, $tagName, $attrs)
    {
        if (!is_resource($parser))
            die(__METHOD__ ." : not a parser");
            
        $this->currentTag = $tagName;
        if ($tagName === "APP")
            $this->apiInfo = $attrs;
        else
        {
            $this->lastElement[$this->currentTag] = array();
            if (sizeof($attrs) != 0)
                $this->lastElement[$this->currentTag]['attrs'] = $attrs;
        }
    }
    
    /**
        Gets the closing tag
    
        @access private
        @param Resource parser - XML Parser
        @param String tagName - the Current tag name
    */
    function tag_close($parser, $tagName)
    {
        if (!is_resource($parser))
            die (__METHOD__ ." : not a parser");
        
        if (empty($tagName))
            die (__METHOD__ . " : tagName is empty");
    }
    
    /**
        Gets the data between the tags
    
        @access private
        @param Resource parser - XML Parser
        @param String data - Data within the tag
    */
    function cdata($parser, $data)
    {
        if (!is_resource($parser))
            die (__METHOD__ ." : not a parser");
            
        $data = trim($data);
        if (!empty($data))
            $this->lastElement[$this->currentTag]['value'] = $data;
    }
    
    /**
        Get the Newest Version
        
        @access public
        @return int / boolean
    */
    function getVersion()
    {
        if (is_resource($this->xmlParser))
            return $this->lastElement['APPLICATION']['attrs']['VERSION'];
        else
            return false;
    }
    
    /**
        Get all features into an Array
        
        @access public
        @return Array / boolean
    */
    function getFeatures()
    {
        if (is_resource($this->xmlParser))
            return explode(", ", $this->lastElement['FEATURES']['value']);
        else
            return false;
    }
    
    /**
        Get all features into an Array
        
        @access public
        @return Array / boolean
    */
    function getRequirements()
    {
        if (is_resource($this->xmlParser))
            return explode(", ", $this->lastElement['REQUIREMENTS']['value']);
        else
            return false;
    }
    
    /**
        Get all features into an Array
        
        @access public
        @return Array / boolean
    */
    function getDownloadLink()
    {
        if (is_resource($this->xmlParser))
            return $this->lastElement['DOWNLOAD-ADDR']['value'];
        else
            return false;
    }
    
    /**
        Get all features into an Array
        
        @access public
        @return Array / boolean
    */
    function getInfoLink()
    {
        if (is_resource($this->xmlParser))
            return $this->lastElement['INFORMATION-ADDR']['value'];
        else
            return false;
    }
    
    /**
        Destroys the Object
    
        @access private
    */
    function destroy()
    {
        xml_parser_free($this->xmlParser);
    }
    
}

?>
