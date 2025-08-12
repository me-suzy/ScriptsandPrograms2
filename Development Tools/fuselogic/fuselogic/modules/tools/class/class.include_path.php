<?php

if(!class_exists('include_path')){

    class include_path{
		
		    var $setting_name;
    
		    function include_path(){
				    $this->setting_name = 'include_path';				
						$this->get_separator();
				}
								
				function _link($directory = ''){
				    if(!empty($directory)){
						        $directory = str_replace('\\','/',$directory);																		
				            $setting = $this->get_setting();
										$setting = str_replace($this->_separator.$directory,'',$setting);
										$setting .= $this->_separator.$directory;												
						        ini_set($this->setting_name,$setting);								
						}		
				}
				
				function link($variables){
				    if(is_array($variables)){
						    foreach($variables as $variable){
								    $this->_link($variable);
								}
						}else{
						    $this->_link($variables);
						}
				
				}
				function add($variables){
				    if(is_array($variables)){
						    foreach($variables as $variable){
								    $this->_link($variable);
								}
						}else{
						    $this->_link($variables);
						}
				
				}
				
				function _unlink($directory = ''){
				    if(!empty($directory)){
						    $directory = str_replace('\\','/',$directory);								
				        $setting = $this->get_setting();										
																
							  $setting = str_replace($directory,'',$setting);		
								$setting = str_replace($this->_separator.$this->_separator,$this->_separator,$setting);														
							  $setting .= $this->_separator.$this->_separator;
							  $setting = str_replace($this->_separator.$this->_separator.$this->_separator,'',$setting);
							  $setting = str_replace($this->_separator.$this->_separator,'',$setting);							
								
								ini_set($this->setting_name,$setting);							
						}				
				}
				
				function unlink($variables){
				    if(is_array($variables)){
						    foreach($variables as $variable){
								    $this->_unlink($variable);
								}
						}else{
						    $this->_unlink($variables);
						}
				
				}
				function remove($variables){
				    if(is_array($variables)){
						    foreach($variables as $variable){
								    $this->_unlink($variable);
								}
						}else{
						    $this->_unlink($variables);
						}
				
				}
				
				function get_setting($setting = ''){
				    if(empty($setting)) $setting = ini_get($this->setting_name);
						$setting = str_replace('\\','/',$setting);
						return $setting;
				}
				
				function get_separator(){
				    $count = substr_count(ini_get($this->setting_name),';');
						if($count > 0){
						    $this->_separator = ';';
						}else{
						    $this->_separator = ':';
						}
				}
    
		    function clear(){
				    ini_set($this->setting_name,'.');									
				}
				
				function to_array(){
				    return explode($this->_separator,ini_get($this->setting_name));
				}
				
    }
 
}

?>
