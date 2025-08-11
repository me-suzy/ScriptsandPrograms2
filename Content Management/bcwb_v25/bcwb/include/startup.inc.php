<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$argv=array();

if(
preg_match("/^.?system/is", $GLOBALS["REQUEST_URI"]) OR
preg_match("/^.?scripts/is", $GLOBALS["REQUEST_URI"]) OR
preg_match("/^.?dcontent/is", $GLOBALS["REQUEST_URI"])
) exit;


if(!$root_path) {
$root_path = preg_replace("/index\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"])."/";
$root_path = preg_replace("/\/\//is","/", $root_path);
}


if (!ini_get("register_globals")) die("Cold't declare register_globals");


// Debug services
include($root_path.'include/startup/debug.inc.php');
include($root_path.'include/startup/common_functions.inc.php');
include($root_path.'include/lib/data.lib.php');



if( extension_loaded("xslt") ) 
{ define("SABLOTRON", true); include($root_path.'include/lib/admin.lib.php'); }


if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
if(! include($root_path_admin.'lang/'.$default_language.'.inc.php') ) die("Can't include ".$root_path.'lang/'.$default_language.'.inc.php');

if($MODREWRITE == "disable" AND $_GET["vpath"]) $GLOBALS["REQUEST_URI"] = $_GET["vpath"];

if(!ereg("/$",$GLOBALS["REQUEST_URI"])) $GLOBALS["REQUEST_URI"].="/";
	$http_prefix = preg_replace("/^.*?".preg_replace("/http:\/\//is", "", $GLOBALS["HTTP_HOST"])."/is", "", $http_path);
	$http_prefix = preg_replace("/\/$/", "", $http_prefix);

$SCRIPT_URI = $GLOBALS["REQUEST_URI"];
if($http_prefix) $SCRIPT_URI = eregi_replace("^".$http_prefix, "", $SCRIPT_URI);
$SCRIPT_URI=preg_replace("/\?(.*?)$/","",$SCRIPT_URI);

$NOSLASH_SCRIPT_URI=preg_replace("/^\//", "", $SCRIPT_URI);
$NOSLASH_SCRIPT_URI=preg_replace("/\/$/", "", $NOSLASH_SCRIPT_URI);

$uri_array=split("\/", $SCRIPT_URI);
if(count($uri_array)) { foreach($uri_array as $val)
{
	if(!ereg("^\?",$val) AND $val AND $val!="index.php") $argv[]=$val;
}
}
unset($uri_array);

include($root_path.'include/startup/auth.inc.php');

	if($argv[0]==$admin_subdomain) unset($argv[0]); // For authorization
	if($argv[0]=="noxsl") { array_shift($argv); define("BCWB_NOXSLT", true); $http_path .= "noxsl/"; } // For Flash and other ext. parser
	
	if($argv[0]=="index" AND !$argv[1]) $argv = false;
	
	$argv_level=count($argv);
	$argv_last=$argv[$argv_level-1];
	$argv_pre_last=$argv[$argv_level-2];
	
	if(	preg_match("/\.jpg$/is", $argv_last) OR 
		preg_match("/\.jpeg$/is", $argv_last) OR 
		preg_match("/\.gif$/is", $argv_last) OR 
		preg_match("/\.bmp$/is", $argv_last) OR 
		preg_match("/\.swf$/is", $argv_last) OR 
		preg_match("/\.pdf$/is", $argv_last) OR 
		preg_match("/\.doc$/is", $argv_last) OR 
		preg_match("/\.zip$/is", $argv_last) OR 
		preg_match("/\.exe$/is", $argv_last)
	) die('Invalid request...');
	
	if( $argv_pre_last )
	{
		$var_pre_last = ($argv_level-1).".".$argv_pre_last.( $argv_level>2 ? ".".$argv[$argv_level-3] : "" );
	}

	// Custom XSL-template 
	if( preg_match("/\.xsl$/is", $argv_last ) ) 
	{
		$custom_template = $argv_last;
		unset( $argv[ $argv_level-1 ] );
		$argv_level=count($argv);
		$argv_last=$argv[$argv_level-1];
		$argv_pre_last=$argv[$argv_level-2];
	}
	

?>