<?php
if(!function_exists('colom')){
function colom($string = '',$number_of_colom = 2,$word_each_colom = 20){
    $result = array();
    $number_of_colom = ($number_of_colom > 2)?$number_of_colom:2;
		$string = trim($string);
		$word_array = explode(' ',$string);
		$word_each_colom = ceil(count($word_array)/$number_of_colom)-1;		
		
		$pointer = 0;
		for($i=0;$i<$number_of_colom;$i++){
		    $result[$i] = '';
				for($j=$i;$j<$word_each_colom;$j++){
				    $result[$i] .= $word_array[$pointer].' ';					
						$pointer++;
				}		
		}
		return $result;
}
}
?>