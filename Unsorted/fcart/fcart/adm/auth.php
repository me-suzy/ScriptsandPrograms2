<?
        if (!isset($PHP_AUTH_USER)) {
        header("WWW-Authenticate: Basic realm=\"$main_www admin area\"");
        header("HTTP/1.0 401 Unauthorized");
        exit;
        }
	if($PHP_AUTH_USER != $admin_root || $PHP_AUTH_PW != $admin_root_pwd) {
        if ($PHP_AUTH_USER != $admin_username || $PHP_AUTH_PW != $admin_password) {
        header("HTTP/1.0 401 Auth Required");
        header("WWW-authenticate: basic realm=\"Admin area\"");
        exit;
        }
	} else $safe_admin=false;
?>
