<?php
// include other stuff
include("functions_admin.php");
include("functions_fetch.php");
include("functions_moderate.php");
include("functions_phrases.php");
include("functions_skineditor.php");
include("functions_skins.php");
include("functions_submissions.php");
include("functions_url.php");

// mainstream functions
function dbSecure($code){
	if (!get_magic_quotes_gpc()) {
	   $code = addslashes($code);
	}
	return $code;
}

function un($code){
	if (get_magic_quotes_gpc()) {
	   $code = stripslashes($code);
	}
	return $code;
}

// allow encoding of messages
function Encode($code){
	$code = nl2br($code);
	return $code;
}

// 404 error function pretty much
function notfound(){
	header("HTTP/1.0 404 Not Found");
	echo("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">");
	echo("<HTML><HEAD>");
	echo("<TITLE>404 Not Found</TITLE>");
	echo("</HEAD><BODY>");
	echo("<H1>Not Found</H1>");
	echo("The requested URL was not found <strong>(database error)</strong> on this server.<P>");
	echo("</BODY></HTML>");
	die();
}

// crazy crap to start a session
function StartSession(){
	ini_set('url_rewriter.tags', '');
	session_start();
}

// redirect or echo URL if not supported
function redirect($url){
	Header("Location: " . $url);
	echo($url);
	die();
}
?>