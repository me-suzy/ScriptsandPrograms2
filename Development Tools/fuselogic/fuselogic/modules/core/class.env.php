<?php

if(!defined('FUSELOGIC_ENV')){
    define('FUSELOGIC_ENV',1);		
		
class env{
   var $root_path;
   var $document_root;
   var $index_name;
   var $door_path;
   var $core_path;
   var $user_fuse;	
  
   function env(){
        $this->index_name = 'index.php';   
	$this->run();
   }
   
   function run(){
        $this->_compatible();
        $this->_core_path();
	$this->_root_path();
	$this->_door_path();
	$this->_index($_SERVER['REQUEST_URI']);
	$this->_document_root();
	$this->_user_fuse($_SERVER['REQUEST_URI']);
	$this->_uri($_SERVER['REQUEST_URI']);
   }
   function _document_root(){
      $this->document_root = $_SERVER['DOCUMENT_ROOT'];
   }
   function _core_path(){
      $this->core_path = dirname(__FILE__);
      $this->core_path = str_replace('\\','/',$this->core_path);
   }
   function _root_path(){	
	$fuselogic_root = dirname(__FILE__);
        for($i=0;$i<4;$i++){
              $fuselogic_root = dirname($fuselogic_root);
              if(file_exists($fuselogic_root.'/fuselogic_root.php')){							      
	          break;
	     }
        }
      $fuselogic_root = str_replace('\\','/',$fuselogic_root);	
      $this->root_path = $fuselogic_root;	
   }	
   function _door_path(){
      $this->door_path = str_replace('\\','/',getcwd());
   }
   function _index($input = ''){           
      $temp = explode($this->index_name,$input);	   	    
      $this->index = $temp[0].$this->index_name.'/';
      return $this->index;		
   }	
   function _user_fuse($input){
      $this->user_fuse = 'init/home';
      $temp = explode($this->index_name,$input);
      if(count($temp) >= 2){
         $temp[1] = '//'.$temp[1];
	 $temp[1] = str_replace('///','',$temp[1]);
	 $temp[1] = str_replace('//','',$temp[1]);
	 if($temp[1] != ''){
	    $temp = explode('/',$temp[1]);
            $this->user_fuse = $temp[0].'/'.$temp[1];
	 }
      }
      return $this->user_fuse;	
   }
   function _uri($input){
      $this->uri = Array();
      $temp = explode($this->index_name,$input);
      if(count($temp) >= 2){
         $temp[1] = '//'.$temp[1];
	 $temp[1] = str_replace('///','',$temp[1]);
	 $temp[1] = str_replace('//','',$temp[1]);
	 if($temp[1] != ''){
	    $temp = explode('/',$temp[1]);
	    array_shift($temp);
	    array_shift($temp);
	    $i = 0;
	    foreach($temp as $value){
               $this->uri[$i] = $value;
	       $i++;
	    }
	 }
      }
      return $this->uri;	
   }

   function data($dir = '',$createDir = True){	
	$path = 'data/'.$dir;
	$path = str_replace('\\','/',$path);	    	
	$path = '///'.$path.'///';
	$path = str_replace('////','',$path);	    	
	$path = str_replace('///','',$path);	
    	$path = str_replace('//','/',$path);	
	
	$temp = explode('/',$path);
	 if(count($temp) > 1){
	    $temp2 = $this->root_path;
	    foreach($temp as $value){
		$temp2 = $temp2.'/'.$value;
	       if(!is_dir($temp2) and $createDir){
	          mkdir($temp2);							
	       }
	    }		
	 }else{
	    if(!is_dir($this->root_path.'/'.$path) and $createDir){
	       mkdir($this->root_path.'/'.$path);								 
	    }
	 }
	 return $this->root_path.'/'.$path;
   }
   
   function _compatible(){
      //try to make the same betwen windows and linux
      $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'///';
      $_SERVER['DOCUMENT_ROOT'] = str_replace('////','',$_SERVER['DOCUMENT_ROOT']);
      $_SERVER['DOCUMENT_ROOT'] = str_replace('///','',$_SERVER['DOCUMENT_ROOT']);
   }
   
/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.2
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        $this->rmdirr("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}
 
   
} 
//end of ENV class; 

} 

?>
