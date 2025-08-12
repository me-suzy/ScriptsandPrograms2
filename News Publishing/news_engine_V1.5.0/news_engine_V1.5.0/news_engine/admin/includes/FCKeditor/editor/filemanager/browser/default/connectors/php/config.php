<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: config.php
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */
$engine_path = "../../../../../../../../../"; 
include_once($engine_path . "lib.inc.php");

global $Config ;

// SECURITY: You must explicitelly enable this "connector". (Set it to "true").
$Config['Enabled'] = true ;

// Path to user files relative to the document root.
//$Config['UserFilesPath'] = '/projekte/tpl_art/images/site_images/' ;
$fckphp_engine_path = str_replace("/admin/includes/FCKeditor/editor/filemanager/browser/default/connectors/php","",$_SERVER['PHP_SELF']);
$fckphp_engine_path = str_replace("basexml.php","",$fckphp_engine_path);
$fckphp_engine_path = str_replace("connector.php","",$fckphp_engine_path);
$fckphp_engine_path = str_replace("config.php","",$fckphp_engine_path);
$fckphp_engine_path = str_replace("io.php","",$fckphp_engine_path);
$fckphp_engine_path = str_replace("util.php","",$fckphp_engine_path);

/*------------------------------------------------------------------------------*/
/* Path to user files relative to the document root (no trailing slash)		*/
/*------------------------------------------------------------------------------*/
$fckphp_config['UserFilesPath'] = $fckphp_engine_path."images/site_images/" ;
/*==============================================================================*/
//$Config['UserFilesPath'] = "../../../../../../../../../images/site_images/" ;
$Config['UserFilesPath'] = $fckphp_config['UserFilesPath'] ;

$Config['AllowedExtensions']['File']	= array() ;
$Config['DeniedExtensions']['File']		= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;

$Config['AllowedExtensions']['Media']	= array('swf','fla','jpg','gif','jpeg','png','avi','mpg','mpeg') ;
$Config['DeniedExtensions']['Media']	= array() ;

?>