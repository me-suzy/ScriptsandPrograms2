<?
session_start();
include ("../config.php");

if ($loggedin != "1"){
	header("Location: /login.php?e=1"); /* Redirect browser */ 
	/* Make sure that code below does not get executed when we redirect. */ 
	exit; 
}
//echo "ok";
?>



<?
/*function authenticate() {
   header("WWW-Authenticate: Basic realm=\"Login newsscript Admin\"");
   header("HTTP/1.0 401 Unauthorized");
   print("You must enter a valid login username and password to access the CMS-admin\n");
   exit;
   }
if(!isset($PHP_AUTH_USER))
{ authenticate(); }
else {
   $php_auth_user = $_SERVER['PHP_AUTH_USER'];
   $php_auth_pass = $_SERVER['PHP_AUTH_PW'];
   if ($php_auth_user != $login || $php_auth_pass != $pass) {
   authenticate();
   exit;
   }
}
*/
?> 