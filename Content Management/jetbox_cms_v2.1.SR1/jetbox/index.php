<?
// This is the standard front-end with Jetbox CMS v2.0.X, it is provided as-it-is.
// It demonstrates some of the features in JETBOX CMS.
// We don't have plans to fully integrate the front-end with the cms,
// so you always have to do some php programming for the front-end.
// It has some nice features like tabs and breadcrumtrails in it.
// A user can also log-in, it's up to you do something with it.

// Function in this file & what they do:

// top_nav(), bot_nav()
// Create the main top, and bottom navigation
// To change the look & feel or order check these functions

// fp_news(), fp_events(), fp_outputs()
//  Gets latest news, events or outputs from database and puts it in the template

// addbstack($item, $name, $view='')
//  Used for breadcrum trail

// backtrackcrum($crumitem)
//  Get root item for creation of breadcrum trail

// backtrackopentree($item)
//  Get root item for creation of breadcrum trail for "pages"
// opentree($item)
//  Get "page"
// openitem($item)
//  Get "content block"

// endstripper($buffer)
// Strips slashes from all output

// Never append sessions to the url
// Doesn't always work though
ini_set('session.use_trans_sid','0');
ini_set('session.use_only_cookies','1');

define('VALID_PAGE', true);

// Disable magic_quotes_runtime - don't change
set_magic_quotes_runtime(0);

// Standard includes
// All front-end specific includes are in "includes" and start with "f_"
// All includes for the back-end are also in "includes"
include ("includes/f_includes.inc.php");
// NAVIGATION
// A standard url could be index.php/view/news/item/123
// This function splits the url into vars
// This system allows the search engines to crawl all the pages
//phpinfo();

$install_dir_count = substr_count($install_dir, '/');
//echo $_SERVER["REQUEST_URI"];
$url_to_split=explode("?",$_SERVER["REQUEST_URI"]);
if ($use_standard_url_method==true) {
	$url = explode("/",$url_to_split[1]);  // Splits URL into array
	$install_dir_count=-2;
}
else{
	$a=explode("index.php",$url_to_split[0]);
	//echo count($a);
	if(count($a)==1){
	//	echo "asdasdas";
		$install_dir_count-=1;
	}
	$url = explode("/",$url_to_split[0]);  // Splits URL into array
}

for($split=0;$split<5;$split++){
	$install_dir_count+=2;
	$_URL[$url[$install_dir_count]]=$url[$install_dir_count+1];
	$url[$install_dir_count]=urldecode($url[$install_dir_count]);
	$$url[$install_dir_count]=urldecode($url[$install_dir_count+1]);
}
// END NAVIGATION


// This is the section for displaying information when you are installing Jetbox
if ($install_jetbox==true && $view=='installation') {
	include("instl/index.php");  
	die();
}
if ($install_jetbox==true || $error_message<>'') {
  include("includes/f_error_handling.php");  
}
// End of installation messages
// Include some extra things when everything is correctly configured 
else{
	// Statistics 
	// Can be configured in general_settings.inc.php
	if ($phpOpenTracker_enabled==true) {
		// prepend phpOpenTracker for statistics
    require_once 'phpOpenTracker.php';
    phpOpenTracker::log();
	}
	
	// Someone may be logged-in
	// Login and registration is handeled by webuser.php
	// All login & registration function can be found in /includes/f_jetstream_core_one.inc.php
	// Check out webuser.php for more info if something goes wrong

	// Check for front-end session
	if ($_COOKIE[session_name()]) {
		session_start();
		if ($_SESSION["uid"] && $_SESSION["type"]=="frontend") {
			$uresult=mysql_prefix_query("SELECT * FROM webuser WHERE uid='".$_SESSION["uid"]."'") or die(mysql_error());
			if (mysql_num_rows($uresult)>0) {
				$uarray=mysql_fetch_array($uresult);
			}
		}
	}
}

// the main purpose for this output buffer functions is to strip the slashes on all output
// Start of main page code
ob_start("endstripper");

// INTEGRATION OF WORKFLOW IN THE FRONT-END
// To add workflow to your front-end you must add the $wfqadd var to your queries
// wfqadd: workflow query addition
// You can add $wfqadd to a query of a container that has workflow enabled
// For more information check main_page.php

// Online and offline query date
$wfqd=date("Y-m-d H:i:s");
$wfqadd= " AND struct.status='published' AND struct.ondate<='".$wfqd."' AND struct.offdate>='".$wfqd."' ";
	
// General template initiation for the main site layout.
$t = new Template("./");
$t->set_var("pagetitle", $sitename);
if (isset($uarray)) {
	$t->set_var("userlink", "<a href=\"".$absolutepathfull."view/webuser\">Hello, ".$uarray["firstname"]."</a>");
}
else{
	$t->set_var("userlink", "<a href=\"".$absolutepathfull."view/webuser\">Sign in or register</a>");
}


// Check if we have a view var and include the appropriate php file
// This is administrated with "navigation" in  admin
if (isset($view)) {
	$dodefaultpage=false;
	$sql2="SELECT * FROM navigation WHERE view_name='".$view."'";
	$r2 = mysql_prefix_query($sql2) or die(mysql_error()." q: ".$sql2."<br /> Line: ".__LINE__." <br/>File: ".__FILE__);
	if ($ra2 = mysql_fetch_array($r2)){
		//echo $ra2["file_name"];
		include($ra2["file_name"]);
	}
	else{
		$dodefaultpage=true;
	}
}
elseif (isset($item) && is_numeric($item)){
	//All items with workflow have a unique id in the struct db table
	//Check what type off item it is.
	//The container_id is the id Jetstream CMS uses to registrate containers
	$dodefaultpage=false;
	$primq = "SELECT struct.*, UNIX_TIMESTAMP(struct.ondate) AS uondate, UNIX_TIMESTAMP(struct.offdate) AS uoffdate, struct.id AS struct_id FROM struct WHERE struct.id=".$item;
	$primr = mysql_prefix_query($primq) or die(mysql_error());
	if ($primarray = mysql_fetch_array($primr)){
		switch ($primarray["container_id"]){
			case 11:
				include("open_tree.php");
			break;
			default:
				$dodefaultpage=true;
			break;
		}
	}
	else{
		$dodefaultpage=true;
	}
}

if($dodefaultpage){
	include("main_page.php");
}

$t->set_var("baseurl", "<base href=\"".$front_end_url."\">");
$t->set_var("absolutepathfull", $absolutepathfull);

// Create the top and bottom navigation
// Edit the functions to add and remove navigation options
$t->set_var("topnav", top_nav());
$t->set_var("botnav", bot_nav());
$t->parse("finaloutput", "block");
$t->p("finaloutput");
$containera = ob_get_contents(); 
ob_end_flush();

function endstripper($buffer) {
	global $use_standard_url_method, $eval_result;
	if ($use_standard_url_method) {
		$buffer=preg_replace('/index.php\/(.*?)"/i','?$1"'.'"', $buffer);
	}

	$reg = "/[ \t]*!!function_\s*?\n?(\s*.*?\n?)\s*!!\s*?\n?/";
	preg_match_all($reg, $buffer, $m);
	$m = $m[1];
	if (is_array($m)) {
		reset($m);
		while(list($k, $v) = each($m)) {
			$function_test_array=explode("(",$v);
			if (function_exists("function_".$function_test_array[0])) {
				@eval("function_".html2specialchars($v).";");
				$buffer = str_replace("!!function_".$v."!!", $eval_result, $buffer);
			}
		}
	}

	return stripslashes($buffer);
}
?>