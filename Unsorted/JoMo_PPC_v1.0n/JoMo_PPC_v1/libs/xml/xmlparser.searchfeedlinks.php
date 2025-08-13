<?php

$linkAttributes = array('Title','URL','URI', 'Description', 'Bid');
$mapLinkAttributes = array('Title'=>"title",'URL'=>"url",'URI'=>"linkURL", 'Description'=>'description', 'Bid'=>"bid");
$_sf_count=0;
$_sf_startcount=0;


$items = array();        
$item = null;    

$index = null;     
                        

function sf_saxStartElement($parser,$name,$attrs)
{
    global $items, $item,$index, $_sf_count,$_sf_startcount;

    switch($name)
    {
    	case 'Listings':
            $items = array();
            break;

    	case 'Count':
            $_sf_count = 0; $_sf_startcount=1;
            break;
           
        case 'Listing':
            $item = array();
            if (in_array('position',array_keys($attrs)))
                $currentNews['position'] = $attrs['position'];
            break;
        default:
        $index = $name;
        break;
    };
}

function sf_saxEndElement($parser,$name)
{
    global $items,$item,$index;
    
    global $_sf_count, $_sf_startcount;

    if ((is_array($item)) && ($name=='Listing'))
    {
    	$item["linkID"]=0;
    	$u = $item["linkURL"];
    	//dprint("end item:u=".$u);

        $items[] = $item;
        $item = null;
    }
    
    if ($name=='Count')
    {
    	$_sf_startcount=2;
    }
    
    $index = null;
}

function sf_saxCharacterData($parser,$data)
{
    global $item,$index;
    global $mapLinkAttributes;
    
    global $_sf_count, $_sf_startcount;

    if ((is_array($item)) && ($index)){
    	$to = $mapLinkAttributes[$index];
    	//dprint("map from=".$index." to =".$to." data=".$data);
    	if ($to=="linkURL" ){
    		if (!isset($item[$to]) || empty($item[$to]))
    			$item[$to] = $data;
    		else
        		$item[$to] .= $data;
        }
        else
        	$item[$to] = $data;
    }
    if ($_sf_startcount==1){
    	$_sf_count = $data;
    }
}


function parseSearchFeedLinks($str, $from ,$page, &$error){
	global $items, $index;
	global $_sf_count, $_sf_startcount;
	
	$pID = getOption("SearchFeedAccount");
	$str = urlencode($str);
//	$filename = "http://www.searchfeed.com/rd/feed/XMLFeed.jsp?cat=$str&excID=162&pID=$pID&nl=$from&page=$page&ip=1.1.1.1";
	$filename = "";
	//dprint("try sf, filename=$filename");
	
	$ff = @fopen ($filename, "r");
	if (!$ff) {
    	//dprint("sf error");
    	return false;
	}

	//dprint("sf parse links");
		
	$text = join('',file($filename));
	
//	echo $text;
//	exit;
		
	$items = array();
	$index=null;
	
	$parser = xml_parser_create();
	xml_set_element_handler($parser,'sf_saxStartElement','sf_saxEndElement');
	xml_set_character_data_handler($parser,'sf_saxCharacterData');
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

	$result["count"] = $_sf_count;
	$result["links"] = $items;
	//return $items;
	//print_r($items);
	return $result;

}

/*
$filename = "1.xml";
$text = join('',file($filename));
$error="";

//echo $text;
$links = parseSearchFeedLnks($text,$error);	
echo "$error<br>";

print_r($links);
*/
?>