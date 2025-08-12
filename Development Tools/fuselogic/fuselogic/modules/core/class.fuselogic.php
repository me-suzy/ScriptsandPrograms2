<?php

if(!defined('FL_FUSELOGIC_CLASS')){
    define('FL_FUSELOGIC_CLASS',1);		
		
class FuseLogic{
        var $LayoutName;	
	var $debug;	
	var $modules = array();
	var $errormessage;	
	var $ParentFuseaction;	
	var $fuse;
	var $env;
	
        // Others --------------------------------------------
	function FuseLogic($env = null){
             $this->env = $env;	     	 
	}	
	function initFuse(&$q){		
	    $this->module = $this->getModule($q->fuse);		
	    $this->fuseaction = $q->fuse;	
	    $this->subModule = $this->getSubModule($q->fuse);			
	    $this->LayoutName = $q->layoutName;				
	    $this->LINE = $q->LINE;				
	    $this->FILE = $q->FILE;		
	    $this->ParentFuseaction = isset($q->parentFuse)?$q->parentFuse:null;					
	}	
	function isModuleExists($module = ''){
	   if(empty($module)) $module = $this->module;
	   return isset($this->modules[$module])?True:False;
	}  
	function debug($flag = ''){
	   if(!empty($flag)) $this->debug = $flag;
	   return $this->debug;
	}
	function fuse($name = ''){
	   if($name != ''){
		$temp = explode('/',$name);
		if(isset($this->fuse[$temp[0]][$temp[1]])) return (string)$this->env->root_path.'/'.$this->fuse[$temp[0]][$temp[1]];		    
	   }	
	}
	
	// SET --------------------------------------------	
	function setErrorMessage($a_errorMessage= ''){
	   $this->errormessage = $a_errorMessage;
	}
	function setModule($module,$location=''){
	    $this->modules[$module] = $location;
	}
	function isHomeCircuit(){
	    $currentFuseaction = $this->module.'/'.$this->subModule;
	    $temp1 = str_replace('.','/',$this->env->user_fuse);
	    $temp2 = explode('/',$temp1);
	    $userFuseaction = @$temp2[0].'/'.@$temp2[1];
	    return ($currentFuseaction == $userFuseaction)?True:False;		 
	}
		
	// GET --------------------------------------------	
	function getWebPath($module_name=''){
	    $cwd = ($module_name == '')?getcwd():$this->getModulePath($module_name);
	    $cwd = str_replace('\\','/',$cwd);			
	    return str_replace($this->env->document_root,'',$cwd);			
	}
	function getModulePath($module = ''){
	    if(empty($module)){	        
		return str_replace('\\','/',$this->env->root_path.'/'.$this->modules[$this->module]);					
	   }else{
	        if(isset($this->modules[$module])){
	           return str_replace('\\','/',$this->env->root_path.'/'.$this->modules[$module]);					   
		}
	   }
	}		
	function getUserModule(){
	    $this->userModule = empty($this->userModule)?$this->getModule($this->env->user_fuse):$this->userModule;
	    return $this->userModule;		
	}
	
	function getUserSubModule(){
	    if(empty($this->userSubModule)){
		$this->userSubModule = $this->getSubModule($this->env->user_fuse);
	   }
	   return $this->userSubModule;		
	}
	function getModule($fuseaction = ''){	  
	   $fuseaction = str_replace('.','/',$fuseaction);
	   if(!empty($fuseaction)){		
	      $temp = array();
	      $temp = explode('/',$fuseaction);
   	       return $temp[0];
	   }else{
	       return @$this->module;
	   }
	}	
	function getSubModule($fuseaction = ''){
	    $fuseaction = str_replace('.','/',$fuseaction);
	    if(!empty($fuseaction)){		
		$temp = array();		
		$fuseaction = str_replace('?','/',$fuseaction);
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

?>