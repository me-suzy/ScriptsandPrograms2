<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/

error_reporting(0);
session_start();

// password for admin functions
$password = "admin123";

// Site Name - used for the page head and title
$site_name    = "PHP-CSL V0.9";

// Site URL - used for the page head
$site_url = "index.php";

// Register Global bypass
$use_super_globals = 1; // change to 0 if your having problems

// Use inline frame for code display
$use_iframe = 0; // change to 0 if your browser does'nt support iframe

// hide admin options?
// if 0, options will not show unless -
// the admin has logged in
$hide_options = 1; // 1 = yes hide and 0 = no, do not hide

// home/root directory
$snippet_root     = @dirname(__FILE__);

// template file used to create syntax library file
$snippet_s        = "/library.s"; 


// template file used to create description library file
$snippet_d        = "/library.d"; 

// file which store the library categories
$snippet_libs     = "libs.cfg";


// file which store the current/default library
$default_lib_file	= "default_lib.cfg";


// check for library session
if(isset($_POST['libs_session'])) {
	session_register("libs_session");
    $libs_session = $_POST['libs_session'];
    header("Location: $site_url");
}

// library folder name - do not change!!
if(isset($_SESSION['libs_session'])) {
	$snippet_library  = "/".$_SESSION['libs_session']."/";
} else {
	$snippet_library  = "/".trim(fread(fopen($default_lib_file,"r"), 80000))."/";
}


// default file to make snippet cats - template file
$snippet_cats_default    = "cats.cfg";


// file which store the snippets categories - do not change!!
$snippet_cats     = $snippet_library.$snippet_cats_default;


// library syntax file extension/suffix
$snippet_ext_s    = ".s";


// library description file extension/suffix
$snippet_ext_d    = ".d";


// current theme
$snippet_theme    = "themes/default";


// DO NOT EDIT BELOW HERE
if ( $use_super_globals == 0 ) {
   extract($_POST);
   extract($_GET);
   extract($_FILES);
   extract($_SERVER);
}


if(isset($_GET['logout'])) {
   session_destroy();
   header("Location: index.php");
}

if(isset($_POST['csl_pass'])) {
   if ($_POST['csl_pass'] == $password) {
      session_register("phpcsl");
      $phpcsl = "yes";
      $ur = "index.php?".$_POST['query'];
      header("Location: $ur");
   } else {
      header("Location: index.php?login=y&err=yes");
   }
}


if(!session_is_registered('phpcsl') &&
   !isset($_GET['login']) && isset($_GET['act'])) {
   	
   	if(!$_GET['act'] == "session") {
   	 $ur = "index.php?login=y&q=".base64_encode(querystr());
     header("Location: $ur");
   	}
}


// build URL querystring
function querystr() {
	global $HTTP_GET_VARS;
	$q = "";
	foreach($HTTP_GET_VARS as $n => $m) {
		$q .= "$n=$m&";
	}
	return $q;
}
?>
