<?php

if(!class_exists("md5_")){
class md5_{
    var $secret_word;
	  var $value;
	  var $hash;
	  var $hash_length;
	
	  function md5_($secret = 'change me please'){	 
		    $this->hash_length = 40; 
	      $this->setSecret($secret);				
	  }
		
	  function setSecret($secret = 'change me please'){
		    $this->secret_word = $secret;	
	  }
	
	  function getSecret(){
	      return $this->secret_word;
	  }
	  		
	  function encode($value){
	      $this->value = $value;
	      $this->hash = $this->hash($this->secret_word.$this->value);  
		    return $this->value.$this->hash;	
	  }
						
		function decode($variable){
		    $result = $this->split($variable);				
        if($this->hash($this->secret_word.$result['data']) === $result['hash']){	
		        $this->value = $result['data'];	        
        }else{			
		        $this->value = NULL;      
        }
		    return $this->value;			
		
		}
				
		function split($variable){
		    $result = array();
		    $data_length = strlen($variable) - $this->hash_length;				
				$hash = substr($variable,-$this->hash_length);
				$data = substr($variable,0,$data_length);
				return array('data'=>$data,'hash'=>$hash);
		}
		
		function hash($string = ''){
		    //need to define by children class
		    //example: return sha1($string);
		}		
		
}

}

?>