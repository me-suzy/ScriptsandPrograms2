<?

$include_dir = str_replace("\\","/",dirname(dirname(__FILE__)));
// standard path for includes
$includes_path= $include_dir."/includes";
if (strstr( PHP_OS, 'WIN') ) {
	ini_set('include_path', $includes_path . ";./;" . ini_get('include_path'));    
}
else{
	ini_set('include_path', $includes_path . ":./:" . ini_get('include_path'));
}

// error handler
@include($includes_path . "/error_handler.inc.php");
if (!@include($includes_path . "/general_settings.inc.php")) {
    echo "<html><body><font face=\"arial\" size=\"-1\"><b>Unfortunatally Jetbox encountered a PHP error and is unable to include required files.</b></font><br><font face=\"arial\" size=\"-1\">PHP detects an incorrect root folder for your system.<br>Detected root folder: <i>".$include_dir."</i><br>
		Configure the <b>\$include_dir</b> in /includes/f_includes.inc.php & includes/includes.inc.php manually.<br><br>The origional php error message is displayed below. This message contains the correct root folder in the \"in... on line ..\" part.<br>";
		include($includes_path . "/general_settings.inc.php");
		echo"</font></body></html>";
		die();
}

//todo: make debugging easy, with a debug mode, that displays when the last include fucks up the system

// lifespan of front end session
$SESS_LIFE ="86400";
// session name
session_name('backsession');

ini_set("session.save_handler", "user");

// authentication functions
require($includes_path . "/auth.inc.php");

// CMS header and footer functions
// jetstream_header() also contains the left navigation
require($includes_path . "/jetstream-html.inc.php");

// contains all central functions
require($includes_path . "/jetstream_core_one.inc.php");

// session handler
require($includes_path . "/session_handler_db.php");

// template handler
require($includes_path . "/template.inc");

// HTTP_USER_AGENT Client Sniffer for PHP
require($includes_path . "/phpSniff.core.php");
require($includes_path . "/phpSniff.class.php");

// Functions for prefixing tables sql queries
require($includes_path . "/sqlprefix_empty.php");

// Email functions
require($includes_path . "/class.phpmailer.php");
require($includes_path . "/class.smtp.php");

// Overview list paginating
require($includes_path . "/my_pagina_class.php");

$GDfuncList = get_extension_funcs('gd');
if( $GDfuncList ){
	// Graphics functions
	require($includes_path . "/jpgraph.1.16/jpgraph.php");
}
?>