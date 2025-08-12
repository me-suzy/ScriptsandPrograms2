<?php

class _string{
    
		function _string(){
		
		}
		
		function first_position($match ='',$string = '',$ofset = 0){
		    if(!empty($match) and !empty($string)){
				    $left = strpos($string,$match,$ofset);
						$right = $left + strlen($match) - 1;
		        return array('left'=>$left,'right' => $right);
				}				
    }
		
    function first_position_i($match='',$string = '',$ofset = 0){
		    if(!empty($match) and !empty($string)){
				    $left = strpos(strtolower($string),strtolower($match),$ofset);
						$right = $left + strlen($match) - 1;
		        return array('left'=>$left,'right' => $right);
				}				
    }
		/*
		function last_position($match = '',$string = '',$ofset = 0){
		    if(!empty($match) and !empty($string)){
				    $left = strrpos($string,$match,$ofset);
						$right = $left + strlen($match) - 1;
		        return array('left'=>$left,'right' => $right);
				}				
    }
    function last_position_i($match='',$string = '',$ofset = 0){
		    if(!empty($match) and !empty($string)){
				    $left = strrpos(strtolower($string),strtolower($match),$ofset);
						$right = $left + strlen($match) - 1;
		        return array('left'=>$left,'right' => $right);
				}				
    }
		*/
		function get($left = 0, $right = 0,$string = ''){
		    if(!empty($string)){
				    $length = $right - $left + 1;
				    return substr($string,$left,$length);				
				}		
		}
		
		function exists($match='',$string=''){
		    if(!empty($match) and !empty($string)){
		        if(strpos($string,$match) === False) return False;
						else return True;		
		    }
		}
		
		function exists_i($match='',$string=''){
		    if(!empty($match) and !empty($string)){
		        if(strpos(strtolower($string),strtolower($match)) === False) return False;
						else return True;		
		    }
		}
		
		function reverse($string = ''){
		    $length = strlen($string);
				$result = '';
				for($i=0;$i<=$length;$i++){
				    $result .= substr($string,$length-$i,1);
				}
				return $result;
		}
		
}

?>