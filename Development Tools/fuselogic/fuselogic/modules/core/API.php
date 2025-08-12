<?php

/*
+-------------------------------------------------------------+
|   PHP version 4                                             |
+-------------------------------------------------------------+
|   Version : 0.0.17                                          |
+-------------------------------------------------------------+
|   Copyright (c) 2002 - 2004 Eko Budi Setiyo                 |
+-------------------------------------------------------------+ 
| License : BSD License                                       |
| http:www.haltebis.com/wakka/main/license                    |
+-------------------------------------------------------------|
| Authors : Setiyo, Eko Budi <ekobudi@haltebis.com>           |
+-------------------------------------------------------------+
*/

if(!function_exists('Queue')){

function FL_DEBUG($flag = ''){
   global $FuseLogic;
	 return $FuseLogic->debug($flag);
}
function Fuse($fuse = ''){
    global $FuseLogic;
    $temp = explode('/',$fuse);   
	  return @(string)$FuseLogic->fuse[$temp[0]][$temp[1]];		
}
function FuseExists($fuse = ''){
    global $FuseLogic;
		$temp = explode('/',$fuse);  
    if(isset($FuseLogic->fuse[$temp[0]][$temp[1]])){
		    return True;
		}else return False;	  
}
function Queue($fuseaction,$layoutName = 'noname',$ParentFuseaction = ''){
	global $FLQueue;
	$FLQueue->Queue($fuseaction,$layoutName,$ParentFuseaction);
}
function QueueIf($fuseaction,$layoutName = 'noname',$ParentFuseaction = ''){
	global $FLQueue,$FuseLogic;	
	$module = $FuseLogic->getModule($fuseaction);	
	$subModule = $FuseLogic->getSubModule($fuseaction);
	if(isset($FuseLogic->fuse[$module][$subModule])){
	    $FLQueue->Queue($fuseaction,$layoutName,$ParentFuseaction);			
	}
}

function FirstQueue($fuseaction,$layoutName = 'noname',$ParentFuseaction = ''){
	global $FLQueue;
	$FLQueue->FirstQueue($fuseaction,$layoutName,$ParentFuseaction);
}
function FirstQueueIf($fuseaction,$layoutName = 'noname',$ParentFuseaction = ''){
	global $FLQueue,$FuseLogic;	
	$module = $FuseLogic->getModule($fuseaction);	
	$subModule = $FuseLogic->getSubModule($fuseaction);
	if(isset($FuseLogic->fuse[$module][$subModule])){
	    $FLQueue->FirstQueue($fuseaction,$layoutName,$ParentFuseaction);			
	}
}
function SingletonQueue(){
	global $FLQueue;
	$FLQueue->singleton();
}

function CloseQueue(){
	global $FLQueue;
	$FLQueue->close();
}
function ClearQueue(){
	global $FLQueue;
	$FLQueue->clear();
}
function setDefaultCommand($fuseaction){
	global $FuseLogic;
	$FuseLogic->setDefaultFuseaction($fuseaction);
}

function setModule($name,$location){
	global $FuseLogic;	
	$FuseLogic->setModule($name,$location);	
}

function getLayout($layoutName = 'noname'){
	global $FLLayout;
	return $FLLayout->getLayout($layoutName);
}

function saveLayout($layoutName = 'noname',$content = ''){
	global $FLLayout;
	return $FLLayout->setLayout($layoutName,$content);
}

function subModule(){
	global $FuseLogic;
	return $FuseLogic->getSubModule();
}

function WebPath($module_name= ''){
	global $FuseLogic;
	return $FuseLogic->getWebPath($module_name);
}

function module(){	
	global $FuseLogic;	
	return $FuseLogic->module;
}

function getLayoutName(){
  global $FuseLogic;
  return $FuseLogic->LayoutName;
}

function Location($URL, $addToken = 0) {
		$questionORamp = (strstr($URL, "?"))?"&":"?";
		$location = ($addToken && substr($URL, 0, 7) != "http://")?$URL.$questionORamp.$SID:$URL; //append the sessionID ($SID) by default
		ob_end_clean(); //clear buffer, end collection of content
		if(headers_sent()) {
			print('<script language="JavaScript"><!--
	location.replace("'.$location.'");
	// --></script>
	<noscript><META http-equiv="Refresh" content="0;URL='.$location.'"></noscript>');
		} else {
			header('Location: '.$location); //forward to another page
			exit; //end the PHP processing
		}
}

function index(){
   global $FL_ENV;
   return $FL_ENV->index;
}

function userModule(){
   global $FuseLogic;
   return $FuseLogic->getUserModule();
}

function userSubModule(){
   global $FuseLogic;
   return $FuseLogic->getUserSubModule();
}
//depriciated use RealPath()
function getModulePath($module = ''){
    global $FuseLogic;
    return $FuseLogic->getModulePath($module);
}
function Real_Path($module = ''){
    global $FuseLogic;
    return $FuseLogic->getModulePath($module);
}
function ob_end(){
   $buffer = ob_get_contents();
   ob_end_clean();
   return $buffer;

}

}
?>
