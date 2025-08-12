<?php

class ValidateFilter extends Validator{    
    var $string;
    var $location;
		var $badwords;
    function ValidateFilter($string = '',$message = '',$badwords = array()){
        $this->string = $string;
				$this->message = $message;
				if(count($badwords) == 0){
				    $badwords[] = 'sex';
						$badwords[] = 'penis';				    
				}
				$this->badwords = $badwords;
        Validator::Validator();
    }

    function validate(){
		    $check = trim($this->string);
				if(!empty($check)){		    
            if($this->isBadWords($this->string,$this->message,$this->badwords)){
				        $this->setError('Bad words in the '.$this->message);
				    }		
				}
    }
	
	  function isBadWords($a_value, $msg, $badwords = array()){
		    $result = False;
		    for($i=1;$i<=5;$i++){
				    $function = 'isBadWords'.$i;
						if(method_exists($this,$function)){
						    if($this->$function($a_value,$msg,$badwords)){
						        $result = True;
								    break;
						    }				
						}
				}
				return $result;
		}
		
	  function isBadWords1($a_value, $msg, $badwords = array()){
	      $isBadWords = False;        
		    foreach($badwords as $badword){
		        if(eregi($badword,$a_value)){
						    $this->found_badword = $badwords;		            
				        $isBadWords = True;				        
				        break;
		        }				
		    };
				return $isBadWords;		    
		} 
			
		function isBadWords2($a_value, $msg, $badwords = array()){	
		    $isBadWords = False;
		
		    // use your imagination :)
		    $separator = array('-',',','.','_','/','\\','|','(',')','~','{','}');
		    $separator = array_merge($separator,array('*','^','<','>','#','@'));
				   
		    foreach($separator as $replace){
		        $a_value = str_replace($replace,' ',$a_value);
		    }		
				
		    foreach($badwords as $badword){
		        if(eregi($badword,$a_value)){		            
		            $isBadWords = True;
			          break;
		        }				
		    };
		    return $isBadWords;			    
		} 
		
		function isBadWords3($a_value, $msg, $badwords = array()){	
		    $isBadWords = False;
		
		    // use your imagination :)
		    $separator = array('-',',','.','_','/','\\','|','(',')','~','{','}');
		    $separator = array_merge($separator,array('*','^','<','>','#','@'));
				    
		    foreach($separator as $replace){
		        $a_value = str_replace($replace,'',$a_value);
		    }		
				
		    foreach($badwords as $badword){
		        if(eregi($badword,$a_value)){		            
		            $isBadWords = True;
			          break;
		        }				
		    };
		    return $isBadWords;			    
		} 
		
		function isBadWords4($a_value, $msg, $badwords = array()){	
		    $isBadWords = False;
		
		    // use your imagination :)
		    $separator = array('-',',','.','_','/','\\','|','(',')','~','{','}');
		    $separator = array_merge($separator,array('*','^','<','>','#','@'));
		
		    $separator[0] = ' ';
		    foreach($separator as $replace){
		        $a_value = str_replace($replace,'',$a_value);
		    }		
				
		    foreach($badwords as $badword){
		        if(eregi($badword,$a_value)){		            
		            $isBadWords = True;
			          break;
		        }				
		    };
		    return $isBadWords;			    
		} 
		
	  function isBadWords5($l_value, $msg, $badwords = array()){	
		    $isBadWords = False;
		
		    // use your imagination :)
		    $separator = array('-',',','.','_','/','\\','|','(',')','~','{','}');
		    $separator = array_merge($separator,array('*','^','<','>','#','@'));
		
		    $l_value = str_replace('kosong','0',$l_value);
				$l_value = str_replace('nol','0',$l_value);
								
				$l_value = str_replace('satu','1',$l_value);
				$l_value = str_replace('dua','2',$l_value);
				$l_value = str_replace('tiga','3',$l_value);
				$l_value = str_replace('empat','4',$l_value);
				$l_value = str_replace('lima','5',$l_value);
				$l_value = str_replace('enam','6',$l_value);
				$l_value = str_replace('tujuh','7',$l_value);
				$l_value = str_replace('delapan','8',$l_value);
				$l_value = str_replace('sembilan','9',$l_value);
							
				$separator[0] = ' ';
		    foreach($separator as $replace){
		        $l_value = str_replace($replace,'',$l_value);
		    }		
				
		    foreach($badwords as $badword){
		        if(eregi($badword,$l_value)){		            
		            $isBadWords = True;
			          break;
		        }				
		    };
		    return $isBadWords;			    
		} 
		
	
	
}
?>