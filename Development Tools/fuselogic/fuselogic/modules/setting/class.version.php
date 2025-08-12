<?php

class php_version{

    function php_version($version = ''){
		    if(empty($version)) $version = phpversion();
				$this->version = $version;
		}
    function less_than($version = ''){
		    if(!empty($version)){
		        if(strcasecmp($this->version,$version)<0) return True;
				else return False;		
				}else return False;
		}
    function equal($version = ''){
		    if(!empty($version)){
		        if(strcasecmp($this->version,$version) == 0) return True;
				else return False;		
				}else return False;
		}
		
}

?>