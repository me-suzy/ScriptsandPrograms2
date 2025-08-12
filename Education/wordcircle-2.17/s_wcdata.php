<?php

/*

CLASS
-----
WCDATA


PROPERTIES
----------
totalRows
InsertedID
database
username
server


METHODS
-------
execQuery()
packageArray()
validate()


*/

//START CLASS DB ------------------------------------------------

class wcdata {

 var $numberOfRows = 0;

 function execQuery($queryString,$DoIncrement=false){
   $errorCode = "0";
  if ($DoIncrement){
  
  if(!isset($_GET['inc'])){
  $increment = $GLOBALS['increment'];
  }else{
  $increment = $_GET['inc'];
  }

  	if(strstr($queryString,"select") != false){
	   eregi("(from[[:space:]]*[a-z0-9_]*)",$queryString,$regs);
	  $link2 = mysql_connect($GLOBALS["dbServer"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]) or   $errorCode = mysql_error();
	  mysql_select_db($GLOBALS["dbName"]) or $errorCode = mysql_error();
  /* Performing SQL query */

  
  		  //if we are looking at members, and incrementing, we don't want to limit message list by group - otherwise, we always go by group
  	  if(isset($_GET['uid']) and $_GET['a'] == 'members'){
	  
	  $result = mysql_query("select count(*) as countrows from ".eregi_replace("from?"," ",$regs[0])." where created_by=".$_GET['uid']) or $errorCode = mysql_error();
	  
	  }else{	
  
      $result = mysql_query("select count(*) as countrows from ".eregi_replace("from?"," ",$regs[0])." where group_id = ".$_GET['gid'].(isset($_GET['did'])?" and discussion_id=".$_GET['did']:"").(isset($_GET['tid'])?" and topic_id=".$_GET['tid']:"").(isset($_GET['pid'])?" and project_id=".$_GET['pid']:"")   ) or $errorCode = mysql_error();

	}
		
	

	  $countresult =  mysql_result($result,0); 
	  $this->numberOfRows = $countresult;
	  	 $queryString .= " limit ";
	  	if(isset($_GET['pagenum'])){$queryString .= ($_GET['pagenum']*$increment).",";}
		 $queryString .= $increment;
	  }
	  }
  	  $link = mysql_connect($GLOBALS["dbServer"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]) or   $errorCode = mysql_error();
     mysql_select_db($GLOBALS["dbName"]) or $errorCode = mysql_error();
  /* Performing SQL query */
  $result = mysql_query($queryString) or $errorCode = mysql_error();
  /* Closing connection */
   if ($errorCode <> "0"){
   $error = $errorCode;
   $GLOBALS['error'][0] = $error;
   return 0;
   }else{
   return $result;
   }
  }


  function wcdata(){
  $GLOBALS['error'] = array();
  }
  
    function checkNameSpace($nameToCheck,$errorMessage){
  $charsallowed='.1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (!ereg("^[a-zA-Z]",$nameToCheck))){
   array_push($GLOBALS['error'],$errorMessage);
  } 
  }
  
  function checkNames($nameToCheck,$errorMessage){
  $charsallowed='.1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_ -';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (!ereg("^[a-zA-Z]",$nameToCheck))){
   array_push($GLOBALS['error'],$errorMessage);
  } 
  }

  function checkTyped($nameToCheck,$errorMessage){
  if (strlen($nameToCheck) < 1){
  	array_push($GLOBALS['error'],$errorMessage);
   }
  }
  
   function checkLen($nameToCheck,$len,$errorMessage){
  if (strlen($nameToCheck) > $len){
  	array_push($GLOBALS['error'],$errorMessage);
   }
  }
  
  	 function compareTwo($nameToCheck,$nameToCheck2,$errorMessage){
  if (trim($nameToCheck) <> trim($nameToCheck2)){
  	array_push($GLOBALS['error'],$errorMessage);
   }
  } 
  
  function checkEmail($nameToCheck,$errorMessage){
  $charsallowed='1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-.@';
   $mycheck=strspn($nameToCheck,$charsallowed);
    if( (strlen($nameToCheck) != $mycheck) or (strlen($nameToCheck) < 1)){
  	array_push($GLOBALS['error'],$errorMessage);
   }
}

 function checkFileTypes($nameToCheck,$errorMessage){
	   foreach($GLOBALS['forbidden_filetypes'] as $value){
	    if (strstr($nameToCheck,$value) !== false){
			  	array_push($GLOBALS['error'],$errorMessage);
		}
	   }
   }

}



?>