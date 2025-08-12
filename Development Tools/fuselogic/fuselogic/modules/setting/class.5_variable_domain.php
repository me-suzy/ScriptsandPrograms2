<?php

class variable_domain{

    var $variables;
		var $domain;
		var $default_domain;
		static private $instance = false;
    //private function __construct(){}
    function __construct($domain_name = ''){
		    $this->variable = array();
				$this->default_domain = 'Default_Domain';
				$this->domain = $this->default_domain;
				$this->setDomain($domain_name);
		}
		function setDomain($domain_name = ''){
		    $this->domain = !empty($domain_name)?strtolower($domain_name):$this->default_domain;
		}
    static function singleton($domain_name = ''){
        if(!variable_domain::$instance){
            variable_domain::$instance = new variable_domain($domain_name);					 
        }
        return variable_domain::$instance;
    }
    		
    function set($name,$value){
	     $name = strtolower($name);
       $this->variables[$this->domain][$name] = $value;
    }

    function get($name){
	     $name = strtolower($name);
	     if(!isset($this->variables[$this->domain][$name])) return '';
			 else return $this->variables[$this->domain][$name];
    }
	 
	  function defined($name){
	     $name = strtolower($name);
	     if(isset($this->variables[$this->domain][$name])) return True;
			 else return False;
	  }
	 
}

?> 