<?php

class setting{

    var $variables;
		
		function setting($singleton = ''){		  
		   if(!empty($singleton)){
			     global $SETTING_SINGLTONG_VARIABLE;
		       $this->variables = &$SETTING_SINGLTONG_VARIABLE;
			 } 			 
		}
		
    function singleton(){		    
				return new setting('singleton');
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