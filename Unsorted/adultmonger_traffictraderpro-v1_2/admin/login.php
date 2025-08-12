<?
require_once("admin_max_settings.php"); if (check_user($u,$p)) { $up = $u."|".$p;  setcookie ("ttp_set",$up,time()+86400,"/",$HTTP_HOST);
header("Location: admin.php\n\n"); exit(); } else {header("Location: login.htm\n\n"); exit();}
?>
