<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > übergreifende Datei, Verwendung für Admin und für Userbereich
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: global.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

// PHP-Version prüfen
$curver = intval(str_replace(".","", phpversion()));
$ini_val = ( $curver >= '400' ) ? 'ini_get' : 'get_cfg_var';

// bei PHP-Version älter als 4.1.0 neue superglobale Variablen füllen
if($curver < 410) {
	$_POST = array();
	$_GET = array();
	$_COOKIE = array();
	$_SESSION = array();
	$_FILES = array();
	$_SERVER = array();
	$_ENV = array();
	$_REQUEST = array();
	fill_new_vars();	
	}
	
if (get_magic_quotes_gpc() == 0) {
  $_GET = addslashes_array($_GET);
  $_POST = addslashes_array($_POST);
  $_COOKIE = addslashes_array($_COOKIE);
}	
	
// register_globals prüfen 
if(@$ini_val("register_globals")) {
	$register_globals = TRUE;
} else {
	$register_globals = FALSE;
}


// neue Variablen mit Werten füllen	
function fill_new_vars() 
	{
	global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_COOKIE_VARS, $HTTP_SESSION_VARS, $HTTP_POST_FILES, $HTTP_SERVER_VARS, $HTTP_ENV_VARS; // INPUT VARS
	global $_REQUEST, $_COOKIE, $_POST, $_GET, $_SERVER, $_FILES,$_ENV,$_SESSION; // OUTPUT VARS
		// Variablen des Post-Parameters einlesen
		if(is_array($HTTP_POST_VARS)) {
				foreach($HTTP_POST_VARS as $var=>$value)
				{
					$_REQUEST[$var] = $value;
					$_POST[$var] = $value;
				}			
		}
		// Variablen des GET-Parameters einlesen
		if(is_array($HTTP_GET_VARS)) {
				foreach($HTTP_GET_VARS as $var=>$value)
				{
					$_REQUEST[$var] = $value;
					$_GET[$var] = $value;
				}			
		}		
		// Variablen des COOKIE-Parameters einlesen
		if(is_array($HTTP_COOKIE_VARS)) {
				foreach($HTTP_COOKIE_VARS as $var=>$value)
				{
					$_REQUEST[$var] = $value;
					$_COOKIE[$var] = $value;
				}			
		}		
		// Variablen des SESSION-Parameters einlesen (ohne $_REQUEST)
		if(is_array($HTTP_SESSION_VARS)) {
				foreach($HTTP_SESSION_VARS as $var=>$value)
				{
					$_SESSION[$var] = $value;
				}			
		}		
		// Variablen des FILES-Parameters einlesen (ohne $_REQUEST)
		if(is_array($HTTP_FILES_VARS)) {
				foreach($HTTP_FILES_VARS as $var=>$value)
				{
					$_FILES[$var] = $value;
				}			
		}		
		// Variablen des SERVER-Parameters einlesen (ohne $_REQUEST)	
		if(is_array($HTTP_SERVER_VARS)) {
				foreach($HTTP_SERVER_VARS as $var=>$value)
				{
					$_SERVER[$var] = $value;
				}			
		}		
		// Variablen des ENV-Parameters einlesen (ohne $_REQUEST)			
		if(is_array($HTTP_ENV_VARS)) {
				foreach($HTTP_ENV_VARS as $var=>$value)
				{
					$_ENV[$var] = $value;
				}			
		}	
		
	}
	
function new_session_reg($var)
	{
    global $register_globals, $HTTP_SESSION_VARS, ${$var};
    $reg = FALSE;
	    if($register_globals) {
	        $reg = session_register("$var"); 
	    } else {
	        if($HTTP_SESSION_VARS[$var] = ${$var}) $reg=TRUE;
			if($_SESSION[$var] =${$var}) $reg=TRUE;
	    }
    return $reg; 
	}
	
function new_session_unreg($var)
	{
    global $register_globals, $HTTP_SESSION_VARS, ${$var};
    $reg = FALSE;
	    if($register_globals) {
	        $reg = session_unregister("$var"); 
	    } else {
	        unset($HTTP_SESSION_VARS[$var]);
			unset($_SESSION[$var]); 
			$reg=TRUE;
	    }
    return $reg; 
	}		

function addslashes_array($array) 
	{
	foreach ($array as $key => $val) {
		$array[$key] = (is_array($val)) ? addslashes_array($val) : addslashes($val);
		}
  	return $array;
	}
	
function stripslashes_array($array)
	{
    if(is_array($array)) {
    	foreach ($array as $key => $val) {
    		$array[$key] = (is_array($val)) ? stripslashes_array($val) : stripslashes($val);
    		}
      	return $array;
        }	
	}	
	
// Erweiterung der Upload Funktion
// siehe http://www.php.net/manual/de/function.is-uploaded-file.php	
if (!function_exists("is_uploaded_file") and get_cfg_var("safe_mode")==0) {
	function is_uploaded_file($filename) {
    		if (!$tmp_file = get_cfg_var('upload_tmp_dir')) $tmp_file = dirname(tempnam('', ''));
    		$tmp_file .= '/' . basename($filename);
    		return (ereg_replace('/+', '/', $tmp_file) == $filename);
  	} 
	function move_uploaded_file($filename, $destination) {
     		if (is_uploaded_file($filename))  {
       			if (copy($filename,$destination)) return true;
       			else return false;
       		}
      		else return false;
   	}
}	
?>