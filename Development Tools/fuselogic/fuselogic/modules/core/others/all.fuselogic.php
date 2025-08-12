<?php

/*
+-------------------------------------------------------------+
|   PHP version 4                                             |
+-------------------------------------------------------------+
|   Version : 0.0.27                                         |
+-------------------------------------------------------------+
|   Copyright (c) 2002 - 2003 Eko Budi Setiyo                 |
+-------------------------------------------------------------+ 
| License : BSD License                                       |
| http:www.haltebis.com/index/wakka/main/license                    |
+-------------------------------------------------------------|
| Authors : Setiyo, Eko Budi <ekobudi@haltebis.com>           |
+-------------------------------------------------------------+
*/
if(!defined('FL_FUSELOGIC_CLASS')){  

    define('FL_FUSELOGIC_CLASS',1);		
		
class FuseLogic{

  var $CircuitPath;
	var $LayoutName;
	var $WebPath;
	var $userFuseaction;
	var $defaultFuseaction;
	var $debug;
	var $FuseLogicRootDirectory;
	var $DocumentRoot;
	var $circuit;
	var $fuseaction;
	var $isHomeCircuit;
	var $circuits = array();
	var $errormessage;
	var $urlRoot;
	var $ParentFuseaction;	
	var $attributes = array();
	var $URL = array();
	
  // Others --------------------------------------------
	
	function FuseLogic($setting = array()){	
	    
		 global $attributes;
		 
	   $this->debug = false;
		 $this->circuits = array();		
		 $this->attributes = array();		 
		 $this->attributes = &$attributes;
		 $this->userFuseaction = '';		 
		 $this->UserCircuit = '';
		 $this->userModule = '';
		 $this->UserFuse = '';
		 $this->userSubModule = '';		 		 
		 $this->userCommand = '';		 		 
		 
		 //the sequence is "IMPORTANT!"
		 $this->_setFuseLogicRoot($setting['fl_root']);
		 $this->_setDocumentRoot($setting['document_root']);				  
		 $this->_setDoor($setting['door'],$this->getDocumentRoot());		 
		 $this->_setIndex($setting['index_name']);				 
		 $this->_setUserCommand($setting['user_command'],$this->getDoor());		 
		 $this->_setURL();		 
		 $this->_setAttributes();		 
		 $this->setDefaultFuseaction('init/home');					 		 
	}
	
	function _setUserCommand($userCommand = '',$door = ''){
	    if(!empty($userCommand)){
			    if(!empty($door)){
			        $userCommand = str_replace($door,'',$userCommand);
					}			    
					$check = substr($userCommand,0,1);    		
				  if($check === '/'){
				      $userCommand = substr($userCommand,1,strlen($userCommand));
				  }
					$temp = explode('/',$userCommand);					
					if($temp[0] == $this->index_name or $temp[0] == 'index.php') array_shift($temp);
					$userCommand = implode('/',$temp);
			    $this->userCommand = $userCommand;					
			}
	}
	
	function setUserSubModule($subModule = ''){
	    if(!empty($subModule)){
			    $this->userSubModule = $subModule;
					return True;
			}
	}
	function setUserModule($Module = ''){
	    if(!empty($Module)){
			    $this->userModule = $Module;
					return True;
			}else return False;
	}
	function _setFuseLogicRoot($fuselogic_root_directory = ''){
	    if(!empty($fuselogic_root_directory)){
		     //kill the trailing slash				 
				 $check = substr($fuselogic_root_directory,-1);    		
				 if($check === '/' or $check === '\\'){
				     $this->FuseLogicRootDirectory = substr($fuselogic_root_directory,0,strlen($fuselogic_root_directory)-2);
				 }else $this->FuseLogicRootDirectory = $fuselogic_root_directory;
		  }
	}
	
	function initFuseaction($input = array('fuseaction'=>'','layouName'=>'noname','ParentFuseaction'=>'')){
		$qlvariable = array();		
		$qlvariable = explode('/',$input['fuseaction']);
		array_shift($qlvariable);
		array_shift($qlvariable);
		/*
		$_GET['qlogic'] = array();
		$_GET['qlogic'] = $qlvariable;
		*/				
		$this->module = $this->getCircuit($input['fuseaction']);		
		$this->circuit = $this->module; 
		$this->fuseaction = $input['fuseaction'];	
		$this->subModule = $this->getSubModule($input['fuseaction']);	
		
		$this->LayoutName = $input['layoutName'];				
		if(isset($input['ParentFuseaction'])){
		    $this->ParentFuseaction = $input['ParentFuseaction'];
		}	
		if($this->isCircuitExists($this->module)){
		    $this->_setHomeCircuit();	
		    $this->_setCircuitPath();
		    $this->_setWebPath();	
		}
	}
	
	function isCircuitExists($circuit = ''){
	   //check for existing circuit
		 if(empty($circuit)) $circuit = $this->module;
	   if(isset($this->circuits[$circuit])){
		    return True;
		 }else return False;
	}
  function isHomeCircuit(){
	   return $this->isHomeCircuit;
	}	
	function debug($flag = ''){
	   if(!empty($flag)) $this->debug = $flag;
	   return $this->debug;
	}
	// SET --------------------------------------------	
	function setErrorMessage($a_errorMessage= ''){
	   $this->errormessage = $a_errorMessage;
	}
	function setUserFuseaction($fuseaction = ''){	
	    $this->userFuseaction = $fuseaction;
	}	
	function _setAttributes(){
	    $this->attributes = array_merge($_POST,$_GET); // GET overwrites POST	
	}					
	function _setIndex($index_name = 'index'){	  		  
			$tempString = $_SERVER['PHP_SELF'];
			$tempString = str_replace('\\','/',$tempString);
			$tempString = dirname($tempString);
			$tempString = str_replace('\\','/',$tempString).'/';
			$tempString = str_replace('//','/',$tempString);			
			$this->uri = $tempString;			   
	    	    
			$this->index_name = $index_name;
	    $this->uriIndex = $this->uri.$this->index_name.'/';	    
	}		
	function _setURL(){			
			$Uri = explode('/',$this->getUserCommand());	 		 
	    if(count($Uri)>0){	   
			    $this->setUserFuseaction($this->getUserCommand());
      }	   	 	  			
	    array_Shift($Uri);
	    array_Shift($Uri);
		
			$this->URL = array();
			$this->URL = $Uri;						
	}		
	function getURL(){
	    return $this->URL;
	}
	function setCircuit($circuit,$location){
		 $this->circuits[$circuit] = $location;
	}
	function setModule($circuit,$location=''){
		 $this->circuits[$circuit] = $location;
	}	
	function setDefaultFuseaction($fuseaction = ''){
	   $this->defaultFuseaction = $fuseaction;		
	}		
  function _setHomeCircuit(){
	   //set weather home circuit or not
		 $this->isHomeCircuit = False;		 
		 $currentFuseaction = $this->module.'/'.$this->subModule;
		 $temp1 = str_replace('.','/',$this->getUserFuseaction());
		 $temp2 = explode('/',$temp1);
		 $userFuseaction = @$temp2[0].'/'.@$temp2[1];
		 if($currentFuseaction == $userFuseaction) $this->isHomeCircuit = True;
		 return $this->isHomeCircuit;
	}
  function _setCircuitPath(){
	  //set the circuit path (real path of circuit)
		$this->CircuitPath = $this->FuseLogicRootDirectory.'/'.$this->circuits[$this->circuit];
		$this->CircuitPath = str_replace('\\','/',$this->CircuitPath);
  }  
	function _setDocumentRoot($documentRoot = ''){
	    if(!empty($documentRoot)){
			    $this->DocumentRoot = $documentRoot;
			}else $this->DocumentRoot = $_SERVER['DOCUMENT_ROOT'];
			
	    $lastCharacter = substr($this->DocumentRoot,-1);    
		  if($lastCharacter == '/'){
		      $this->DocumentRoot = substr($this->DocumentRoot, 0, -1); 
		  }	 
	}	
  function _setWebPath(){
	  //set the currentPaht (relative URL path from Fuselogic URL ROOT);
		$this->WebPath = str_replace($this->DocumentRoot,'',$this->CircuitPath);
	}  
	function _setDoor($door,$document_root){
	    $door = str_replace('\\','/',$door);
      $this->door = str_replace($document_root,'',$door);				      
	}
	
	// GET --------------------------------------------
	function getDocumentRoot(){
	    return $this->DocumentRoot;
	}
	function getDoor(){
	    return $this->door;
	}
	function getUserCommand(){
	    return $this->userCommand;
	}	
	function getLayoutName(){
	   return $this->LayoutName;
	}
	function getWebPath(){
	    return $this->WebPath;;
	}			
	function getCircuitPath(){
	    return $this->CircuitPath;
	}
	function getModulePath($module = ''){
	    if(empty($module)){
	        return $this->CircuitPath;
			}else{
			    if(isset($this->circuits[$module])){
			        $result = $this->FuseLogicRootDirectory.'/'.$this->circuits[$module];
		          $result = str_replace('\\','/',$result);
					    return $result;
					}
			}
	}
	function getCircuitName(){
	    return $this->circuit;
	}	
	function module(){
	    return $this->module;
	}
	function getErrorMessage(){
	    return $this->errormessage;
	}	
	function getUserFuseaction(){
	    if(empty($this->userFuseaction)){
			    return $this->getDefaultFuseaction();					
			}else{			    
	        return $this->userFuseaction;
			}		
	}
	function getDefaultFuseaction(){	    
	    return $this->defaultFuseaction;
	}	
	function getUserCircuit(){
	    if(empty($this->UserCircuit)){
			    $this->UserCircuit = $this->getCircuit($this->getUserFuseaction());
			}
			return $this->UserCircuit;		
	}
	function getUserModule(){
	    if(empty($this->userModule)){
			    $this->userModule = $this->getModule($this->getUserFuseaction());
			}
			return $this->userModule;		
	}
	function getUserFuse(){
	    if(empty($this->UserFuse)){
			    $this->UserFuse = $this->getSubModule($this->getUserFuseaction());
			}
			return $this->UserFuse;		
	}		 									
	function getUserSubModule(){
	    if(empty($this->userSubModule)){
			    $this->userSubModule = $this->getSubModule($this->getUserFuseaction());
			}
			return $this->userSubModule;		
	}		
	function getUriRoot(){
	    return $this->uri; 
	}	
	function getUriRootIndex(){
	    return $this->uriIndex;
	}  
	function getCircuit($fuseaction = ''){
	  $fuseaction = str_replace('.','/',$fuseaction);
		$temp = array();
		$temp = explode('/',$fuseaction);
   	return $temp[0];
	}
	function getModule($fuseaction = ''){
	  $temp = array();
		$temp = explode('/',$fuseaction);
   	return $temp[0];
	}				
	function getFuseaction(){	   
		 return $this->fuseaction;	 			
	}
			
	function getSubModule($fuseaction = ''){
	   $fuseaction = str_replace('.','/',$fuseaction);
	   if(!empty($fuseaction)){		
		    $temp = array();		
		    $temp = explode('/',$fuseaction);
		    array_shift($temp);		
		    return @$temp[0];
		 }else{
		    return $this->subModule;
		 }				
	}
	 	  
 	
} 

//end of FuseLogic class; 

} 

//-------------------------------------------------------------------------------
if(!defined("FL_LAYOUT_CLASS")){

    define("FL_LAYOUT_CLASS",1);

    class FLLayout{
        var $Layout = array();	 
	 
	      function FLLayout(){
	          $this->setLayout('noname','');
	      }
	 
	      function setLayout($layoutName='noname',$Layout = ''){
	          $this->Layout[$layoutName] = $Layout;
	      }
	 
	      function getLayout($layoutName = 'noname'){
	          if(isset($this->Layout[$layoutName])){
			          return $this->Layout[$layoutName];
			      }else return '';	 
	      }
		
	      function ob_start(){
			      ob_start(); //start the output buffer
	      }
	 
	      function ob_end(){				    
			      $buf = ob_get_contents(); //get stuff out of buffer to variable
			      @ob_end_clean();          //clear buffer, end collection of content
			      return $buf;              // return the contents of the buffer												 
	      }	 	
    }
//end of class Layout
}

//-------------------------------------------------------------------------------

if(!defined('FL_QUEUE_CLASS')){
    define('FL_QUEU_CLASS',1);

class FLQueue{

    var $pointer;
		var $open;
		var $fuseactionQ;
		var $layoutQ;
		var $single;
		var $ParentFuseaction;
		var $ActiveFuseaction;
		var $logs;
		
		function FLQueue(){
		   $this->ActiveFuseaction = '';
			 $this->ParentFuseaction = '';
			 $this->ParentFuseactionQ = array();
			 $this->fuseactionQ = array();
			 $this->single = array();
			 $this->logs = array(); 
		   $this->pointer = 0;		
			 $this->open = True;
			 $this->time0 = 0;	
			 $this->time1 = 0;
			 $this->logCounter = -1;		 
		}
	
		function Queue($fuseaction ='',$layoutName = 'noname',$ParentFuseaction = ''){
		   if($this->open){				 
				   $this->ForceQueue($fuseaction,$layoutName,$ParentFuseaction);					 	 
			 }
		}
		
		function ForceQueue($fuseaction = '',$layoutName = 'noname',$ParentFuseaction = ''){
		   if($this->_is_singleton($fuseaction) != 1){
		         $this->fuseactionQ[$this->pointer] = $fuseaction;
			       $this->layoutQ[$this->pointer] = $layoutName;	
						 if(empty($ParentFuseaction)){
						    $ParentFuseaction = $this->ActiveFuseaction;
						 }
						 $this->ParentFuseactionQ[$this->pointer] = $ParentFuseaction;							 			
			       $this->pointer++;						 
			 }			 
		}
		
		function clear(){
		   $this->pointer = 0;
		}
		
		function close(){
		   $this->open = false;
		}
		
		function open(){
		   $this->open = True;			 
		}
								
		function FirstQueue($fuseaction='',$layoutName = 'noname',$ParentFuseaction = ''){
		   if($this->open){			 
					$this->ForceFirstQueue($fuseaction,$layoutName,$ParentFuseaction);
			 }
		}
		
		function ForceFirstQueue($fuseaction='',$layoutName = 'noname',$ParentFuseaction = ''){
		   if($this->_is_singleton($fuseaction) != 1){
		         array_unshift($this->fuseactionQ, $fuseaction);
		         array_unshift($this->layoutQ, $layoutName);				
						 if(empty($ParentFuseaction)){
						    $ParentFuseaction = $this->ActiveFuseaction;
						 }		
						 array_unshift($this->ParentFuseactionQ, $ParentFuseaction);					 				 
			       $this->pointer++;
			 }
		}
		
		function _delete($pointer = 0){
		   for($i = $pointer; $i < $this->pointer - 1 ;$i++){
			    $this->fuseactionQ[$i] = $this->fuseactionQ[$i+1];
					$this->layoutQ[$i] = $this->layoutQ[$i+1];
					if(isset($this->ParentFuseactionQ[$i+1])){
					    $this->ParentFuseactionQ[$i] = $this->ParentFuseactionQ[$i+1];
					}
			 }
		   $this->pointer--;
			 if($this->pointer < 0 ) $this->pointer = 0;
		}
				
		function _get($pointer = 0){
		   $result = array();
			 $result['fuseaction']       = '';
			 $result['layoutName']       = '';
			 $result['ParentFuseaction'] = '';
			 if($this->pointer > 0){
			     $result['fuseaction']       = $this->fuseactionQ[$pointer];
			     $result['layoutName']       = $this->layoutQ[$pointer];
			     $result['ParentFuseaction'] = $this->ParentFuseactionQ[$pointer];			 
			 }
			 return $result;	
		}
		
		function service($pointer = 0){
		   $temp = array();
		   $temp = $this->_get($pointer);		  
			 $this->ActiveFuseaction = $temp['fuseaction'];
			 $this->ParentFuseaction = $temp['ParentFuseaction'];			 				 
		   $this->_delete($pointer);			 
			 return $temp;
		}
		function getMicrotime(){
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }
		function log_start(){
		    $this->time0 = $this->getMicrotime();			
				$this->logCounter++;	
		}
		function log_end(){
		    if($this->logCounter>=0){
		        $dt = $this->getMicrotime() - $this->time0;			 			 
			      $this->executedTime = (float)number_format($dt,6);			
				
			      $this->logs['module_name'][$this->logCounter] = $this->ActiveFuseaction;			 
			 	    $this->logs['module_time'][$this->logCounter] = $this->executedTime;			
				    $this->total_time = $this->total_time + $this->executedTime;	
				}	
		} 
		function count(){
		   return $this->pointer;		
		}

		function singleton($fuseaction = ''){
		   if(empty($fuseaction)){
			    $fuseaction = $this->ActiveFuseaction;
			 }
			 $fuseaction= str_replace('.','/',$fuseaction);
		   $tempArray = explode('/',$fuseaction);
		   $fuseaction = $tempArray[0].'.'.$tempArray[1];
			 
			 $this->single[$fuseaction] = 1;			  
		}
		
		function _is_singleton($fuseaction = ''){
		   $fuseaction= str_replace('.','/',$fuseaction);
		   $tempArray = explode('/',$fuseaction);
		   $fuseaction = $tempArray[0].'.'.$tempArray[1];
		   if(isset($this->single[$fuseaction])){
		      if($this->single[$fuseaction] == 1){
			       return 1;
			    }else{
			       return 0;
			    }
			 }else return 0;							 
		}
		
		function getLogs(){
		    $this->log_end();
		    //return $this->logs;				
				$result = array();
				$count = count($this->logs['module_time']);				
				for($i=0;$i<=$this->logCounter;$i++){
				    $percent = number_format($this->logs['module_time'][$i]*100.0/$this->total_time,2).'%';	
						$result[$i] = (string)($percent).':::'.number_format($this->logs['module_time'][$i],6).'sec:::'.$this->logs['module_name'][$i];			
				}				
				return $result;
		}
		
}
// end of class Queue

}

?>
