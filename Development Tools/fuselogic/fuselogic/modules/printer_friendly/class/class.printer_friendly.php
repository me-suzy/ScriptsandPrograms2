<?php

require_once('class.string.php');

class printer_friendly{
    
		function printer_friendly(){
		
		}
		
		function img_src_strip($variable){
		    return(eregi_replace("<img src=[^>]*>", "", $variable));
    }

    function img_border_strip($variable){
		    return(eregi_replace("<img border=[^>]*>", "", $variable));
    }	

    function font_strip($variable){		    
				return $this->tag_strip($variable,'font');
    }
						
		function img_strip($variable){
		    return $this->tag_strip($variable,'img');				
    }
		
		function tag_strip($variable,$tag = ''){
		    if(!empty($tag)){
		        $variable = eregi_replace("<".$tag."[^>]*>","", $variable);
				    $variable = eregi_replace("</".$tag.">","", $variable);
				    return $variable;
				}
    } 
		
		function tag_inside_strip($string,$tag = ''){
		    if(!empty($tag)){
				    $class_string = &new _string();
						$count = 50;
						for($i=0;$i<$count;$i++){
						    if(!$class_string->exists_i('</'.$tag.'>',$string)) break;
								
								$end_tag = '</'.$tag.'>';
								$start_tag = '<'.$tag;
												
		            $end_tag_position = $class_string->first_position_i($end_tag,$string); 
						    $string1 = $class_string->get(0,$end_tag_position['right'],$string); 		
								$string1 = $class_string->reverse($string1);
								$start_tag_reverse = $class_string->reverse($start_tag);		
											
						    $start_tag_position = $class_string->first_position_i($start_tag_reverse,$string1);	
								$start_tag_position['left'] = $end_tag_position['right'] - $start_tag_position['right'];					
						    $string1 =  $class_string->get($start_tag_position['left'],$end_tag_position['right'],$string);						
						    $string = str_replace($string1,'',$string);
												
						}
						return $string;
				}
    }
		
		function color_strip($variable){
		    
				$attributes = array();
				$attributes[] = 'bgcolor';
				$attributes[] = 'color';
				$attributes[] = 'bordercolor';
				$attributes[] = 'background';
				$attributes[] = 'background-color';
				$attributes[] = 'outline-color';
				
								
				$count = count($attributes);
				for($i=0;$i<$count;$i++){
		        $variable = $this->_color_strip($variable,$attributes[$i]);
				}
				return $variable;
		}
		
		function _color_strip($variable,$attribute_name = ''){
		    if(!empty($attribute_name)){
		        $variable = eregi_replace(' '.$attribute_name.'[:=][\'"]#[0-9a-f]+[\'"]', "", $variable);				
				    return $variable;	
				}
    }
		 
		function attribute_strip($variable,$attribute_name = ''){
		    if(!empty($attribute_name)){
		        $variable = eregi_replace('^'.$attribute_name.'=".*"$'," ",$variable);				
				    return $variable;	
				}
    }
				 
    function run($string = ''){		
		    $string = $this->color_strip($string);
				$string = $this->tag_strip($string,'span');
				//$string = $this->tag_strip($string,'font');
				$string = $this->tag_strip($string,'img');								
				$string = $this->tag_inside_strip($string,'noscript');
				$string = $this->tag_inside_strip($string,'script');
				$string = $this->tag_inside_strip($string,'SCRIPT');												
				$string = $this->tag_strip($string,'link');				
		    return $string;
		}
		
}

?>