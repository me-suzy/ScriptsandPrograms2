<?php

class setting{

    var $variables;
		static private $instance = false;
    //private function __construct(){}
    function __construct(){}
		
    static function singleton(){
        if(!setting::$instance){
            setting::$instance = new setting();					 
        }
        return setting::$instance;
    }
    		
    function set($name,$value){
	     $name = strtolower($name);
       $this->variables[$name] = $value;
    }

    function get($name){
	     $name = strtolower($name);
	     if(!isset($this->variables[$name])) return '';
			 else return $this->variables[$name];
    }
	 
	  function defined($name){
	     $name = strtolower($name);
	     if(isset($this->variables[$name])) return True;
			 else return False;
	  }
	 
}

?> 