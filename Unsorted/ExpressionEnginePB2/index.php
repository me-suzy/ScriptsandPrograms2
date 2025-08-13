<?php
/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: index.php
-----------------------------------------------------
 Purpose: Triggers the main engine
=====================================================
*/

// URI Type
// This variable allows you to hard-code the URI type.
// For most servers, 0 works fine.
// 0 = auto  
// 1 = path_info  
// 2 = query_string

$qtype = 0; 


// DO NOT EDIT BELOW THIS!!! 

error_reporting(0);

if (isset($_GET['URL'])) 
{ 
	if (substr($_GET['URL'], 0, 4) != "http") $_GET['URL'] = "http://".$_GET['URL']; 
	header("location: ".$_GET['URL']); 
	exit; 
}

$uri  = '';
$pathinfo = pathinfo(__FILE__);
$ext  = ( ! isset($pathinfo['extension'])) ? '.php' : '.'.$pathinfo['extension'];
$self = ( ! isset($pathinfo['basename'])) ? 'index'.$ext : $pathinfo['basename'];

switch ($qtype)
{
	case 0 :	if (isset($_SERVER['PATH_INFO']) AND $_SERVER['PATH_INFO'] != '' AND $_SERVER['PATH_INFO'] != "/".$self) $uri = &$_SERVER['PATH_INFO']; elseif (isset($_SERVER['QUERY_STRING'])) $uri = &$_SERVER['QUERY_STRING']; 
		break;
	case 1 :	if (isset($_SERVER['PATH_INFO'])) $uri = &$_SERVER['PATH_INFO']; 			
		break;
	case 2 :	if (isset($_SERVER['QUERY_STRING'])) $uri = &$_SERVER['QUERY_STRING']; 
		break;
}

unset($system_path);
unset($config_file);
unset($qstr);

require 'path'.$ext;

if ((isset($template_group) AND isset($template)) && $uri != '')
{
	if (preg_match("#^\/P\d+\/$#", $uri))
	{
		$qstr = $uri;
	}
	else
	{
		$template_group = '';
		$template = '';
	}
}

if ( ! isset($system_path))
{
	if (file_exists('install'.$ext))
	{
		header("location: install".$ext); 
		exit;
	}
	else
	{
        exit("The system does not appear to be installed. Click <a href='install.php'>here</a> to install it.");	
	}
}

if ( ! ereg("/$", $system_path)) $system_path .= '/';

require $system_path.'core/core.system'.$ext;

?>