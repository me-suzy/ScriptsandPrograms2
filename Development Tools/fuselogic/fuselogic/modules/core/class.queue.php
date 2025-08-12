<?php
/*
+-------------------------------------------------------------+
|   PHP version 4                                             |
+-------------------------------------------------------------+
|   Version : 0.1.15                                          |
+-------------------------------------------------------------+
|   Copyright (c) 2002 - 2004 Eko Budi Setiyo                 |
+-------------------------------------------------------------+ 
| License : BSD License                                       |
| http:www.haltebis.com/index/wakka/main/license                    |
+-------------------------------------------------------------|
| Authors : Setiyo, Eko Budi <ekobudi@haltebis.com>           |
+-------------------------------------------------------------+
*/
if(!defined('FL_QUEUE_CLASS')){
    define('FL_QUEU_CLASS',1);
require_once('class.q.php');				
class FLQueue{
    var $q;
    var $pointer;
		var $open;
		var $single;
		var $ParentFuseaction;
		var $ActiveFuseaction;
		var $logs;
		var $max_number_of_queue;
		var $queue_counter;			
		var $activeQueue;
			
		function FLQueue($setting = array()){		  
		   $this->q = array(); 
		   $this->ActiveFuseaction = '';
			 $this->ParentFuseaction = '';
			 $this->single = array();
			 $this->logs = array(); 
		   $this->pointer = 0;		
			 $this->open = True;
			 $this->time0 = 0;	
			 $this->time1 = 0;
			 $this->logCounter = -1;		 
			 $this->queue_counter = 0;
			 $this->max_number_of_queue = (@$setting['max_number_of_queue']> 3)?$setting['max_number_of_queue']:3;
		}		
	  function next(){
		    $this->queue_counter++; 
		    if($this->queue_counter < $this->max_number_of_queue and $this->pointer > 0){
		        return $this->service();
				}else{
				    return false;
				}				
		}	
		function Queue($fuse ='',$layoutName = 'noname'){
		   if($this->open){				 
				   $this->ForceQueue($fuse,$layoutName);					 	 
			 }
		}	
		function ForceQueue($fuse = '',$layoutName = 'noname'){
		    if($this->_is_singleton($fuse) != 1){		    
						$this->q[$this->pointer] = &new Q($fuse,$layoutName,$this->activeQueue->fuse);				 			
			      $this->pointer++;						 
			  }			 
		}		
		function clear(){
		   $this->pointer = 0;
			 $this->q = array();			 
		}		
		function close(){
		   $this->open = false;
		}
								
		function FirstQueue($fuse='',$layoutName = 'noname'){
		   if($this->open){			 
					$this->ForceFirstQueue($fuse,$layoutName);
			 }
		}	
		function ForceFirstQueue($fuse='',$layoutName = 'noname'){
		    if($this->_is_singleton($fuse) != 1){			    
						array_unshift($this->q,new Q($fuse,$layoutName,$this->activeQueue->fuse));							 			 				 
			      $this->pointer++;
			  }
		}	
		function _delete(){		   
		   for($i=0;$i<$this->pointer - 1 ;$i++){
			    $this->q[$i] = $this->q[$i+1];					
			 }
		   $this->pointer--;			 
			 $this->pointer = ($this->pointer<0)?0:$this->pointer;
			 unset($this->q[$this->pointer]);
		}	
		function service(){	
		    if($this->pointer>0){  		        
						$this->activeQueue = $this->q[0];	
			      $this->ActiveFuseaction = $this->activeQueue->fuse;						
			      $this->_delete();		      
						return true;
			  }else{
				    return false;
				}
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
				    $this->total_time = @$this->total_time + $this->executedTime;		
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
		   @$fuseaction = $tempArray[0].'.'.$tempArray[1];
			 
			 $this->single[$fuseaction] = 1;			  
		}		
		function _is_singleton($fuseaction = ''){
		   $fuseaction= str_replace('.','/',$fuseaction);
		   $tempArray = explode('/',$fuseaction);
		   @$fuseaction = $tempArray[0].'.'.$tempArray[1];
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