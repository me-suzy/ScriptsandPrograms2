<?include ("inc.php");
session_start();
if ($use_js_encode == 1) {
	$login_ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
	session_register("login_ip");
	$Ltemplate = "login_js.htm";
} else {
	$Ltemplate = "login.htm";
}
echo preg_replace("~#([a-z_]+)#~ie","$\\1",read_template($Ltemplate));
?>