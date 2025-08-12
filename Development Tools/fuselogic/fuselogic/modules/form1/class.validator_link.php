<?php

require_once('function.remote_file_exists.php');

class ValidateLink extends Validator{    
    var $link;
    var $name;
    function ValidateLink($link,$name){
        $this->link = $link;
				$this->name = $name;
        Validator::Validator();
    }

    function validate(){
		    $check = trim($this->link);
				if(!empty($check)){		    
            if(True !== remote_file_exists($this->link)){
				        $this->setError('Your '.$this->name.' Link is not Correct');
				    }		
				}
    }
}
?>