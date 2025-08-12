<?php
$z = "b";
include ("config.php");

setcookie("username", "", time()-24*3600*14);
setcookie("password", "", time()-24*3600*14);

$upd = mysql_query("UPDATE onecms_users SET logged = '0' WHERE username = '$username'") or die(mysql_error());
include ("a_header.inc");
echo "You are now logged out";
include ("a_footer.inc");
?>