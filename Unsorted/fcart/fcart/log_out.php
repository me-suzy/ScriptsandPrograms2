<?
include "config.php";
include "mod.php";

include "params.php";

$id = md5(uniqid(rand().getmypid()));
$id = r_secure($id);
setcookie("ID", $id, time() + $cookie_timeout);

header("Location: http://$http_location/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&text=".urlencode("You have logged out. Thank you for using $ourservice"));
?>
