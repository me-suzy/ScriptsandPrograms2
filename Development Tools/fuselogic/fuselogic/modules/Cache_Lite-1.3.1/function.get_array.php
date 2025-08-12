<?php

function get_array($file = '',$array_name = ''){
    if(!empty($file) and !empty($array_name)){
		    if(file_exists($file)){
		        $$array_name = array();
		        require($file);    
            if(isset($$array_name)){
						    return $$array_name;
		        }
		    }		
		}
}

?>