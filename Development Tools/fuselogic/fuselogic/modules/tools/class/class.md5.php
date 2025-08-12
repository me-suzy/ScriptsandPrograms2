<?php

if(!class_exists("md5")){

class md5{
    
		function & factory($secret = 'change me please',$type = 'md5'){
		    
		    switch($type){
				    case 'sha1':
						    md5::loadClass('md5_sha1');
								return new md5_sha1($secret);					
						    break;
						default:
						    md5::loadClass('md5_md5');
								return new md5_md5($secret);						
						    break;		
				
				}
				/*
				$md5_class_name = 'md5_'.$type;
				md5::loadClass($md5_class_name);
				return new $md5_class_name($secret);				
		    */
		}
		
		function loadClass($class)
		{
        if(is_array($class)){

            $res = array ();
            foreach($class as $singleClass){
                $res[] = md5::loadClass($singleClass);
            }
            return $res;

        }else{

            if(!class_exists($class)){
                $file = 'class.'.$class.'.php';
								$md5_class_name = $class;
                if((@include_once $file) !== false){
								    if(class_exists($class)){										    
                        return true;
										}
                }
            }
            return false;
        }
    }
   
		
}

}

?>