<?php
if(!class_exists('value_object')){
class value_object{

    function value_object($array = array()){
		    foreach($array as $key=>$value){
              $this->$key = $value;
        }
		}

}		
}
?>
