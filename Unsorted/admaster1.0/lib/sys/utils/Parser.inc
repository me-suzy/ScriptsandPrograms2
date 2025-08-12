<?

class Parser
{
	var $parser;
    var $file;
    var $output;
    var $input;
    var $open_tag;
    var $close_tag;
    var $current_tag;

    function Parser ($input = "")
    {
    	$this->parser 		= "";
    	$this->file   		= "";
    	$this->output 		= "";
    	$this->current_tag  = "";

    	if (strlen($input))
    		$this->input = $input;
    	else
    		$this->input = 0;

    	$this->open_tag  = array ();
    	$this->close_tag = array ();
    }
    function destroy()
    {
    	xml_parser_free($this->parser);
    }

    function pickFile ($fileName)
    {
    	$this->fileName = $fileName;
    	$fhandle = fopen ($fileName, "r");
    	$buff = utf8_decode (fread ($fhandle, 3));
    	$this->input = utf8_decode (fread ($fhandle, filesize ($fileName)));
    	if ($buff == "<xm")
    		$this->input = $buff.$this->input;
		fclose ($fhandle);		
    }
    function startElement($parser, $name, $attrs)
    {
    	$this->current_tag = $name;
    	$this->onStartElement ($name, $attrs);
    }

    function endElement($parser, $name)
    {
    	$this->onEndElement ($name);
   	}

   	function characterData($parser, $data)
   	{
    	$this->onCharacterData ($data);
    }

    function parse()
    {
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, &$this);

        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($this->parser, "startElement", "endElement");
        xml_set_character_data_handler($this->parser, "characterData");
 		
   		new Debug ("XML parsing begin --------------------", "Parser", "parse", "WARNING", "SYS");
   		if (!@xml_parse($this->parser, $this->input))
   		{
   			new Debug ("XML Parser error. Write to: daboheny@yahoo.com", "Parser", "parse", "ERROR", "SYS");

           	return false;
    	}
    	new Debug ("XML parsing end   --------------------", "Parser", "parse", "WARNING", "SYS");
    }

    function onStartElement ($name, $attrs)  {}
    function onEndElement ($name)    {}
    function onCharacterData ($data) {}

}

?>