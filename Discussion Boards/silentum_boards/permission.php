<?
	/*
	Silentum Boards v1.4.3
	permission.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	@error_reporting(E_ALL & ~E_NOTICE);

	session_start();

	if(@get_cfg_var("register_globals") != 1) {
	while($act_var = each($HTTP_GET_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	while($act_var = each($HTTP_POST_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	while($act_var = each($HTTP_ENV_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	while($act_var = each($HTTP_SERVER_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	while($act_var = each($HTTP_COOKIE_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	while($act_var = each($HTTP_SESSION_VARS)) {
	$$act_var[0] = $act_var[1];
	}
	}

	if(!isset($_SERVER) || !isset($_ENV)) {
	@$_GET = $HTTP_GET_VARS;
	@$_POST = $HTTP_POST_VARS;
	@$_ENV = $HTTP_ENV_VARS;
	@$_SERVER = $HTTP_SERVER_VARS;
	@$_COOKIE = $HTTP_COOKIE_VARS;
	@$_SESSION = $HTTP_SESSION_VARS;
	}

	require_once("settings.php");

	$file = $config['temp_css_file'];
	if(is_dir(substr($file, 0, -4))) {
	$imagefolder =substr($file, 0, -4);
	require("$imagefolder/stylesheet_settings.php");
	}
	else {
	$imagefolder="images";
	}

	$user_logged_in = 0;
	$user_id = 0;
	unset($user_data);
	unset($cache);

	nix();

	if(!isset($file_counter)) $file_counter = 0;

	if(isset($session_user_id)) {
	$session_user_data = get_user_data($session_user_id);
	if($session_user_pw == $session_user_data['pw']) {
	$user_logged_in = 1;
	$user_id = $session_user_id;
	$user_data = $session_user_data;
	}
	}

	if($user_logged_in == 0 && isset($cookie_xbbuser)) {
	$cookie_userdata = myexplode($cookie_xbbuser);
	if(trim($cookie_userdata[1]) != "" && $cookie_userdata[1] != "0") {
	if($cookie_userdata2 = get_user_data($cookie_userdata[0])) {
	if($cookie_userdata2[pw] == $cookie_userdata[1]) {
	$user_logged_in = 1;
	$user_id = $cookie_userdata[0];
	$user_data = $cookie_userdata2;
	$session_user_id = $cookie_userdata[0];
	$session_user_pw = $cookie_userdata[1];
	session_register('session_user_id','session_user_pw');
	}
	}
	}
	}

	session_name("sid");
	$HSID = "sid=".session_id();

	if($user_logged_in != 1) {
	$set_new_cookie = 1;
	if(isset($session_online)) {
	if(strlen($session_online) < 16) {
	$set_new_cookie = 0;
	$special_id = $session_online;
	}
	}
	if($set_new_cookie == 1) {
	$special_id = "guest".get_random_number(10);
	$session_online = $special_id;
	session_register("session_online");
	}
	}
	else {
	$special_id = $user_id;
	if(!$session_online) {
	$session_online = $special_id;
	session_register("session_online");
	}
	}

	if(isset($var_css_file)) {
	if(!isset($HTTP_SESSION_VARS['session_css_file'])) {
	$HTTP_SESSION_VARS['session_css_file'] = 'stylesheets/'.$var_css_file;
	session_register('session_css_file');
	}
	else $HTTP_SESSION_VARS['session_css_file'] = 'stylesheets/'.$var_css_file;
	$config['temp_css_file'] = $HTTP_SESSION_VARS['session_css_file'];
	}
	elseif(isset($HTTP_SESSION_VARS['session_css_file'])) $config['temp_css_file'] = $HTTP_SESSION_VARS['session_css_file'];
	else $config['temp_css_file'] = $config['default_stylesheet'];
?>