<?
$include_dir = str_replace("\\","/",dirname(dirname(__FILE__)));
// standard path for includes
$includes_path= $include_dir."/includes";
if (strstr( PHP_OS, 'WIN') ) {
	ini_set('include_path', ini_get('include_path') . ";./;" . $includes_path);    
}
else{
	ini_set('include_path', ini_get('include_path') . ":./:" . $includes_path);
}

if (!@include($includes_path . "/general_settings.inc.php")) {
    echo "<html><body><font face=\"arial\" size=\"-1\"><b>Unfortunatally Jetbox encountered a PHP error and is unable to include required files.</b></font><br><font face=\"arial\" size=\"-1\">PHP detects an incorrect root folder for your system.<br>Detected root folder: <i>".$include_dir."</i><br>
		Configure the <b>\$include_dir</b> in /includes/f_includes.inc.php & includes/includes.inc.php manually.<br><br>The origional php error message is displayed below. This message contains the correct root folder in the \"in... on line ..\" part.<br>";
		include($includes_path . "/general_settings.inc.php");
		echo"</font></body></html>";
		die();
}

// lifespan of front end session
$SESS_LIFE ="86400";
// session name
session_name('frontsession');

// authenticatoin functions
require($includes_path . "/f_auth.inc.php");

// contains all central functions
require($includes_path . "/f_jetstream_core_one.inc.php");

// Functions to display conents on the front-end website
require($includes_path . "/f_general_functions.inc.php");

// session handler
require($includes_path . "/session_handler_db.php");

// template handler
include ($includes_path . "/template.inc");

// Functions for prefixing tables sql queries
require($includes_path . "/sqlprefix_empty.php");
?>