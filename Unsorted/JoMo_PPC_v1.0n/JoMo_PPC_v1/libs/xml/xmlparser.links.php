<?php
$items = array();        
$item = null;    

$index = null;     
                        

function saxStartElement($parser,$name,$attrs)
{
    global $items, $item,$index;

    switch($name)
    {
    	case 'root':
            $items = array();
            break;
           
        case 'result':
            $items = array();
            break;
            
        case 'site':
            $item = array();
            if (in_array('position',array_keys($attrs)))
                $currentNews['position'] = $attrs['position'];
            break;
        default:
        $index = $name;
        break;
    };
}

function saxEndElement($parser,$name)
{
    global $items,$item,$index;

    if ((is_array($item)) && ($name=='site'))
    {
        $items[] = $item;
        $item = null;
    };
    $index = null;
}

function saxCharacterData($parser,$data)
{
    global $item,$index;

    if ((is_array($item)) && ($index))
        $item[$index] = $data;
}


function parseXMLLinks($text, &$error){
	global $items, $index;
	$items = array();
	$index=null;
	
	$parser = xml_parser_create();
	xml_set_element_handler($parser,'saxStartElement','saxEndElement');
	xml_set_character_data_handler($parser,'saxCharacterData');
	xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,false);
	
	//$xml = join('',file('news.xml'));
	//$xml = join('',$filename);
	$xml = $text;
	
	if (!xml_parse($parser,$xml,true)){
	    $error = sprintf('Error XML: %s â ñòðîêå %d',        
	    	xml_error_string(xml_get_error_code($parser)),
	        xml_get_current_line_number($parser) );
	    return array(); 
	    
	}
	
	$error = "no error";
	xml_parser_free($parser);

	return $items;

}

$filename = "1.xml";
$text = join('',file($filename));
$error="";

//echo $text;
$links = parseXMLLinks($text,$error);	
echo "$error<br>";

print_r($links);
?>