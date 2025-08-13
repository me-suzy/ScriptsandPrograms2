<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_xml.php,v $ - $Revision: 1.10 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Parse $xmldata and return the query array
// !!!NOT TO BE USED!!!
// Warning: Call-time pass-by-reference has been deprecated - argument passed by value; If you would like to pass it by reference, modify the declaration of xml_set_object().
function xml_parse_file($xmldata) {
	// Create a new parsing class
	$rss_parser = new RSS_Parser();
	
	// Createa a new XML parser
	$xml_parser = xml_parser_create();

	// Set the object of the parser to our class
	xml_set_object($xml_parser, $rss_parser);

	// Set the start, end and data functions for our parser
	xml_set_element_handler($xml_parser, 'startElement', 'endElement');
	xml_set_character_data_handler($xml_parser, 'characterData');

	// We don't need no uppercasing
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);

	// Parse the file
	xml_parse($xml_parser, $xmldata, true) or xml_error('XML error: '.xml_error_string(xml_get_error_code($xml_parser)).' at line '.xml_get_current_line_number($xml_parser));

	// Close the parser
	xml_parser_free($xml_parser);

	// Return the query array
	return $rss_parser->queryArray;
}

// ############################################################################
// The parsing class, in all its glory
class RSS_Parser {
	var $currentTable = '';
	var $currentField = '';
	var $oldField = '';
	var $currentQuery = array();
	var $queryArray = array();

	function startElement($parser, $tag, $attribs) {
		global $_backup;

		// Is this tag is one of the tables?
		if (array_key_exists($tag, $_backup['tables'])) {
			$this->currentTable = $tag;
			$this->currentQuery = '';
		// Is this tag is one of the fields, inside the current table?
		} elseif (!empty($this->currentTable) and array_contains($tag, $_backup['tables'][$this->currentTable])) {
			$this->currentField = $tag;
		// Guess not...
		} else {
			$this->currentField = '';
		}
	}

	function endElement($parser, $tag) {
		global $_backup;

		// If this tag is one of the tables it means we are done with it
		// So we finalize the query
		if ($this->currentTable == $tag) {
			$this->queryArray['mail'.$this->currentTable][] = xml_unescapeCDATA($this->currentQuery);
			$this->currentQuery = '';
		}
	}

	function characterData($parser, $data) {
		global $_backup;

		// Trim it to remove tabs and stuff like that
		$data = trim($data);

		// If the data is not empty and we are inside a field
		if ($data != '' and !empty($this->currentField)) {
			// Are we still inside the same field (happens with multi-line data
			if ($this->currentField == $this->oldField) {
				$this->currentQuery[$this->currentField] .= "\n".addslashes($data);
			} else {
				$this->currentQuery[$this->currentField] = addslashes($data);
			}
			// Update the old field
			$this->oldField = $this->currentField;
		}
	}
}

// ############################################################################
// Trigger an error
function xml_error($error) {
	$error = addslashes($error);
	$error = str_replace("\\'", "'", $error);
	eval('standarderror("'.$error.'");');
}

// ############################################################################
// Escape CDATA tags
function xml_escapeCDATA($xml) {
    return str_replace(array('<![CDATA[', ']]>'), array('«![CDATA[', ']]»'), $xml);
}

// ############################################################################
// Unescape CDATA tags
function xml_unescapeCDATA($xml) {
    return str_replace(array('«![CDATA[', ']]»'), array('<![CDATA[', ']]>'), $xml);
}

// ############################################################################
// The function that formats data that goes into XML
function xml_dataformat($xml) {
	if (preg_match('#[\<\>\&\'\"\[\]]#', $xml)) {
		$xml = '<![CDATA['.xml_escapeCDATA($xml).']]>';
	}
	return $xml;
}

?>