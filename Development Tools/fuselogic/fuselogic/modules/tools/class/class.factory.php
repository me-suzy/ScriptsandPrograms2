<?php

class factory{
    
    function loadClass($class)
		{
        if(is_array($class)){

            $res = array ();
            foreach($class as $singleClass){
                $res[] = PHP_Compat::loadFunction($singleClass);
            }
            return $res;

        }else{

            if(!class_exists($class)){
                $file = 'class.'.$class.'.php';
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

?>