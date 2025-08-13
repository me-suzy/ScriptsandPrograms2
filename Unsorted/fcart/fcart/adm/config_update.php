<?
include "../config.php";
include "../mod.php";
include "auth.php";
include "mod.php";

if ($safe_admin) safe_mode_msg(true);
include "../params.php";

while (list($key,$val) = each($HTTP_POST_VARS)) {
	mysql_query("update config set value='".r_secure($val)."' where name='".$key."'") or die ("$mysql_error_msg");
}

header("Location: ".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/config_edit.php?first=$first&sortby=$sortby&category=".urlencode($category));
?>
