<?php

if(!class_exists("md5_md5")){

require_once('class.md5_.php');
class md5_md5 extends md5_{
    function md5_md5($secret = 'change me please'){	 
		    $this->hash_length = strlen($this->hash($secret)); 
	      $this->setSecret($secret);				
	  }		
    function hash($string = ''){
		    return md5($string);
		}		
}

}

?>