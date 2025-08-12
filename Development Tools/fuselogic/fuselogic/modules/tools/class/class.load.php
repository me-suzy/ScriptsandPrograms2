<?php
if(!class_exists('LOAD')){
class LOAD
{    
    function Function($function = ''){
        if(is_array($function)){
            $res = array ();
            foreach ($function as $singlefunc){
                $res[] = LOAD::Function($singlefunc);
            }
						return $res;
        }else{
            if(!function_exists($function)){
						    if(@include_once './functions/function.'.$function.'.php'){
                    return true;
								}elseif(@include_once 'function.'.$function.'.php'){
                    return true;
								}elseif(@include_once dirname(dirname(__FILE__)).'/functions/function.'.$function.'.php'){
                    return true;
                }else return false;
            }else return true;
        }
    }
		
		function Class($class)
    {
        if(is_array($class)){
            $res = array ();
            foreach ($class as $singleClass){
                $res[] = LOAD::Class($singleClass);
            }
						return $res;
        }else{
            if(!class_exists($class)){
						    if(@include_once './class/class.'.$class.'.php'){
								    return True;
								}elseif(@include_once 'class.'.$class.'.php'){
								    return True;
								}						    
                elseif(@include_once dirname(__FILE__).'/class.'.$class.'.php'){
                    return true;
                }else return false;
            }else return true;
        }
    }		
		
}
}
?>