<?php

class variable_domain{

    var $variables;
		var $domain;
		var $default_domain;
		
		function variable_domain($domain_name = '',$singleton = ''){		  
		   if($singleton === 'singleton'){
			     global $VARIABLE_DOMAIN_SINGLETON_VARIABLE;
		       $this->variables = &$VARIABLE_DOMAIN_SINGLETON_VARIABLE;
					 $this->default_domain = 'Default_Domain';
				   $this->domain = $this->default_domain;
				   $this->setDomain($domain_name);
			 } 			 
		}
		
    function singleton($domain_name = ''){		    
				return new variable_domain($domain_name,'singleton');
		}
		function setDomain($domain_name = ''){
		    $this->domain = !empty($domain_name)?strtolower($domain_name):$this->default_domain;
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