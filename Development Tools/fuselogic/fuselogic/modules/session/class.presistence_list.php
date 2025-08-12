<?php

class presistence_list{

   var $instance_name;
	 var $object_name;
	 var $object_file;

	 function presistence_list($instance_name,$object_name,$file_name=''){
	    $file_name = empty($file_name)?'class.'.$object_name.'.php':$file_name;			
	    $this->instance_name = $instance_name;
			$this->object_name = $object_name;
			$this->object_file = $this->find_file($file_name);			
	 }
	 
	 function find_file($file_name){	    
			$include_path = new include_path();
			foreach($include_path->to_array() as $value){
				    if($value{0} == '.'){
						   if(strlen($value) == 1){
							    $file = getcwd().'/'.$file_name;
							 }else{
							    $file = getcwd().'/'.str_replace('./','',$value).'/'.$file_name;							 
							 }						
						}else{
						   $file = $value.'/'.$file_name;	          
				    }
						$file = str_replace('\\','/',$file);
						if(file_exists($file)){						  			   
							 break;
						}
			 }
			 return $file;				
	 }
	 
}

?>
