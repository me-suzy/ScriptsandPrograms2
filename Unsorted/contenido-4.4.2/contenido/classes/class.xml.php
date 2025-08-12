<?php

/**
 * Class XML_doc
 *
 * Simple class for extracting values
 * from a XML document. Uses a simplified
 * XPath syntax to access the elements.
 * For example: 'root/person/name' would
 * return the value of the 'name' node in
 * 'root/person' node. You have to specify
 * the root node name. Can't access node
 * attributes yet.
 *
 * @copyright four for business AG <http://www.4fb.de>
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @version 0.9
 * @package 4fb_XML
 */
class XML_doc {

    /**
     * @var array $errors
     */
     var $errors = array();

    /**
     * @var string xml
     */
     var $xml;

    /**
     * @var parsed array
     */
    var $parsearray;

    /**
     * @var help array
     */
    var $itemname;

    /**
     * XML Parser Object
     */
    var $parser;

    /**
     * Class Construcor
     */
    function XML_doc() {
      // do nothing
    } // end function


    /**
     * load()
     *
     * Load the XML file
     *
     * @param string XML document filename
	 * @return boolean true if the load was successful
     */
    function load($filename) {

        if (file_exists($filename)) {
            $fp = fopen ($filename, "r");
            
            if ($fp === false)
            {
            	return (false);
            }
            
            unset($this->xml);
            $this->xml = fread ($fp, filesize ($filename));
            fclose ($fp);
            return (true);

        } else {
            //die('no XML file ('.$filename.')');
			return (false);

        }

    } // end function

    /**
     * _getElement()
     *
     * Extract the content of
     * one XML node.
     *
     * @access private
     * @param $xml String XML string
     * @param $start String Start tag of the node -> <name>
     * @param $end String End tag of the node -> </name>
     */
    function _getElement($xml, $start, $end) {

        $startpos = strpos($xml, $start);

        if (!$startpos) {
            $this->errors[] = 'Error 101: No XML file.';
        }

        $endpos = strpos($xml, $end);
        $endpos = $endpos + strlen($end);
        $endpos = $endpos - $startpos;
        $endpos = $endpos - strlen($end);
        $contents = substr ($xml, $startpos, $endpos);
        $contents = substr ($contents, strlen($start));


        //preg_match("/$start(.*?)$end/",$xml,$match);
        return $contents;

    } // end function

    /**
     * valueOf()
     *
     * Extract one node value from the XML document.
     * Use simplified XPath syntax to specify the node.
     * F.e. 'root/test/firstnode'
     *
     * @return String Value of XML node
     */
     function valueOf($xpath) {

        $arr_xpath = explode("/", $xpath);
        $max_elm   = count($arr_xpath) - 1;
        $tmp_xml   = "";
        $tmp_first = true;


        foreach ($arr_xpath as $key => $value) {
            if ($key < $max_elm) {
                // search the tree for
                // the last element
                if ($tmp_first) {
                    $tmp_xml = $this->_getElement($this->xml, "<$value>", "</$value>");
                    $tmp_first = false;

                } else {
                    $tmp_xml = $this->_getElement($tmp_xml, "<$value>", "</$value>");
                }
            } else {
                // last element found,
                // return its value
                $ret=$this->_getElement($tmp_xml, "<$value>", "</$value>");

                if(strstr($ret,"<![CDATA[")){
                   $ret=str_replace("<![CDATA[","",$ret);
                   $ret=str_replace("]]>","",$ret);
                }
                return $ret;
            }

        } // end foreach

    } // end function




    /**
     * parse()
     *
     * Parse the xml file in an array
     *
     *
     *
     * @return array parsearray
     */

    function parse() {
        // set up a new XML parser to do all the work for us
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_element_handler($this->parser, "startElement", "endElement");
        xml_set_character_data_handler($this->parser, "characterData");

        // parse the data and free the parser...
        xml_parse($this->parser, $this->xml);
        xml_parser_free($this->parser);
        return $this->parsearray;
    } // end function

    /**
     *
     *
     *
     */
    function startElement($parser, $name, $attrs) {
        // Start a new Element.  This means we push the new element onto
        // store the name of this element
        $this->itemname[]="$name";
    } // end function

    /**
     *
     *
     *
     */
    function endElement($parser, $name) {
        // End an element.  This is done by popping the last element from
        // the stack and adding it to the previous element on the stack.
        // delete the old elemnt from itemname
        array_pop($this->itemname);
    } // end function

    /**
     *
     *
     *
     */
    function characterData($parser, $data) {
        // Collect the data onto the end of the current chars it dont collect whitespaces.


        $data = eregi_replace ( "[[:space:]]+", " ", $data );
        if(trim($data)){
           //search for the element path
           foreach($this->itemname as $value){
                   $pos.="[$value]";
           }
           //set the new data in the parsearray
           eval("\$this->parsearray$pos=trim(\$data);");

        }

    } // end function








} // end class XML_doc








?>