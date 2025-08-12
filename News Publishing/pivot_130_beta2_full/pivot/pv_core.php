<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------


$build = "Pivot - 1.30 beta 2: 'Rippersnapper'";

DEFINE('INPIVOT', TRUE);

// make sure the $Cfg array isn't already set
$Cfg = array();
$Pivot_Vars = array();
$ThisUser = array();

// some global initialisation stuff
$Pivot_Vars = array_merge($_GET , $_POST, $_SERVER);

if(realpath(__FILE__)=="") {
	$pivot_path = dirname(realpath($_SERVER['SCRIPT_FILENAME']))."/";
} else {
	$pivot_path = dirname(realpath(__FILE__))."/";
}
$pivot_path = str_replace("\\", "/", $pivot_path);


// Include some other files
require_once($pivot_path.'pvlib.php');
require_once($pivot_path.'pvdisp.php');
require_once($pivot_path.'modules/module_db.php');
require_once($pivot_path.'modules/module_i18n.php');
require_once($pivot_path.'modules/module_lang.php');
require_once($pivot_path.'modules/module_parser.php');
require_once($pivot_path.'modules/module_ipblock.php');
require_once($pivot_path.'modules/module_snippets.php');




// Start the timer:
$starttime=getmicrotime();



GetSettings();
LoadDefLanguage();
Setpaths();


/**
 * Load the current theme
 */
if (($Cfg['deftheme']=="") || (!file_exists($pivot_path.'theme/'.$Cfg['deftheme'].'_theme.inc.php'))) {
	$Cfg['deftheme'] = "default";
}
require_once($pivot_path.'theme/'.$Cfg['deftheme'].'_theme.inc.php');

/**
 * Set the correct value for $i18n_use
 *
 * This needs work: more than iso-8859-1 have to have 'false'..
 */
if ($CurrentEncoding == "iso-8859-1") {
	$i18n_use = false;
} else {
	$i18n_use = true;
}

/**
 * If debug is set, include the file..
 */
if( $Cfg['debug']==1){
	require_once($pivot_path.'modules/module_debug.php');
} else {
	error_reporting(E_ERROR);
	function debug() { }
	function debug_sep() { }
	function debug_printbacktrace() { }
	function debug_printr() { }
}


// if pv_core is included from a weblog, we might need to check whether it is
// still up to date.
if(defined('INWEBLOG')){
	inweblogcheck();
}



/*   2004/10/24 :: JM - do /extras directory check
     2005/01/03 :: JM - more elegant implementation to solve touch
     r3
     create extensions/ dir and include lamer protection
-------------------------------------------------------------- */
/*
function makedir_extensions( $pivot_path,$extensions_name ) {

	// create dir name - some people won't put the dir in a logical place....
	$extensions_dir  = fixpath( $pivot_path.'/../'.$extensions_name );
	if( '/' != substr( $extensions_dir,-1 )) { $extensions_dir .= '/'; }
	$extensions_idx  = $extensions_dir.'index.php';

	// if dir doesn't exist - make it
	if( !file_exists( $extensions_dir )) { makedir( $extensions_dir ); }

	// lAm3eR protection
	if( !file_exists( $extensions_idx )) {
		// need to check that we can write here... look at permissions first
		if( is_writable( $extensions_dir )) {
			touch( $extensions_idx );
		} else {
			// can't write - say so discreetly
			debug( 'insufficient permission to create index.php in extensions/ dir' );
		}
	}
}

if(( isset( $Cfg['extensions_path'] ))&&( '' != $Cfg['extensions_path'] )) {
	// this is a directory
	if( '/' != substr( $Cfg['extensions_path'],-1 )) { $Cfg['extensions_path'] .= '/'; }
	makedir_extensions( $Paths['log_url'], $Cfg['extensions_path'] );
}
*/

// Produce an error message and exit.
function ErrorOut($msg='bebug death', $show='1') {
	global $Pivot_Vars;
	$content = '<b>pivot error</b>: ' . $msg . '<br />';
	if($show==1){
		foreach($Pivot_Vars as $key=>$value) {
			$content .= '<b>' . $key . '</b>: ' . $value . "<br />";
		}
	}
	SimplePage('Error', $content);
	exit;
}

// show a simple page..
function SimplePage($title='', $content='no content provided') {
	global $Cfg;

	$CurrentEncoding = $Cfg['defencoding'];

	echo <<< END_PAGE
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
			<head>
			<title>Pivot$title</title>
			<meta http-equiv="Content-Type" content="text/html; charset=$CurrentEncoding" />
			<style type="text/css">
				body {
					background: #F0F0F0;
					margin: 0;
					padding: 20px;
					text-align: left;
				}
				a {
					color: #666666;
				}
				div {
					background-color: #FFFFFF;
					border: 1px solid #111111;
					padding: 7px;
					font-family: tahoma;
					color: #000000;
					width: 550px;
				}
			</style>
			</head>
			<body>
				<div>
					$content
				</div>
			</body>
		</html>
END_PAGE;
}


function Load() {
	global $Pivot_Vars, $Cfg, $Users, $ThisUser;
	if($Pivot_Vars['func'] == "selfreg"){
		require_once('selfreg.php');
	}elseif($Cfg['installed'] == 0){
		require_once('setup.php');
	}else{
		CheckLogin();
		LoadUserLanguage();

		// convert encoding to UTF-8
		i18n_array_to_utf8($Pivot_Vars, $dummy_variable);

		$ThisUser = $Users[$Pivot_Vars['user']];
		require_once('pv_data.php');
		mainFunctions();
		if($Users[$Pivot_Vars['user']]['userlevel'] >= 3){
			adminFunctions();
		}
		if(isset($Pivot_Vars['menu']) && $Pivot_Vars['menu']=='admin'){
			require_once('pv_admin.php');
			startAdmin();
		}else{
			startNormal();
		}
	}
	SaveSettings();
}


function Setpaths(){
	global $Paths, $Cfg;

	list($path_from, $path_to) = find_path_difference();

	if (isset($_SERVER['PATH_INFO']) && ($_SERVER['PATH_INFO'] !="") ) {
		$current_path = $_SERVER['PATH_INFO'];
	} else if (isset($_SERVER['PHP_SELF']) && ($_SERVER['PHP_SELF'] !="") ) {
		$current_path = $_SERVER['PHP_SELF'];
	} else {
		$current_path = $_SERVER['SCRIPT_NAME'];
	}

	// if $path_from is just a slash it means we should just add $path_to
	// to the current directory
	if ($path_from == "/") {
	    $current_path = dirname($current_path)."/".$path_to."dummy";
	} else {
	    $current_path = preg_replace("#".$path_from."(.*)#i", $path_to."dummy", $current_path);
	}

	$Paths['pivot_url'] = dirname($current_path)."/";
	$Paths['pivot_url'] = str_replace("//", "/", $Paths['pivot_url']);

	if (strpos($Paths['pivot_url'], "php.exe") > 0) {
		$global_pref['pivot_url']= substr($global_pref['pivot_url'], strpos($global_pref['pivot_url'], "php.exe")+7);
	}

	// for now. log_url assumes '../'. Mark disapproves ;)
	$Paths['log_url']= dirname(dirname($current_path))."/";
	$Paths['log_url']= str_replace("\\", "", $Paths['log_url']);
	if (strpos($Paths['log_url'], "php.exe") > 0) {
		$Paths['log_url']= substr($Paths['log_url'], strpos($Paths['log_url'], "php.exe")+7);
	}

	if ($Paths['log_url']=="//")  { $Paths['log_url']="/"; }

	$Paths['host']="http://".$_SERVER['HTTP_HOST'];

	if(realpath(__FILE__)=="") {
		$Paths['pivot_path'] = str_replace('\\', '/', dirname(realpath($_SERVER['SCRIPT_FILENAME']))."/");
	} else {
		$Paths['pivot_path'] = str_replace('\\', '/', dirname(realpath(__FILE__))."/");
	}
	$Paths['extensions_path']  = fixpath( $Paths['pivot_path'] . '/../' . $Cfg['extensions_path'] );
	$Paths['extensions_url']  = fixpath( $Paths['pivot_url'] . '/../' . $Cfg['extensions_path'] );



}


// 2004/11/23 =*=*= JM - tentatives to eliminate the offset error...
function find_path_difference() {

    /* remove windows weirdness in paths */
    $path1 = str_replace( '\\','/',realpath( '.' ));
    $path2 = str_replace( '\\','/',dirname( realpath( __FILE__ )));

    $path1 = explode("/", $path1 );
    $path2 = explode("/", $path2 );

    if( $path1 == $path2 ) {
        return array( $path1, $path1);
    } else {

    	for($i=0; $i<max(count($path1), count($path2)) ; $i++) {
    		if (isset($path1[$i]) && isset($path2[$i]) && ($path1[$i] == $path2[$i]) ) {
    			$max = $i;
    		}
    	}

    	// Element $max in $path1 and $path2 is the same (and that element
		// might not be a part of the url, but rather of the path). Increase
		// $max by one if possible.
		$max = min($max+1, count($path1), count($path2));

    	//debug ("max: $max");

    	$path1 = implode("/", array_slice($path1,$max))."/";
    	$path2 = implode("/", array_slice($path2,$max))."/";

        //debug("pathdiff:  $path1, $path2");

        return array( $path1,$path2 );
    }
}


function gethost() {
	global $Weblogs, $Paths, $Current_weblog;

	if (strlen($Weblogs[$Current_weblog]['siteurl'])>3) {
		$host = $Weblogs[$Current_weblog]['siteurl'];
		//$host = preg_replace("/^http:\/\//i", "", $host);
		$host = preg_replace("/\/$/i", "", $host);
	} else {
		$host = $Paths['host'];
	}
	return $host;

}

function siteurl_isset() {
	global $Weblogs, $Paths, $Current_weblog;

	return (strlen($Weblogs[$Current_weblog]['siteurl'])>3);

}


function MinLevel($min) {
	global $Users, $Pivot_Vars;
	if($Users[$Pivot_Vars['user']]['userlevel'] < $min){
		piv_error(lang('minlevel'), 1);
	}
}


function startNormal() {
	global $Pivot_Vars, $mainInternal;

	// bob notes: Tweaked this a bit. I think this now does what
	// i think it's supposed to do.

	$func = $mainInternal[$Pivot_Vars['func']];
	$func2 = $mainInternal[$Pivot_Vars['menu']];

	if ( isset($Pivot_Vars['func']) && isset($func) && (function_exists($func)) ) {
		$func();
	} else if (isset($Pivot_Vars['menu']) &&  (isset($func2)) && (function_exists($func2)) ) {
		$func2();
	} else {
		main_screen();
	}
}

function Login($failed=0, $reason=0, $reason_desc="") {
	global $Pivot_Vars, $build, $Cfg;
	$cookie = 0;

	setcookie('user', '', -9999);
	setcookie('pass', '', -9999);
	setcookie('mode', 'nothing', -9999);

	if($failed == 1) {
		$failed = lang('login','retry');
	}
	if($Pivot_Vars['user']) {
		$uservar = $Pivot_Vars['user'];
	}

	PageHeader(lang('login','title'), 0);
	if(isset($Cfg['bn_' . $_SERVER['REMOTE_ADDR']]) && (abs($Cfg['bn_' . $_SERVER['REMOTE_ADDR']] - time()) >= 60*60*12)){
		unset($Cfg['fl_' . $_SERVER['REMOTE_ADDR']]);
		unset($Cfg['bn_' . $_SERVER['REMOTE_ADDR']]);
	}

	if($Cfg['fl_' . $_SERVER['REMOTE_ADDR']] >= 10 && ($Cfg['bn_' . $_SERVER['REMOTE_ADDR']] - time() < 60*60*12)){
		Paragraph(lang('login', 'banned'));
		if(!isset($Cfg['bn_' . $_SERVER['REMOTE_ADDR']])){
			$Cfg['bn_' . $_SERVER['REMOTE_ADDR']] = time();
		}
	}else{

		echo "<div style='padding: 20px;'>";
		StartForm('login');
		StartTable();
		GenSetting('header', lang('login','title'). " &raquo; ".$build,  '', 8);
		GenSetting('user', lang('login','name'), '', 0, $uservar);
		GenSetting('pass', lang('login','pass'), $failed, 1, $passvar);
		GenSetting('remember', lang('login','remember'), '', 3, array( lang('login','rchoice','2'), 'stayloggedin', lang('login','rchoice','0'), 'nothing'), '', $_COOKIE['mode']);
		EndForm(lang('login','title'), 1);

	}

	if ($reason > 0) {
		Debug("logged out, because of reason #".$reason. ": ". $reason_desc);
		echo "<p>logged out because of <b>reason #".$reason. ": ". $reason_desc."</b></p>";
	}

	echo "<p>".lang('login','delete_cookies_desc')."<br />";
	$bookmarklet = "javascript:d=new Date();var ck = document.cookie.split(';');for(var i in ck) {document.cookie=ck[i].split('=')[0] + '=;EXPIRES=' + d.toUTCString();};location.href=document.URL;";
	printf("<a href=\"%s\">%s</a></p>", $bookmarklet,  lang('login','delete_cookies'));

	SaveSettings();
	echo "</div>";
	PageFooter();


	exit;
}




function CheckLogin() {
	global $Users, $Pivot_Vars, $Cfg;

	// User is banned..
	if(isset($Cfg['bn_' . $_SERVER['REMOTE_ADDR']])){
		Login(1, 1, "User is banned");
	}

	// added to not check for referers if no session id is given..
	if(!isset($Pivot_Vars['session'])){
		$uri = 'http://' . $Pivot_Vars['HTTP_HOST'] . $Pivot_Vars['SCRIPT_NAME'];
		if(strpos($Pivot_Vars['HTTP_REFERER'], $uri)!=0){
			$Pivot_Vars['user'] = '';
			Login(0, 2, "No session active.");
		}
	}

	// If we selected logout from the menu..
	if( isset($Pivot_Vars['func']) && ($Pivot_Vars['func'] == 'login') && isset($Pivot_Vars['do']) && ($Pivot_Vars['do'] == 'logout')){

		setcookie('user', '', -9999);
		setcookie('pass', '', -9999);
		setcookie('mode', 'nothing', -9999);
		unset($Users[$Cfg['tempsessions'][$Pivot_Vars['session']][0]]['session']);
		unset($Cfg['tempsessions'][$Pivot_Vars['session']]);

		SaveSettings();
		login(0,3, "User logged off");
	}

	// if the user has cookies set, but no session is active yet..
	if( isset($_COOKIE['user']) && isset($_COOKIE['hash']) && ($_COOKIE['mode'] == 'stayloggedin') &&
		( (!isset($Pivot_Vars['session'])) || ($Pivot_Vars['session'] == "")) ) {

		// Try to revive an old Session..
		ReviveSession();

	} else if($Pivot_Vars['func'] == 'login' || $Pivot_Vars['do'] == 'login') {

		// if we've just logged in, reset the cookies, if necesary and start a new session..

		if ( ($Users[$Pivot_Vars['user']]['pass'] == md5($Pivot_Vars['pass'])) && ($Users[$Pivot_Vars['user']]['userlevel']>0) ) {

			NewSession($Pivot_Vars['user']);

		}else{

			// add one to the failed login attempts.
			if(strlen($Pivot_Vars['user']) > 0) {
				$Cfg['fl_' . $_SERVER['REMOTE_ADDR']]++;
			}

			Login(1,4, "Incorrect username or password");

		}

	} else {
		// when running normally, the session stuff is updated.

		$Pivot_Vars['user'] = $Cfg['tempsessions'][$Pivot_Vars['session']][0];

		$ip = substr( $_SERVER['REMOTE_ADDR'], 0, strrpos( $_SERVER['REMOTE_ADDR'], "."));

		// calculated locally: user's pass + current session + ip we got from user
		$hash1 = md5( md5( $Users[$Pivot_Vars['user']]['pass'] . $Pivot_Vars['session'] ) . $ip ) ;

		// stored hash
		$hash2 = $Cfg['tempsessions'][$Pivot_Vars['session']][1];

		// we check if the two hash matches with the one that was stored
		if ($hash1 != $hash2) {

			//debug("hash 1" . $hash1 );
			//debug("hash 2" . $hash2 );

			// if this is the case, something's not ok, so go back to login..
			Login(0,0, "No hacking, please");

		}
	}

	// If by this point no session is set, we will show the login screen..
	if(strlen($Pivot_Vars['session']) == 0) {
		Login(0,8, "Please log on. (if you keep getting this message, delete the cookies for this site)");
	}

	// Update the timer, so we can keep the user logged in.
	if($Cfg['tempsessions'][$Pivot_Vars['session']][2] - time() <= ($Cfg['session_length'] / 4)) {
		$Cfg['tempsessions'][$Pivot_Vars['session']][2] = $Cfg['tempsessions'][$Pivot_Vars['session']][2] + $Cfg['session_length'];

		/* REDUNDANT
		if($Pivot_Vars['remember'] == 'stayloggedin'){
			setcookie('user', $Pivot_Vars['user'], time()+$Cfg['cookie_length']);
		} */
	}


}

function NewSession($user) {
	global $Users, $Cfg, $Pivot_Vars;

	unset($Cfg['fl_' . $_SERVER['REMOTE_ADDR']]);
	unset($Cfg['bn_' . $_SERVER['REMOTE_ADDR']]);

	if(strlen($user) == 0) { return 0; }

	$sid="";
	for ($i = 1 ; $i <= 12; $i++) {
		$rchar = mt_rand(1,30);
		if($rchar <= 10) {
			$sid .= chr(mt_rand(65,90));
		}elseif($rchar <= 20) {
			$sid .= mt_rand(0,9);
		}else{
			$sid .= chr(mt_rand(97,122));
		}
	}
	//now we have a 12char string (ie - hard to guess) for the session identifier.  go us.

	// make the parts for the hash.
	$hash[1] = md5($Pivot_Vars['pass']);
	$hash[2] = $sid;
	$hash[3] = substr( $_SERVER['REMOTE_ADDR'], 0, strrpos( $_SERVER['REMOTE_ADDR'], "."));

	// make sure cookie length is set
	if ($Cfg['cookie_length']==0) {
		$Cfg['cookie_length'] = 1814400;
	}

	if($Pivot_Vars['remember'] == 'stayloggedin'){

		setcookie('hash', md5( $hash[1].$hash[2] ), time()+$Cfg['cookie_length']);
		setcookie('mode', $Pivot_Vars['remember'], time()+$Cfg['cookie_length']);
 		setcookie('user', $Pivot_Vars['user'], time()+$Cfg['cookie_length']);

	}

	if($Pivot_Vars['remember'] == 'nothing'){

		setcookie('user', '', '0');
		setcookie('hash', '', '0');
		setcookie('mode', $Pivot_Vars['remember'], '0');

	}

	//kill the old session.
	unset($Cfg['tempsessions'][$Users[$user]['session']]);

	//$Users[$user]['session'] = $sid;
	$Users[$user]['lastlogin'] = time();

	$Pivot_Vars['session'] = $sid;
	$Pivot_Vars['user'] = $user;

	// Make the new session.
	$Cfg['tempsessions'][$sid] = array(
		$user,
		md5( md5($hash[1].$hash[2]) . $hash[3]),
		time()+$Cfg['session_length']
	);





}


function ReviveSession() {
	global $Cfg, $Users, $Pivot_Vars;

	if (is_array($Cfg['tempsessions'])) {
		foreach( $Cfg['tempsessions'] as $sess_key => $sess_data) {

			if ($sess_data[0] == $_COOKIE['user']) {

				$ip = substr( $_SERVER['REMOTE_ADDR'], 0, strrpos( $_SERVER['REMOTE_ADDR'], "."));
				$hash = md5( $_COOKIE['hash'] . $ip);

				if ($hash == $sess_data[1]) {
					$Pivot_Vars['user'] = $sess_data[0];
					$Pivot_Vars['session'] = $sess_key;
					$Users[ $Pivot_Vars['user'] ]['lastlogin'] = time();
					return "";
				}
			}
		}
	}
}


// CHANGES: PAUL 30-3-2003
function files_main($image='') {
	global $Cfg, $Pivot_Vars;

	$show = 24;

	MinLevel(2);
	$path = '../' . $Cfg['upload_path'];
	PageHeader(lang('userbar','files'),1);
	$ankeiler= (lang('userbar','files').' &raquo; '.lang('userbar','files_title'));

	// if there is an 'action' to do
	if (isset($Pivot_Vars['doaction'])) {
		files_action($Pivot_Vars['action'], $Pivot_Vars['check']);
	}

	if(isset($Pivot_Vars['preview']) && $Pivot_Vars['preview'] == 'true') {
		$myurl = sprintf("index.php?session=%s&amp;menu=files&amp;preview=false", $Pivot_Vars['session']);
		PageAnkeiler($ankeiler, '&raquo; '. lang('upload', 'preview'), $myurl);
	} else {
		$myurl = sprintf("index.php?session=%s&amp;menu=files&amp;preview=true", $Pivot_Vars['session']);
		PageAnkeiler($ankeiler, '&raquo; '. lang('upload', 'thumbs'), $myurl);
	}

	echo "<scr"."ipt language='JavaScript' type='text/javascript'>\nfun"."ction changePage(newLoc)\n{\nnextPage = newLoc.options[newLoc.selectedIndex].value;\nif (nextPage != '') { document.location.href = nextPage; } }</scr"."ipt>";



	list($fileArray, $thumbArray) = getFileList();

	$count = count($fileArray);


	$loop= 0;
	do {
		$mystart = substr(strtolower(urldecode($fileArray[$loop]['name'])),0,18);
		if (isset($fileArray[($loop + $show -1)])) {
			$mystop = substr(strtolower(urldecode($fileArray[($loop + $show -1)]['name'])),0,18);
		} else {
			$mystop = "zzz";
		}
		$url = "index.php?session=".$Pivot_Vars['session']."&amp;menu=files&amp;slice=$loop&amp;preview=".$Pivot_Vars['preview'];
		$slice_arr[]="<option value=\"$url\">$mystart - $mystop</option>";
		$loop = $loop + $show;

	} while ($loop < $count);

	if (count($slice_arr)>1) {
		echo "<form name='form1' action=''>";
		echo "<select name='selectedPage' onchange='changePage(this.form.selectedPage)'><option value='#'>Jump to: </option>";
		echo implode("\n", $slice_arr);
		echo "</select></form><hr size='1' noshade='noshade' />";
	}

	if (isset($Pivot_Vars['slice'])) {
		$slice = $Pivot_Vars['slice'];
	} else {
		$slice = 0;
	}

	$fileArray = array_slice($fileArray, $Pivot_Vars['slice'], $show);



	// Here we decide to show the files as a
	// list or as thumbnail preview
	if(isset($Pivot_Vars['preview']) && $Pivot_Vars['preview'] == 'true') {
		// This is the _thumbnail_ preview
		// not to be mistaken with a original preview
		// that would take too much download time
		show_image_preview($fileArray,$thumbArray);
	} else {
		show_image_list($fileArray,$thumbArray);
	}

}


function uploadfile() {
	global $Cfg, $Pivot_Vars, $qual, $local;
	//Modified upload function
	//11-04-2003
	//Sander Bijl <sander@geenzorg.org>

	MinLevel(2);
	$path = '../'.$Cfg['upload_path'];
	include_once('includes/fileupload-class.php');
	$my_uploader = new uploader;

	// OPTIONAL: set the max filesize of uploadable files in bytes
	$my_uploader->max_filesize($Cfg['max_filesize']);

	// UPLOAD the file
	if ($my_uploader->upload('userfile', $Cfg['upload_accept'], $Cfg['upload_extension'])) {
		debug($my_uploader->file['file']);
		$success = $my_uploader->save_file($path, $Cfg['upload_save_mode'], 1);
	}


	if ($success) {

		error_reporting(E_ALL);

		include_once("modules/module_imagefunctions.php");

		printf('<script language="javascript" type="text/javascript">function pop(a){window.open("modules/module_image.php?image="+a, "thumb", "toolbar=no,resizable=yes,scrollbars=yes,width=460,height=490"); 		self.location="index.php?menu=files"; }</script>');

		PageHeader(lang('userbar','main'), 1);
		PageAnkeiler(lang('userbar','files') . ' &raquo; ' . lang('userbar','uploaded_success'));

		echo '<tr><td align="center" colspan="2">';
		$fullentry = sprintf('../%s%s',$Cfg['upload_path'],$my_uploader->file['name']);
		echo '<img src="' . $fullentry. '" border="0" alt="new image">';
		echo '</td></tr><tr><td align="right" width="48%"><br /><br />';


		if (auto_thumbnail($my_uploader->file['name'])) {
			echo "<p><b>Thumbnail:</b><br>";
			$thumbfilename = ( "../" . $Cfg['upload_path'] .  make_thumbname(basename($my_uploader->file['name'])));
			printf('<p><img src="%s" />', $thumbfilename);
			printf('<p><a href="javascript:pop(\'%s\')">' . lang('upload', 'edit_thumbnail') . '</a></td>',$my_uploader->file['name']);
		} else {
			printf('<p><a href="javascript:pop(\'%s\');">' . lang('upload', 'create_thumb') . '</a></td>',$my_uploader->file['name']);

		}


		GenSetting('' ,lang('upload','thisfile'),'',8,'',6);
		StartForm('file_upload', 0, 'enctype="multipart/form-data"');
		printf('<input name="%s" type="file"  class="input"><br />',$Cfg['upload_file_name']);
		printf('<input type="submit" value="%s" class="button" /></form>',lang('upload','button'));

		PageFooter();

	} else {
		if($my_uploader->errors) {
			files_main($my_uploader->errors);
		}
	}

}

function deletefile() {
	global $Pivot_Vars, $Cfg;
	MinLevel(2);

	if($Pivot_Vars['confirmed']!=1){
		PageHeader(lang('upload','delete_title'), 1);
		StartTable(lang('upload','picheader'), 2);
		echo '<tr><td align="center" colspan="2">';
		echo '<img src="' . $Cfg['siteurl'] . $Cfg['upload_path'] . $Pivot_Vars['del']. '" border="0" alt="new image">';
		echo '</td></tr><tr><td align="right" width="48%">';

		StartForm('file_delete');
		echo '<input type="hidden" name="del" value="'.$Pivot_Vars['del'].'" />';
		echo '<input type="hidden" name="confirmed" value="1" />';
		echo '<input type="submit" class="button" value="&nbsp;&nbsp;'.lang('yes').'&nbsp;&nbsp;" /></form></td>';
		echo '<td align="left">';
		StartForm('files');
		echo '<input type="submit" class="button" value="&nbsp;&nbsp;'.lang('no').'&nbsp;&nbsp;" /></form></td>';
		echo '</tr></table><br />';
		PageFooter();

		// everynow and then it needs to be custom done, sorry to
		// anyone that wants to change pvdisp.php :-\
	}else{
		if(!@unlink('../'.$Cfg['upload_path'].$Pivot_Vars['del'])){
			files_main(array('could not delete ../' . $Cfg['upload_path'] . $Pivot_Vars['del']));
		}else{
			if(file_exists('../'.$Cfg['upload_path'].$Pivot_Vars['del'])){
				files_main(array('could not delete ../' . $Cfg['upload_path'] . $Pivot_Vars['del']));
				//no idea why this would, much less could happen, but whatever
			}else{
				files_main(array('deleted '. $Pivot_Vars['del'] .' with no problems'));
			}
		}
	}
}



function main_screen() {
	global $Cfg, $build, $Users, $Pivot_Vars, $db, $Paths;

	$db = new db();

	PageHeader($Cfg['sitename'], 1);
	PageAnkeiler(lang('userbar','main_title'));

	$welcome = lang('general', 'welcome');
	$welcome = str_replace('%build%', $build, $welcome);


	// check to see if there are any 'timed publish' items that need to be
	// published..
	timedpublishcheck();

	echo "<p><b>".$welcome."</b></p>";

	// the 'main menu'..

	$main_funcs = array(
	array(lang('userbar','submit'), 'new_entry', lang('userbar','submit_title'), 'new_entry'),
	array(lang('userbar','entries'), 'entries', lang('userbar','entries_title'), 'entries'),
	array(lang('userbar','u_settings'), 'userinfo', lang('userbar','u_settings_title'), 'userinfo'),
	array(lang('userbar','files'), 'files', lang('userbar','files_title'), 'files'),
	);

	if ($Users[$Pivot_Vars['user']]['userlevel'] >= 3) {
		$main_funcs[] = array(lang('userbar','admin'), 'admin', lang('userbar','admin_title'), 'admin');
	}

	DispPage($main_funcs, 'overview');





	echo "\n\n<div style='position: absolute; right: 20px; top: 55px; border: 0px solid #F00;'>\n\n";

	// Load the latest news from pivotlog.net/notifier.xml
	define('MAGPIE_DIR', realpath('.').'/includes/magpierss/');
	define('MAGPIE_CACHE_DIR', './db/rsscache/');
	define('MAGPIE_FETCH_TIME_OUT', 5);	// 5 second timeout
	define('MAGPIE_CACHE_AGE', 60*60*8); // 8 hours
	require_once(MAGPIE_DIR.'rss_fetch.inc');
	if (isset($Cfg['notifier'])) {
		$feed_url = $Cfg['notifier'];
	} else {
		$feed_url = "http://www.pivotlog.net/notifier.xml";
	}
	$feed = fetch_rss($feed_url);


	// show the 'remove setup'..
	if(file_exists('../pivot-setup-safemode.php') || file_exists('../pivot-setup.php')) {
		// show the 'latest news'.
		printf("<table cellspacing='0' class='tabular_border' border='0' width='320'><tr class='tabular_nav'>");
		printf("<td colspan='4' class='tabular-lastheader'>%s:</td></tr>", lang("adminbar", "remove_setup_header"));
		printf("<tr class='tabular_line_even'><td class='tabular-small' style='white-space: normal;'>%s</td>", lang("adminbar", "remove_setup"));
		echo '</tr>';
		echo '</table><br />';

	}

	// show the 'latest news'.
	printf("<table cellspacing='0' class='tabular_border' border='0' width='320'><tr class='tabular_nav'>");
	printf("<td colspan='4' class='tabular-lastheader'>%s (%s)</td></tr>",
		lang("adminbar", "latest_pivot_news"),
		date("d/m/Y",strtotime(substr(str_replace("T", " ",$feed->items[0]['issued']),0,10)))
		);
	printf("<tr class='tabular_line_even'><td class='tabular-small' style='white-space: normal;'> %s</td>",
		$feed->items[0]['atom_content']);
	echo '</tr>';
	echo '</table><br />';



	// show the last 8 comments.
	last_comments_overview();


	// show the last 8 entries
	$overview_arr = array_reverse( $db->getlist(-6,0,"","", TRUE, ''));
	printf('<table cellspacing="0" class="tabular_border" border="0" width="320"><tr class="tabular_nav">');
	printf('<td colspan="5" class="tabular-lastheader">' . lang('userbar','recent_entries') . '</td></tr>', $prevlink);


	foreach ($overview_arr as $overview_line) {
		print_row_overview($overview_line);
	}

	echo '</table><br />';

	echo "</div>";





	//echo "</td></tr></table>\n\n";

	PageFooter();

}



function entries_screen($message="") {
	global $config_array, $Pivot_Vars, $Cfg, $absmax;
	PageHeader(lang('userbar','entries'), 1);
	PageAnkeiler(lang('userbar','entries') . ' &raquo; ' . lang('userbar','entries_title'));

	// display a message, if there is one..
	if ($message!="") {
		echo "<p><b>$message</b></p>";
	}

	serialize_uncache("ALL");

	// if there is an 'action' to do
	if ( (isset($Pivot_Vars['doaction'])) && ($Pivot_Vars['action']!="") ) {

		if ( ($Pivot_Vars['action']=="delete") && ($Pivot_Vars['confirmed']!=1) ) {
			$vars = array(
			"action", $Pivot_Vars['action'],
			"check", serialize($Pivot_Vars['check']),
			"doaction", "1"
			);
			if (count($Pivot_Vars['check'])==1) {
				ConfirmPage("hmm ho hum", $vars,  lang('entries' , 'delete_one_confirm') );
			} else {
				ConfirmPage("hmm ho hum", $vars,  lang('entries' , 'delete_multiple_confirm') );
			}
		}

		entries_action($Pivot_Vars['action'], $Pivot_Vars['check']);
	}


	$db = new db();

	if (!isset($Cfg['overview_entriesperpage'])) { $Cfg['overview_entriesperpage'] = 20; }

	$absmax = $db->get_entries_count();
	$show = (isset($Pivot_Vars['show'])) ? $Pivot_Vars['show'] : $Cfg['overview_entriesperpage'] ;
	$offset = (isset($Pivot_Vars['offset'])) ? $Pivot_Vars['offset'] : 0 ;


	if (isset($Pivot_Vars['first'])) { $offset=$absmax-$show; }

	$myurl =sprintf("index.php?session=%s&amp;menu=entries", $Pivot_Vars['session']);

	//Sort entries change
	//set initial values for sort values
	$entry_sort = ""; //goes in query string
	$sort = "date";   //goes in the getlist method call
	if(isset($Pivot_Vars['sort'])) {
		$entry_sort = "&amp;sort=".$Pivot_Vars['sort'];
		$sort = $Pivot_Vars['sort'];
	}

	if (isset($Pivot_Vars['filtercat'])) {

		$overview_arr = $db->getlist(-$show,$offset,"", array($Pivot_Vars['filtercat']), FALSE, $sort);
		$filter = "&amp;filtercat=".$Pivot_Vars['filtercat'];
		$filtertitle = str_replace('%name%', $Pivot_Vars['filtercat'], lang('entries', 'filteron') );

	} else if (isset($Pivot_Vars['filteruser'])) {

		$overview_arr = $db->getlist(-$show,$offset, $Pivot_Vars['filteruser'] , "", FALSE, $sort);
		$filter = "&amp;filteruser=".$Pivot_Vars['filteruser'];
		$filtertitle = str_replace('%name%', $Pivot_Vars['filteruser'], lang('entries', 'filteron') );

	} else if ( (isset($Pivot_Vars['search'])) && (strlen($Pivot_Vars['search'])>1) ) {

		include_once("modules/module_search.php");
		$overview_arr = search_entries($Pivot_Vars['search']);
		$filtertitle = str_replace('%name%', '&hellip;', lang('entries', 'filteron') );
		$offset =  0;
		$absmax = $show = 1;

	} else {

		$overview_arr = $db->getlist(-$show,$offset,"", "", FALSE, $sort);
		$filter = "";
		$filtertitle = str_replace('%name%', '&hellip;', lang('entries', 'filteron') );

	}


	if ($offset<($absmax-$show)) {
		$prev=$offset+$show;
		$prevlink=sprintf('<a href="%s&amp;offset=%s&amp;show=%s%s%s">&laquo; '. lang('entries', 'first') .'</a>&nbsp;&nbsp;', $myurl, ($absmax-$show), $show, $filter, $entry_sort);
		$prevlink.=sprintf('<a href="%s&amp;offset=%s&amp;show=%s%s%s">&lsaquo; '. lang('entries', 'previous') .' %s</a>', $myurl, $prev, $show, $filter, $entry_sort, $show);
	} else {
		$prevlink="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	if ($offset>0) {
		$next=max(0, $offset-$show);
		$nextlink=sprintf('<a href="%s&amp;offset=%s&amp;show=%s%s">&rsaquo; '. lang('entries', 'next') .' %s</a>&nbsp;&nbsp;', $myurl, $next, $show, $filter, $entry_sort, $show);
		$nextlink.=sprintf('<a href="%s&amp;show=%s%s">&raquo; '. lang('entries', 'last') .'</a>', $myurl, $show, $filter, $entry_sort);
	} else {
		$nextlink="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	// make the html for the paginator..
	$numofpages = (int)ceil(($absmax / abs($show)));
	if ($numofpages > 1) {
		for($i = 0; $i < $numofpages; $i++) {
			$init = $i * abs($show) ;
			$pages_arr[] = sprintf("<option value=\"%s%s&amp;show=%s%s&amp;offset=%s\">%s</option>", 	$myurl, $entry_sort, abs($show), $filter, $init, $i+1);
		}

		$title = str_replace('%num%', ceil($offset / abs($show))+1, lang('entries', 'jumptopage') );
		$pages = "<select name='selectedPage' onchange='changePage(this.form.selectedPage)' 	class='input'>";
		$pages .= sprintf("<option value='' selected='selected'>%s</option>", $title  );
		$pages .= implode ("\n", $pages_arr) ;
		$pages .= "</select>";
	}

	// make the HTML for the filter box
	if ((isset($Pivot_Vars['filtercat'])) || (isset($Pivot_Vars['filteruser'])) ) {
		$pages_arr = array( sprintf("<option value=\"%s%s&amp;show=%s\">%s</option>", $myurl, $entry_sort, abs($show), lang('entries', 'filteroff')) );
	} else {
		$pages_arr = array();
	}
	$cats = cfg_cats();

	$pages_arr[] = "<option value=''>".lang('entries', 'category')."</option>";
	foreach ($cats as $cat) {
		$pages_arr[] = sprintf("<option value=\"%s%s&amp;show=%s&amp;filtercat=%s\"> - %s</option>", $myurl, $entry_sort, abs($show), $cat['name'], $cat['name']);
	}

	$users = explode("|", $Cfg['users']);
	$pages_arr[] = "<option value=''>".lang('entries', 'author')."</option>";
	foreach ($users as $user) {
		$pages_arr[] = sprintf("<option value=\"%s%s&amp;show=%s&amp;filteruser=%s\"> - %s</option>", $myurl, $entry_sort, abs($show), $user, $user);
	}


	$pages .= "<select name='selectedFilter' onchange='changePage(this.form.selectedFilter)' class='input'>";
	$pages .= sprintf("<option value='' selected='selected'>%s</option>", $filtertitle );
	$pages .= implode ("\n", $pages_arr) ;
	$pages .= "</select>";

	$searchval = (isset($Pivot_Vars['search'])) ? $Pivot_Vars['search'] : 'search';

	$pages .= "<input type='text' name='search' value='".$searchval."' class='input' style='padding: 2px; height: 19px; width: 90px;' onfocus='this.select();' />";

	// Some JS for the paginator and filter menus
	echo "<scr"."ipt language='JavaScript' type='text/JavaScript'>\nfun"."ction changePage(newLoc)\n{\nnextPage = newLoc.options[newLoc.selectedIndex].value;\nif (nextPage != '') { document.location.href = nextPage; } }</scr"."ipt>";

	printf("<form name='form1' method='post' action='%s&amp;doaction=1'>\n<table cellspacing='0' class='tabular_border' border='0'>\n", $myurl);
	echo "<tr class='tabular_nav'><td colspan='8'>\n";

	echo '<table cellspacing="0" cellpadding="0" class="tabular_border" style="border:0px;" border="0" width="100%"><tr>';
	printf('<td>%s&nbsp;</td>', $prevlink);
	printf('<td align="center">%s</td>', $pages);
	printf('<td align="right" class="tabular_nav">&nbsp;%s</td></tr></table>', $nextlink);

	echo "\n</td></tr><tr class='tabular_header'><td>&nbsp;</td>";
	echo '<td><a href="'.$myurl.'&amp;sort=status">'. lang('entries', 'status') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=title">'. lang('entries', 'title') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=category">'. lang('entries', 'category') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=author">'. lang('entries', 'author') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=date">'. lang('entries', 'date') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=comm">'. lang('entries', 'comm') .'</a></td>';
	echo '<td><a href="'.$myurl.'&amp;sort=track">'. lang('entries', 'track') .'</a></td>';
	echo '</tr>';
	//End Sort Entry Changes

	foreach ($overview_arr as $overview_line) {
		print_row($overview_line);
	}


	echo '<tr class="tabular_header"><td colspan="8"><img src="pics/arrow_ltr.gif" width="29" height="14" border="0" alt="" />';
	echo '<a href="#" onclick=\'setCheckboxes("form1", true); return false;\'>'. lang('forms', 'c_all') .'</a> / ';
	echo '<a href="#" onclick=\'setCheckboxes("form1", false); return false;\'>'. lang('forms', 'c_none') .'</a>';
	echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'. lang('forms', 'with_checked_entries');
	echo '<select name="action" class="input">
	<option value="" selected="selected">'. lang('forms', 'choose') .'</option>
	<option value="publish">'. lang('forms', 'publish') .'</option>
	<option value="hold" >'. lang('forms', 'hold') .'</option>
	<option value="delete">'. lang('forms', 'delete') .'</option>
	<option value="generate">'. lang('forms', 'generate') .'</option>
			</select>';

	echo '&nbsp;&nbsp;<input type="submit" value="'. lang('go') .'" class="button" /></td></tr>';


	echo '</table></form>';



	PageFooter();
}


function newentry_screen() {
	global $config_array, $Pivot_Vars, $entry, $Cfg, $useWysiwyg, $Users, $Paths;

	PageHeader(lang('userbar','entries'), 1);
	PageAnkeiler(lang('userbar','entries') . ' &raquo; ' . lang('userbar','submit_title'),  '&raquo; ' . lang('general', 'extended_view'));

	include_once "includes/edit_new.php";

	PageFooter();
}




function modifyentry_screen() {
	global $config_array, $Pivot_Vars, $entry, $Cfg, $useWysiwyg, $db, $Users, $Paths;

	$useWysiwyg = TRUE;

	// load an entry
	if (isset($Pivot_Vars['id'])) {

		$db = new db();
		$entry = $db->read_entry($Pivot_Vars['id']);

		if (!isset($entry['publish_date'])) {
			$entry['publish_date'] = $entry['date'];
		}
	}


	// to allow same level users to edit eachother's posts, use:
	// 	} else if ($Users[$Pivot_Vars['user']]['userlevel'] >= $Users[$db->entry['user']]['userlevel'])  {


	if ($Pivot_Vars['user'] == $db->entry['user']) {
		// allowed to edit own entry
		MinLevel(1);
	} else if ($Users[$Pivot_Vars['user']]['userlevel'] > $Users[$db->entry['user']]['userlevel'])  {
		// someone who has a lower lever
		MinLevel($Users[$db->entry['user']]['userlevel']);
	} else {
		// allowed to edit other people's entries
		Minlevel(3);
	}

	PageHeader(lang('userbar','entries'), 1);
	$ankeiler= lang('userbar','entries') . ' &raquo; ' . lang('userbar','modify_title');
	$ankeiler= str_replace("%1" , '<i>"'.trimtext($entry['title'],25).'"</i>', $ankeiler);
	PageAnkeiler($ankeiler, '&raquo;' . lang('general', 'extended_view'));

	include_once "includes/edit_new.php";

	PageFooter();
}




function entrysubmit_screen() {
	global $db,  $config_array, $Pivot_Vars, $entry, $Cfg, $Users, $Paths, $Weblogs, $temp_entry, $filtered_words;

	$db = new db();

	$entry = get_entry_from_post();

	if ((!$entry['title']=="") || (!$entry['introduction']=="") || (!$entry['user']=="") ) {

		// in this part, we remove the entry from the categories in which
		// the current user is not allowed to post entries
		foreach ($entry['category'] as $my_cat) {
			$allowed = explode("|", $Cfg['cat-'. $my_cat]);
			if (in_array($Pivot_Vars['user'], $allowed)) {
				$allowed_cats[] = $my_cat;
			} else {
				$message .= '<br />';
				$message .= sprintf( lang( 'entries','entry_catnopost' ),$m_cat ) ;
				debug("niet in cat: ".$my_cat);
			}
		}

		$entry['category'] = $allowed_cats;

//		echo "<pre>";
//		print_r($entry);
//		echo "</pre>";
//
//		die();
		$db->set_entry($entry);

		if ($db->save_entry(TRUE)) {
			$message = sprintf( lang( 'entries','entry_saved_ok' ).$message,'<i>'.trimtext( $entry['title'],25 ).'</i>' );
		} else {
			$message = sprintf( lang( 'entries','entry_saved_ok' ),'<i>'.trimtext( $entry['title'],25 ).'</i>' );
		}


		// only trigger the ping if it's a new entry..
		if ( ($Pivot_Vars['f_code']=="") && ($Pivot_Vars['f_code_orig']=="") && ($entry['status']=="publish") ) {
			$ping=TRUE;
		} else {
			$ping=FALSE;
		}

		// if the global index as they are made var is set - can continue
		if( '1'==$Cfg['search_index'] ) {
			/*
			2004/10/16 =*=*= JM
			an entry should only be indexed if both are true:
			 - 'publish'==$entry['status']
			 - current date is at least equal to $entry['publish_date']
			I lie, there is another case...
			it is conceivable that this is a timed publish AND the time has come
			I will leave this to timed publish routines - if I can find them...
			-> pvLib ... it's flagged

			and of course, providing that there is at least one
			category where it would be indexed...

			something else that can't be tested... if the user changes a normal
			publish to a timed-publish, or puts on hold when it was previously
			normal. user should reindex in this case

			*/
			// check status and date
			if(( 'publish'==$entry['status'] )
				||(( 'timed'==$entry['status'] )&&( $entry['publish_date'] <= date( 'Y-m-d-H-i' )))) {
				// categories...
				if( can_search_cats( cfg_cat_nosearchindex(),$entry['category'] )) {
					include_once( 'modules/module_search.php' );
					update_index($db->entry);
					debug('update search index: '.$db->entry['code']);
				}
			}
		}

		// perhaps send a trackback ping.
		if ($Pivot_Vars['tb_url'] != "") {

			debug("tburl: " . $Pivot_Vars['tb_url']);
			require_once( 'includes/send_trackback.php' );
			$weblogs = find_weblogs_with_cat($db->entry['category']);

			if (isset($Weblogs[$weblogs[0]])) {

				// we use temp_entry to make sure the correct entry is used for making the filename
				$temp_entry= $entry;

				$my_url = $Paths['host'].make_filelink( $db->entry['code'],$weblogs[0],'' );


				$weblog_title = $Weblogs[$weblogs[0]]['name'];
				debug("TRACKBACK ping: $my_url");
				$message .= '<br />' ;
				$message .= sprintf( lang( 'entries','entry_ping_sent' ),$Pivot_Vars['tb_url'] );

				$tb_urls = explode("\n", $Pivot_Vars['tb_url']);

				// make the contents of what to send with the trackback..
				$tb_contents = parse_step4($entry['introduction']);

				if ($Pivot_Vars['convert_lb']==2) {
					$tb_contents = pivot_textile($tb_contents);
				} else if (($Pivot_Vars['convert_lb']==3) || ($Pivot_Vars['convert_lb']==4) )  {
					$tb_contents = pivot_markdown($tb_contents, $Pivot_Vars['convert_lb']);
				}

				$tb_contents = trimtext(strip_tags($tb_contents),255);

				foreach($tb_urls as $tb_url) {
					$tb_url = trim($tb_url);
					if(isurl($tb_url)) {
						trackback_send($Pivot_Vars['tb_url'], $my_url, $entry['title'], $weblog_title, $tb_contents);
					}
				}
			}
		}
	}


	generate_pages( $db->entry['code'],TRUE,TRUE,TRUE,$ping );
	entries_screen( $message );
}



function edit_comments($msg="") {
	global $Cfg, $Pivot_Vars, $Users;

	PageHeader(lang('userbar','comments'), 1);
	PageAnkeiler(lang('userbar','comments') . ' &raquo; ' . lang('userbar','comments_title'));

	$id = $Pivot_Vars['id'];

	$db = new db();

	// read entry if it's not in memory yet.
	$db->read_entry($id, true);




	if ($Pivot_Vars['user'] == $db->entry['user']) {
		// allowed to edit own comments
		MinLevel(2);
	} else {
		// allowed to edit comments on other people's entries
		Minlevel(3);
	}


	// print if there are no comments - and exit!
	if ( (!$db->entry['comments']) || (count($db->entry['comments'])<1) ) {
                echo "<p><B>".lang('notice', 'comment_none')."</b><br /><br /></p>";
                PageFooter();
                echo "<br /><br /><br /><br />";
                return;
	}

	// perhaps delete a comment.
	if (isset($Pivot_Vars['del'])) {

		$del_comm = $db->entry['comments'][ $Pivot_Vars['del'] ];

		//remove the comment from last_comments if it's in there..
		@$last_comms =	load_serialize("db/ser_lastcomm.php", true, true);
		if ($last_comms !== false && count($last_comms)>0) {
			foreach ($last_comms as $key => $last_comm) {
				if ( ($last_comm['code'] == $db->entry['code']) &&
				($last_comm['name'] == $del_comm['name']) &&
				($last_comm['date'] == $del_comm['date'])	){
					unset($last_comms[$key]);
					save_serialize("db/ser_lastcomm.php", $last_comms );
				}
			}
		}

		// *argh* evil hack to directly delete comments.. I should write a
		// proper wrapper
		unset ($db->entry['comments'][ $Pivot_Vars['del'] ]);
		unset ($db->db_lowlevel->entry['comments'][ $Pivot_Vars['del'] ]);

		$db->save_entry();

		$msg = lang('notice', 'comment_deleted');

	}

	// perhaps add an ip-block for single ip.
	if (isset($Pivot_Vars['blocksingle'])) {
		$msg = "Added block for IP ".$Pivot_Vars['blocksingle'];
		add_block($Pivot_Vars['blocksingle']);
	}

	// perhaps add an ip-block for single ip.
	if (isset($Pivot_Vars['blockrange'])) {
		$iprange = make_mask ($Pivot_Vars['blockrange']);
		$msg = "Added block for IP-range ".$iprange;
		add_block($iprange);
	}

	// perhaps remove an ip-block for single ip.
	if (isset($Pivot_Vars['unblocksingle'])) {
		$msg = "Removed block for IP ".$Pivot_Vars['unblocksingle'];
		rem_block($Pivot_Vars['unblocksingle']);
	}

	// perhaps remove an ip-block for single ip.
	if (isset($Pivot_Vars['unblockrange'])) {
		$iprange = make_mask ($Pivot_Vars['unblockrange']);
		$msg = "Removed block for IP-range ".$iprange;
		rem_block($iprange);
	}



	// print a message, if there is one.
	if ($msg!="") { echo "<p><B>$msg</b><br /><br /></p>"; }

	// show the edit form, to edit a comment..
	if (isset($Pivot_Vars['edit'])) {

		StartForm('submitcomment', 0);
		StartTable();

		$mycom = $db->entry['comments'][ $Pivot_Vars['edit'] ];

		$settings = array();
		$settings[] = array('heading', lang('weblog_config','shortentry_template'), '', 8, '', 2, '');
		$settings[] = array('name', lang('weblog_text','name'), '', 0, unentify($mycom['name']) , 30, '');
		$settings[] = array('id', '', '', 7, $id, '', '');
		$settings[] = array('count', '', '', 7, $Pivot_Vars['edit'] , '', '');
		$settings[] = array('email', lang('weblog_text','email'), '', 0, $mycom['email'] , 60, '');
		$settings[] = array('url', lang('weblog_text','url'), '', 0, $mycom['url'] , 60, '');
		$settings[] = array('registered', 'Registered', '', 0, $mycom['registered'] , 10, '');
		$settings[] = array('notify', 'Notify', '', 0, $mycom['notify'] , 10, '');
		$settings[] = array('ip', lang('weblog_text','ip'), '', 0, $mycom['ip'] , 30, '');
		$settings[] = array('date', lang('weblog_text','date'), '', 0, $mycom['date'] , 30, '');
		$settings[] = array('comment', lang('weblog_text','comment'), '', 5, $mycom['comment'], '60', 'rows=5');


		DisplaySettings($settings, 'blog_settings');
		EndForm(lang('weblog_config','save_comment'), 1);
	}



	// print out all the comments..
	foreach ($db->entry['comments'] as $key => $comment) {


		$myblock = block_type($comment['ip']);

		if ( ($myblock=="single") || ($myblock=="range") ) {
			$strike = "style='text-decoration: line-through;'";
		} else {
			$strike = "";
		}

		// strip stuff from lamers' comments..
		$comment['name'] = strip_tags($comment['name']);
		$comment['email'] = strip_tags($comment['email']);
		$comment['url'] = strip_tags($comment['url']);

		if ($comment['registered'] == 1) {
			$comment['name'] = "<span style='background-color: #FF9;'>" . $comment['name'] . "</span>";
		}

		printf("<table border=0 cellpadding=2 cellspacing=2 width='95%%' style='border-bottom:".
		" 2px solid #999;'><tr><td width='40%%' valign='top'>".
                lang('weblog_text','name').":&nbsp;<b %s>%s</b><br />",
		$strike,  stripslashes($comment['name']));

		if (strpos($comment['url'], "ttp://") < 1 ) {
			$comment['url']="http://".$comment['url'];
		}

		if (isurl($comment['url'])) {
			$comment['url'] = sprintf("<a href='%s' target='_blank' %s>%s</a>", $comment['url'], $strike, trimtext($comment['url'], 40) );
		}

		if (isemail($comment['email'])) {
			$comment['email'] = sprintf("<a href='mailto:%s' %s>%s</a>", $comment['email'], $strike, trimtext($comment['email'], 40) );
		}

		if ($comment['notify'] == 1) {
			$comment['email'] =  $comment['email'] . "(notify!)";
		}


		printf(lang('weblog_text','email').":&nbsp;%s<br />", $comment['email']);
		printf(lang('weblog_text','url').":&nbsp;%s<br />", $comment['url']);
		printf(lang('weblog_text','ip').":&nbsp;%s<br />", $comment['ip']);
		printf(lang('weblog_text','date').":&nbsp;%s<br />", $comment['date']);

		printf("<td valign='top'><span %s>%s</span></td>", $strike, nl2br(htmlspecialchars($comment['comment'])));

		// only show the option to edit and delete links if the user is an advanced user.
		if ($Users[$Pivot_Vars['user']]['userlevel']>=2) {

			$link=sprintf("index.php?session=%s&amp;menu=entries&amp;func=editcomments&amp;", $Pivot_Vars['session']);
			$editlink=sprintf("%sid=%s&amp;edit=%s", $link, $db->entry['code'], $key);
			$dellink=sprintf("%sid=%s&amp;del=%s", $link, $db->entry['code'], $key);

			printf("</tr><tr class='tabular_line_odd'><td><a href='%s'>%s</a> /", $editlink, lang('entries', 'edit_comment') );
			printf(" <a href='%s'>%s</a>&nbsp;&nbsp;", $dellink, lang('entries', 'delete_comment') );

		} else {
			printf("<td>&nbsp;</td>");
		}

		// only show the option to add or remove ip-blocks if the user is an administrator.
		if ($Users[$Pivot_Vars['user']]['userlevel']>=3) {

			if ($myblock=="none") {
				$blocktext1 = str_replace("%s", $comment['ip'], lang('entries', 'block_single'));
				$blocklink1 = sprintf("%sid=%s&blocksingle=%s", $link, $db->entry['code'], $comment['ip']);
				$blocktext2 = str_replace("%s", make_mask($comment['ip']), lang('entries', 'block_range'));
				$blocklink2 = sprintf("%sid=%s&blockrange=%s", $link, $db->entry['code'], $comment['ip']);

				printf("<td><a href='%s'>%s</a> / ", $blocklink1, $blocktext1);
				printf("<a href='%s'>%s</a></td>", $blocklink2, $blocktext2);
			} else if ($myblock=="single") {
				$blocktext1 = str_replace("%s", $comment['ip'], lang('entries', 'unblock_single'));
				$blocklink1 = sprintf("%sid=%s&unblocksingle=%s", $link, $db->entry['code'], $comment['ip']);
				printf("<td><a href='%s'>%s</a></td>", $blocklink1, $blocktext1);
			} else {
				$blocktext1 = str_replace("%s", make_mask($comment['ip']), lang('entries', 'unblock_range'));
				$blocklink1 = sprintf("%sid=%s&unblockrange=%s", $link, $db->entry['code'], $comment['ip']);
				printf("<td><a href='%s'>%s</a></td>", $blocklink1, $blocktext1);
			}

		} else {
			printf("<td>&nbsp;</td>");
		}

		printf("</td></tr></table><br />");
	} // end of printing comments


	PageFooter();

	echo "<br /><br /><br /><br />";
}


function submit_comment() {
	global $Cfg, $Pivot_Vars;

	$mycomm = array(
	'name' => entify(stripslashes($Pivot_Vars['name'])),
	'email' => $Pivot_Vars['email'],
	'url' => $Pivot_Vars['url'],
	'ip' => $Pivot_Vars['ip'],
	'registered' => $Pivot_Vars['registered'],
	'notify' => $Pivot_Vars['notify'],
	'date' => $Pivot_Vars['date'],
	'comment' => entify(stripslashes($Pivot_Vars['comment']))
	);



	$db = new db();
	$entry = $db->read_entry( $Pivot_Vars['id']);

	$entry['comments'][ $Pivot_Vars['count'] ] = $mycomm;

	$db->set_entry($entry);
	$db->save_entry();

	// remove it from cache, to make sure the laters one is used.
	$db->unread_entry($entry['code']);

	$msg = lang('notice', 'comment_saved');

	edit_comments($msg);



}

function edit_trackbacks($msg="") {
	global $Cfg, $Pivot_Vars, $Users;

	PageHeader(lang('userbar','trackbacks'), 1);
	PageAnkeiler(lang('userbar','trackbacks') . ' &raquo; ' .  lang('userbar','trackbacks_title'));
	$id = $Pivot_Vars['id'];

	$db = new db();

	// read entry if it's not in memory yet.
	$db->read_entry($id, true);




	if ($Pivot_Vars['user'] == $db->entry['user']) {
		// allowed to edit own trackbacks
		MinLevel(2);
	} else {
		// allowed to edit trackbacks on other people's entries
		Minlevel(3);
	}


	// print if there are no trackbacks - and exit!
	if ( (!$db->entry['trackbacks']) || (count($db->entry['trackbacks'])<1) ) {
                echo "<p><B>".lang('notice', 'trackback_none')."</b><br /><br /></p>";
                PageFooter();
                echo "<br /><br /><br /><br />";
                return;
	}

	// perhaps delete a trackback.
	if (isset($Pivot_Vars['del'])) {

		$del_track = $db->entry['trackbacks'][ $Pivot_Vars['del'] ];

		//remove the trackback from last_trackbacks if it's in there..
		@$last_tracks =	load_serialize("db/ser_lasttrack.php", true, true);
		if ($last_tracks !== false && count($last_tracks)>0) {
			foreach ($last_tracks as $key => $last_track) {
				if ( ($last_track['code'] == $db->entry['code']) &&
				($last_track['name'] == $del_track['name']) &&
				($last_track['date'] == $del_track['date'])	){
					unset($last_tracks[$key]);
					save_serialize("db/ser_lasttrack.php", $last_tracks );
				}
			}
		}

		// *argh* evil hack to directly delete trackbacks.. I should write a
		// proper wrapper
		unset ($db->entry['trackbacks'][ $Pivot_Vars['del'] ]);
		unset ($db->db_lowlevel->entry['trackbacks'][ $Pivot_Vars['del'] ]);

		$db->save_entry();

		$msg = lang('notice', 'trackback_deleted');

	}
/* Removed since we don't store the IP for trackbacks (yet) - FIXME
	// perhaps add an ip-block for single ip.
	if (isset($Pivot_Vars['blocksingle'])) {
		$msg = "Added block for IP ".$Pivot_Vars['blocksingle'];
		add_block($Pivot_Vars['blocksingle']);
	}

	// perhaps add an ip-block for single ip.
	if (isset($Pivot_Vars['blockrange'])) {
		$iprange = make_mask ($Pivot_Vars['blockrange']);
		$msg = "Added block for IP-range ".$iprange;
		add_block($iprange);
	}

	// perhaps remove an ip-block for single ip.
	if (isset($Pivot_Vars['unblocksingle'])) {
		$msg = "Removed block for IP ".$Pivot_Vars['unblocksingle'];
		rem_block($Pivot_Vars['unblocksingle']);
	}

	// perhaps remove an ip-block for single ip.
	if (isset($Pivot_Vars['unblockrange'])) {
		$iprange = make_mask ($Pivot_Vars['unblockrange']);
		$msg = "Removed block for IP-range ".$iprange;
		rem_block($iprange);
	}
*/


	// print a message, if there is one.
	if ($msg!="") { echo "<p><B>$msg</b><br /><br /></p>"; }

	// show the edit form, to edit a trackback..
	if (isset($Pivot_Vars['edit'])) {

		StartForm('submittrackback', 0);
		StartTable();

		$mytrack = $db->entry['trackbacks'][ $Pivot_Vars['edit'] ];

		$settings = array();
		$settings[] = array('heading', lang('weblog_config','shortentry_template'), '', 8, '', 2, '');
		$settings[] = array('id', '', '', 7, $id, '', '');
		$settings[] = array('count', '', '', 7, $Pivot_Vars['edit'] , '', '');
		$settings[] = array('name', lang('weblog_text','blog_name'), '', 0, unentify($mytrack['name']) , 60, '');
		$settings[] = array('title', lang('weblog_text','title'), '', 0, unentify($mytrack['title']) , 60, '');
		$settings[] = array('excerpt', lang('weblog_text','excerpt'), '', 5, unentify($mytrack['excerpt']), '60', 'rows=5');
		$settings[] = array('url', lang('weblog_text','url'), '', 0, $mytrack['url'] , 60, '');
		$settings[] = array('ip', lang('weblog_text','ip'), '', 0, $mytrack['ip'] , 30, '');
		$settings[] = array('date', lang('weblog_text','date'), '', 0, $mytrack['date'] , 30, '');


		DisplaySettings($settings, 'blog_settings');
		EndForm(lang('weblog_config','save_trackback'), 1);
	}



	// print out all the trackbacks..
	foreach ($db->entry['trackbacks'] as $key => $trackback) {


		$myblock = block_type($trackback['ip']);

		if ( ($myblock=="single") || ($myblock=="range") ) {
			$strike = "style='text-decoration: line-through;'";
		} else {
			$strike = "";
		}

		// strip stuff from lamers' trackbacks..
		$trackback['url'] = strip_tags($trackback['url']);

		printf("<table border=0 cellpadding=2 cellspacing=2 width='95%%' style='border-bottom:".
		" 2px solid #999;'><tr><td width='40%%' valign='top'>".
                lang('weblog_text','title').":&nbsp;<b %s>%s</b><br />",
		$strike,  stripslashes($trackback['title']));

/*
		if (strpos($trackback['url'], "ttp://") < 1 ) {
			$trackback['url']="http://".$trackback['url'];
		}

		if (isurl($trackback['url'])) {
			$trackback['url'] = sprintf("<a href='%s' target='_blank' %s>%s</a>", $trackback['url'], $strike, trimtext($trackback['url'], 40) );
		}
*/
		printf(lang('weblog_text','blog_name').":&nbsp;%s<br />", $trackback['name']);
		printf(lang('weblog_text','url').":&nbsp;%s<br />", $trackback['url']);
		printf(lang('weblog_text','ip').":&nbsp;%s<br />", $trackback['ip']);
		printf(lang('weblog_text','date').":&nbsp;%s<br />", $trackback['date']);

		printf("<td valign='top'><span %s>%s</span></td>", $strike, nl2br(htmlspecialchars($trackback['excerpt'])));

		// only show the option to edit and delete links if the user is an advanced user.
		if ($Users[$Pivot_Vars['user']]['userlevel']>=2) {

			$link=sprintf("index.php?session=%s&amp;menu=entries&amp;func=edittrackbacks&amp;", $Pivot_Vars['session']);
			$editlink=sprintf("%sid=%s&amp;edit=%s", $link, $db->entry['code'], $key);
			$dellink=sprintf("%sid=%s&amp;del=%s", $link, $db->entry['code'], $key);

			printf("</tr><tr class='tabular_line_odd'><td><a href='%s'>%s</a> /", $editlink, lang('entries', 'edit_trackback') );
			printf(" <a href='%s'>%s</a>&nbsp;&nbsp;", $dellink, lang('entries', 'delete_trackback') );

		} else {
			printf("<td>&nbsp;</td>");
		}

		// only show the option to add or remove ip-blocks if the user is an administrator.
		if ($Users[$Pivot_Vars['user']]['userlevel']>=3) {

			if ($myblock=="none") {
				$blocktext1 = str_replace("%s", $trackback['ip'], lang('entries', 'block_single'));
				$blocklink1 = sprintf("%sid=%s&blocksingle=%s", $link, $db->entry['code'], $trackback['ip']);
				$blocktext2 = str_replace("%s", make_mask($trackback['ip']), lang('entries', 'block_range'));
				$blocklink2 = sprintf("%sid=%s&blockrange=%s", $link, $db->entry['code'], $trackback['ip']);

				printf("<td><a href='%s'>%s</a> / ", $blocklink1, $blocktext1);
				printf("<a href='%s'>%s</a></td>", $blocklink2, $blocktext2);
			} else if ($myblock=="single") {
				$blocktext1 = str_replace("%s", $trackback['ip'], lang('entries', 'unblock_single'));
				$blocklink1 = sprintf("%sid=%s&unblocksingle=%s", $link, $db->entry['code'], $trackback['ip']);
				printf("<td><a href='%s'>%s</a></td>", $blocklink1, $blocktext1);
			} else {
				$blocktext1 = str_replace("%s", make_mask($trackback['ip']), lang('entries', 'unblock_range'));
				$blocklink1 = sprintf("%sid=%s&unblockrange=%s", $link, $db->entry['code'], $trackback['ip']);
				printf("<td><a href='%s'>%s</a></td>", $blocklink1, $blocktext1);
			}

		} else {
			printf("<td>&nbsp;</td>");
		}

		printf("</td></tr></table><br />");
	} // end of printing trackbacks


	PageFooter();

	echo "<br /><br /><br /><br />";
}


function submit_trackback() {
	global $Cfg, $Pivot_Vars;

	$mytrack = array(
	'title' => entify(stripslashes($Pivot_Vars['title'])),
	'excerpt' => entify(stripslashes($Pivot_Vars['excerpt'])),
	'name' => entify(stripslashes($Pivot_Vars['name'])),
	'url' => $Pivot_Vars['url'],
	'ip' => $Pivot_Vars['ip'],
	'date' => $Pivot_Vars['date'],
	);



	$db = new db();
	$entry = $db->read_entry( $Pivot_Vars['id']);

	$entry['trackbacks'][ $Pivot_Vars['count'] ] = $mytrack;

	$db->set_entry($entry);
	$db->save_entry();

	// remove it from cache, to make sure the laters one is used.
	$db->unread_entry($entry['code']);

	$msg = lang('notice', 'trackback_saved');

	edit_trackbacks($msg);


}




function buildfrontpage() {

	PageHeader(lang('adminbar','buildfrontpage'), 1);
	PageAnkeiler(lang('adminbar','buildfrontpage'));

	echo "<p>";

	buildfrontpage_function();

	print_timers();

	echo "</p><p><b>".lang('general', 'done')."</b></p>";

}



function build_index() {
	global $config_array, $Archive_array;

	PageHeader(lang('adminbar','buildindex'), 1);
	PageAnkeiler(lang('adminbar','buildindex') . ' &raquo; ' . lang('adminbar','buildindex_title'));

	@set_time_limit(0);

	// also force the archive index file to be updated
	@unlink('db/ser-archives.php');
	// Make a new archive array.
	$Archive_array = make_archive_array();


	// msg corrected =*=*= JM 2004/09/27
	echo("<p>". lang('adminbar', 'buildindex_start') ."<br />\n");
	//	echo("<p>". lang('adminbar', 'buildsearchindex_start') ."<br />\n");
	flush();

	$db= new db();
	$db->generate_index();

	echo "<br /><br />\n\n<b>".str_replace("%num%", timetaken(), lang('adminbar', 'buildindex_finished'))."</b><br /><br /></p>\n";

	// stuff stops here..
	PageFooter();
}

// JM - THIS IS WHERE WE DO THE SEARCHINDEXING...
function build_search() {
	global $Cfg, $filtered_words, $Pivot_Vars;

	PageHeader(lang('adminbar','buildsearchindex'), 1);
	PageAnkeiler(lang('adminbar','buildsearchindex') . ' &raquo; ' . lang('adminbar','buildsearchindex_title'));

	/* JM - Bob said was 300, and was lowered to 200 because of a user
			with large entries going over PHP's 8Mb variables limit... wow!
			Is there a more elegant/flexible solution? he asks.
	*/

	// initialise the threshold.. Initially it's set to 10 * the rebuild_threshold,
	// roughly assuming we index 10 entries per second.
	if (isset($Cfg['rebuild_threshold']) && ($Cfg['rebuild_threshold']>4)) {
		$chunksize = (10 * $Cfg['rebuild_threshold']);
	} else {
		$chunksize = 280;
	}


	@set_time_limit(0);

	echo("<p><strong>". lang('adminbar', 'buildsearchindex_start') ."</strong><br /><br />\n");
	flush();

	makedir("db/search");
	include_once("modules/module_search.php");

	$start = (isset($Pivot_Vars['start'])) ? $Pivot_Vars['start'] : 0;
	$stop = $start + $chunksize;
	$time = (isset($Pivot_Vars['time'])) ? $Pivot_Vars['time'] : 0;

	if($start==0) { clear_index();	}

	$continue = start_index($start, $stop, $time);

	write_index(FALSE);

	$time = (isset($Pivot_Vars['time'])) ? $Pivot_Vars['time'] : 0;
	$time += timetaken('int');

	if($continue) {

		$myurl = sprintf("index.php?session=%s&menu=admin&func=admin&do=build_search&start=%s&time=%s", $Pivot_Vars['session'], $stop, $time);
		printf('<script> self.location = "%s"; </script>',$myurl);
		//printf('<a href="%s">%s</a>',$myurl,$myurl);
	} else {
		echo "<br /><br />\n\n<p><b>".str_replace("%num%", $time, lang('adminbar', 'buildindex_finished'))."</b><br /><br /></p>\n";
	}

	// stuff stops here..
	PageFooter();
}



function send_pings() {
	global $Weblogs;
	PageHeader(lang('adminbar','sendping'), 1);
	PageAnkeiler(lang('adminbar','sendping') . ' &raquo; ' . lang('adminbar','sendping_title'));

	$db= new db();

	foreach ($Weblogs as $name => $weblog) {

		$path = $weblog['front_path'].$weblog['front_filename'];

		debug ("ping $path");

		open_ping_window($name, $path);

	}

	echo "</p><p><b>".lang('general', 'done')."</b></p>";

	PageFooter();
}





function u_settings_screen($erred=0, $userfields='') {

	PageHeader(lang('userbar','userinfo'), 1);
	PageAnkeiler(lang('userbar','userinfo') . ' &raquo; ' . lang('userbar','u_settings_title'));

	libchange_user(0, $erred, $userfields);

	// stuff goes here..

	PageFooter();

}



function u_settings_save() {
	global $Pivot_Vars;

	libsave_change_user(0);

}

function u_marklet_screen() {
	global $config_array, $Paths;
	PageHeader(lang('userbar','userinfo'), 1);
	PageAnkeiler(lang('userbar','userinfo') . ' &raquo; ' . lang('bookmarklets','bm_add'));



	$url = gethost().$Paths['pivot_url']."index.php?menu=entries&amp;func=new_entry";

	//debug("url: ".$url);

	$withlink = sprintf("javascript:bm=document.selection?document.selection.createRange().text:document.getSelection();void(open('%s&url='+escape(location.href)+'&i='+escape(bm)+'&t='+escape(document.title), 'new_log_entry', 'resizable=yes, scrollbars=yes, width=750, height=420, location=yes, status=yes'));", $url);

	$nolink = sprintf("javascript:void(open('%s','new_log_entry', 'resizable=yes, scrollbars=yes, width=750, height=420, location=yes, status=yes'));", $url);

	$main_funcs = array(array(lang('bookmarklets','bm_withlink'), $withlink, lang('bookmarklets','bm_withlink_desc')),
	array(lang('bookmarklets','bm_nolink'), $nolink, lang('bookmarklets','bm_nolink_desc')),

	);

	DispPage($main_funcs);

	echo "<p>".lang('bookmarklets', 'bookmarklets_info');
	echo "<ul><li>".lang('bookmarklets', 'bookmarklets_info_1')."</li>";
	echo "<li>".lang('bookmarklets', 'bookmarklets_info_2')."</li></ul></p>";

	PageFooter();
}




?>
