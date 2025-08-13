<?
//main protected folder full path. Inside it must be .htaccess and.passwd files with 666 chmod permissions
$protected_path= "/home/john/public_html/protected/";

//Site info
$login_page = "index.php";
$site_name = "Protected area";
$webmaster_mail = "www@site.com";
$protected_url = "http://www.site.com/protected/index.html";
//small .gif or .jpg image inside protected folder
$protected_image = "www.site.com/protected/hackerHunter_logo_sm.gif"; //Without http://

// Database Info
$db_host="localhost";
$db_name="auth";
$db_user="username";
$db_password="password";
$users_table="users";
$sessions_table = "sessions";
$proxy_table = "proxy";
$changes_table = "changes";

//General settings.

//If it set to 1, login_js.htm template will be used and password will be slightly encoded before 
//sending across network. And if it will be any other number, will be used login.htm template
//and password will not be encoded.
//Note: In any case real password (encoded or not encoded) will be send across network only once.
$use_js_encode = 1;

$proxy_deny = 0; //set it to zero to allow users use proxy (NOT RECOMMENDED TO ALLOW IT!)
$temp_user_timeout = 7200; // in seconds. 2 hours = 7200 seconds
$rotation_timeout = 1200; // in seconds. 20 minutes = 1200 seconds

//functions and constants don't change something below :)
$template = "something_wrong.htm";
$c_time = time();
if (!preg_match("~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)[0-9]{1,3}~", $HTTP_SERVER_VARS["REMOTE_ADDR"], $remoteip)) {
	if (!preg_match("~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)[0-9]{1,3}~", $_SERVER["REMOTE_ADDR"], $remoteip)) {
		if (!preg_match("~([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)[0-9]{1,3}~", getenv('REMOTE_ADDR'), $remoteip)){
			$user_ip = "Unknown";
		}
	}
}
if ($user_ip != "Unknown") {
	$user_ip = $remoteip[1]."*";
	$full_ip = $remoteip[0];
}
srand ((double) microtime() * 1000000);
$disallowed_symbols = "/[^_A-Za-z0-9 ]/";
$mail_disallowed_symbols = "/[^_A-Za-z0-9\.@]/";
$symbols = array ("B","q","o","0","i","O","s","w","z","b","R","p","P","n","4","G","y","N","8","5","r","H","e","7","m","E","Z","L","u","j","M","Y","d","t","V","W","Q","g","U","c","_","F","X","I","6","a","A","x","J","K","1","T","2","l","f","D","S","h","9","k","3","C","v");
function read_template ($template){
	$page = fopen($template, "r");
	$html = fread($page, filesize ($template));
	fclose($page);
	return $html;
}
function rand_string($length){
	global $symbols;
	$tmp_arr = array_rand ($symbols, $length);
	foreach ($tmp_arr as $value) {
		$tmp_str.=$symbols[$value];
	}
	return $tmp_str;
}
$this_server = $HTTP_SERVER_VARS["SERVER_NAME"];
$auth_web_path = preg_replace ("~[^/]+$~", "", $HTTP_SERVER_VARS["SCRIPT_NAME"]);?>