<?
$id = $HTTP_COOKIE_VARS['ID'];
if (strlen($id) < 30)
	die("Cookie support required");
$id = r_secure($id);
setcookie("ID", $id, time() + $cookie_timeout);
?>
