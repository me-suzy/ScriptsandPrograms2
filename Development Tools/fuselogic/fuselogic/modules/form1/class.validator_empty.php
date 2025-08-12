<?php

class ValidateEmpty extends Validator{    
    var $string;
    var $name;
    function ValidateEmpty($string,$name){
        $this->string = $string;
				$this->name = $name;
        Validator::Validator();
    }

    function validate(){		   
		    $check = trim($this->string); 
        if(empty($check)){
				    $this->setError('Your '.$this->name.' is empty');
				}		
    }
}
?>