<?php
// function for the importing export languages
function export_language($title = ""){
	global $db, $dbprefix, $config;
	
	if ($title == ""){ $title = "Untitled"; }
	
	$sql = "SELECT * FROM "  . $dbprefix . "phrases ORDER BY phrase_name ASC";
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "No phrases found"; }
	
	header("Content-type: text/xml\n\n");
	header("Content-Disposition: attachment; filename=" . $title . ".xml");
	
	echo("<?xml version=\"1.0\" ?>\n");
	echo("<languagepack>\n");
	echo("	<title>" . $title . "</title>\n");
	echo("	<software>Particle Links</software>\n");
	echo("	<version>" . $config["version"] . "</version>\n");
	echo("	<published>" . date("j F Y") . "</published>\n");
	echo("	<phrases>");
	
	do {
	echo("		<phrase name=\"" . $rec->fields["phrase_name"] . "\">" . $rec->fields["phrase_value"] . "</phrase>\n");
	} while ($rec->loop());
	
	echo("	</phrases>\n");
	echo("</languagepack>");
	
	// kill script to prevent page being written
	die();
}

function import_language($xmlfile){
	global $db, $dbprefix;
	
	// setup reading functions
	function startElement($parser, $name, $attrs){
		global $currentTag, $currentAtr;
		
		$currentTag = $name;
		$currentAtr = $attrs;
	}
	
	function endElement($parser, $name){
		global $currentTag, $currentAtr;
		
		$currentTag = "";
		$currentAtr = "";
	}
	
	function characterData($parser, $data){
		global $currentTag, $currentAtr, $db, $dbprefix;
		
		if ($currentTag == "PHRASE"){
			// read this!
			$pname = $currentAtr["NAME"];
			
			if ($pname <> ""){
				// this has a name, let's do this thing
				$sql = "SELECT * FROM " . $dbprefix . "phrases WHERE phrase_name = '" . addslashes($pname) . "'";
				$phr = $db->execute($sql);
				if ($phr->rows > 0){
					// this is an update job
					$sql  = "UPDATE " . $dbprefix . "phrases SET phrase_value = '" . addslashes($data) . "' ";
					$sql .= "WHERE phrase_name = '" . addslashes($pname) . "'";
					$db->execute($sql);
				} else {
					// insert new phrase
					$sql  = "INSERT INTO " . $dbprefix . "phrases (phrase_name, phrase_value) VALUES ";
					$sql .= "'" . addslashes($pname) . "', ";
					$sql .= "'" . addslashes($data) . "')";
					$db->execute($sql);
				}
			}
		}
	}
	
	// start up the parser
	$p = xml_parser_create();
	xml_set_element_handler($p, "startElement", "endElement");
	xml_set_character_data_handler($p, "characterData");
	$currentTag = "";
	$currentAtr = "";
	
	// open the file
	$filepath = $xmlfile["tmp_name"];
	if (!($fp = fopen($filepath, "r"))) {
	   return "could not open XML input";
	}
	
	// read the file
	while ($data = fread($fp, 4096)) {
		if (!xml_parse($p, $data, feof($fp))) {
			return sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser));
		}
	}
	
	// free up memory
	xml_parser_free($p);
	
	// probably worked by now
	return "Phrases imported successfully!";
}
?>