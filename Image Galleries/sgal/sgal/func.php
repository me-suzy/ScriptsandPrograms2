<?php 
function write_xml($images_array,$file_name)
        {

                $file_handle=fopen($file_name,'w+');
                fwrite($file_handle,'<?xml version="1.0" encoding="ISO-8859-1"?><images>');
                foreach($images_array as $image)
                {
                        if(trim($image['url'])!=="")
                       {
                        fwrite($file_handle,'<image><imageName>' . $image['imageName'] . '</imageName>');
                        fwrite($file_handle, '<update>'.$image['upDate'] . '</update>');
                        fwrite($file_handle, '<lastDate>'.$image['lastDate'] . '</lastDate>');
                        fwrite($file_handle,'<url>' . $image['url'] . '</url>');
                        fwrite($file_handle,'<caption>' . $image['caption'] . '</caption>');
                        fwrite($file_handle,'<hits>' . $image['hits'] . '</hits></image>');
                       }
                }
                fwrite($file_handle,'</images>');
                fclose($file_handle);

        }
/* initiating variables */ 
$i= 0; 
$cur = 'none'; 


/* defining handlers */ 

function startElement($parser, $element_name, $attrs) { 
    global $cur; 
    global $i; 
  switch($element_name) { 
      case 'IMAGENAME': 
          $cur='imageName'; 
      break;
    case 'UPDATE' :
        $cur='upDate';
      break;
    case 'LASTDATE' :        
        $cur='lastDate'; 
      break; 
    case 'URL' :             
        $cur='url'; 
      break; 
    case 'HITS' :    
        $cur='hits'; 
      break;
    case 'CAPTION':
        $cur='caption';
    } 
} 
function characterData($parser, $xml_data) { 
    global $i; 
    global $cur; 
    global $images; 
   
  if (trim($xml_data)) {       
      $images[$i][$cur] = $xml_data; 
  } 
} 
function endElement($parser, $element_name) { 
    global $i; 
  if($element_name=='IMAGE') { 
    $i++; 
  } 
} 


?> 
