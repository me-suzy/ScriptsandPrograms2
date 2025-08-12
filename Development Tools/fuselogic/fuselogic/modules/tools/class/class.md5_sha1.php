<?php

if(!class_exists("md5_sha1")){
require_once('class.md5_.php');
class md5_sha1 extends md5_{
    function md5_sha1($secret = 'change me please'){	 
		    $this->hash_length = strlen($this->hash($secret));  
	      $this->setSecret($secret);				
	  }		
    function hash($string = ''){
		    return sha1($string);
		}		
}

}

?>