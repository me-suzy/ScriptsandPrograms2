<?php
/*
+--------------------------------------------------------------------------------+
| RSSReader
+--------------------------------------------------------------------------------+
|
|	Description
|	-> Reads a given RSS feed
|
|	Features
|	-> You can customize the initial opening tag by setting
|		the $main_id var
|	-> You can also customize which tags RSSReader will 
|		recognize as sub tags by adding tags to the $sub_keys
|
|	Usage:
|		$rss = new RSSReader("http://http://rss.news.yahoo.com/rss/topstories");
|		$rss->Read();
|		// For complete feed array:
|		print_r($this->feed['array']);
|		// For just the items:
|		print_r($this->feed['items']);
|
|		OR
|
|		$rss = new RSSReader();
|		print_r($rss->Read("http://rss.news.yahoo.com/rss/topstories"));
|
|	Author:			Matt Froese
|	Contact:		matt@spcan.com
|	Version: 		1.0
|	Last Updated:	July 14, 2005	
|
+--------------------------------------------------------------------------------+
*/
class RSSReader {
	
	var $parser 			= '';
    var $file   			= '';
    var $current_tag		= '';
       
    var $main_id			= 'CHANNEL';
    var $sub_keys			= array ('IMAGE', 'ITEM');	   
    
    var $feed 				= array (	'header' => '',
    									'items' => '' );
    
    var $items              = array();
    var $data 				= array();
    var $feed_data 			= array();
    
    var $errors 			= array();
    
	/*	
	+--------------------------------------------------------------------------------+
	| RSSReader
	+--------------------------------------------------------------------------------+	
	*/
    function RSSReader( $file = '' ) {
        $this->file = ( $file == "" ) ? "" : $file;
    }
    
	/*	
	+--------------------------------------------------------------------------------+
	| Read
	+--------------------------------------------------------------------------------+	
	*/
    function Read( $file = '' ) {    	
       	$this->file = ( $file == "" ) ? $this->file : $file;
        if( preg_match("/^http:\/\/([^\/]+)(.*)$/", $this->file, $matches) ) {

            $host 	= $matches[1];
            $uri 	= $matches[2];

            $request = "GET " . $uri . " HTTP/1.0\r\n";
            $request .= "Host: " . $host . "\r\n";
            $request .= "Connection: close\r\n\r\n";

            if( $http = fsockopen($host, 80, $errno, $errstr, 5) ) {
                fwrite($http, $request);
                $timeout = time() + 5;
                
                $response = "";
                while(time() < $timeout && !feof($http)) {
                    $response .= fgets($http, 4096);
                }
                list($header, $xml) = preg_split("/\r?\n\r?\n/", $response, 2);
                if( preg_match("/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches) ){
                    $status = $matches[1];
                    if( $status == 200 ) {
                        $this->parser = xml_parser_create();
                        xml_set_object($this->parser, $this);
                        xml_set_element_handler($this->parser, "startElement", "endElement");
                        xml_set_character_data_handler($this->parser, "characterData");
                        xml_parse($this->parser, trim($xml));
                    } else {
                        $this->errors[] = "Cannot retrieve feed: HTTP returned <b>" . $status . "</b>.";
                    }
                } else {
                    $this->errors[] = "Cannot get status from header.";
                }
            } else {
               $this->errors[] = "Cannot connect to <b>" . $host .  "</b>.";
            }
        } else {
            $this->errors[] = "Invalid file (" . $this->file . ").";
        }
		$this->feed['array'] 	= $this->feed_data;
		$this->feed['items']	= $this->feed_data['ITEM'];
        return $this->feed['array'];
    }
    
	/*	
	+--------------------------------------------------------------------------------+
	| startElement
	+--------------------------------------------------------------------------------+	
	*/
    function startElement($parser, $name, $attrs) {
        $this->current_tag = $name;
    	
        if( $this->current_tag == $this->main_id ) {
            $this->inside_tag[ $this->main_id ] = true;
    	}
    	foreach( $this->sub_keys as $key ) {
    		if( $this->current_tag == $key ) {
    			$this->inside_tag[ $key ] = true;
    			break;
    		}
    	}
    }

	/*	
	+--------------------------------------------------------------------------------+
	| characterData
	+--------------------------------------------------------------------------------+	
	*/
    function characterData($parser, $data) { 
    	// skip if this element == ""
    	if( trim($data) != "" ) {
	    	if( $this->inside_tag[ $this->main_id ] ) { 			
				$done = false;
		    	foreach( $this->sub_keys as $key ) {
		    		if( isset($this->inside_tag[ $key ]) && $this->inside_tag[ $key ] == true ) {
		    			if( isset($this->data[ $key ][ $this->current_tag ]) ) {
		    				$this->data[ $key ][ $this->current_tag ] 	.= $data;
		    			} else {
		    				$this->data[ $key ][ $this->current_tag ] 	= $data;	
		    			}
		    			$done 										= true;
		    			break;
		    		}
		    	}
	    		if( $done == false ) {
	    			$this->feed_data[ $this->current_tag ] = $data;		
	    		}			
	    	} 
    	}       
    }

	/*	
	+--------------------------------------------------------------------------------+
	| endElement
	+--------------------------------------------------------------------------------+	
	*/
    function endElement($parser, $name) {    
    	    	
    	foreach( $this->sub_keys as $key ) {
    		if( $name == $key ) {
    			$this->inside_tag[ $key ] 					= false;    			
    			$this->feed_data[ $key ][] 	= $this->data[ $key ];
    			$this->data[ $key ] 						= array();
    			break;
    		}
    	}
    	if( $name == $this->main_id ) {
            $this->inside_tag[ $this->main_id ] = false;
    	}    
        
        $this->current_tag = "";
    }
}
?>