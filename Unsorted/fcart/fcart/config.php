<?
$mysql_host = "lion.simcom.ru";
$mysql_user = "root";
$mysql_db = "fcart";
$mysql_password = "";
$images_url="products";
$default_sortby="alpha";
$cookie_timeout=93600;
$admin_username="admin";
$admin_password="admin";
$admin_root="master";
$admin_root_pwd="webstore";
$safe_admin=false;

$main_www="$HTTP_HOST";
$ourservice="F-Cart";
$http_location=$HTTP_HOST.substr($SCRIPT_NAME,0,strrpos($SCRIPT_NAME,"/"));
$https_location=$HTTP_HOST.substr($SCRIPT_NAME,0,strrpos($SCRIPT_NAME,"/"));
$http_adm_location=$HTTP_HOST.substr($SCRIPT_NAME,0,strrpos($SCRIPT_NAME,"/"));
$https_adm_location=$HTTP_HOST.substr($SCRIPT_NAME,0,strrpos($SCRIPT_NAME,"/"));
$transfer_cookie=false;

$bonus_points="DVD points";

$cc_required=true;
$cc_disabled=false;
$generic_card_type=1;
$generic_card_name="John Doe";
$generic_card_num="4444333322221111";
$generic_expmonth="01";
$generic_expyear="01";

mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db) || die("Could not connect to SQL db");
$c_result = mysql_query("select name, value from config");
while ($row = mysql_fetch_row($c_result)) {
	${$row[0]} = $row[1];
}
mysql_free_result($c_result);
?>
