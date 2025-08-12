<?
/*
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/

if (isset ($HTTP_GET_VARS)){
$_GET = $HTTP_GET_VARS;
}
if (isset ($HTTP_POST_VARS)){
$_POST = $HTTP_POST_VARS;
}
if (isset ($HTTP_COOKIE_VARS)){
$_COOKIE = $HTTP_COOKIE_VARS;
}
if (isset ($HTTP_POST_FILES)){
$_FILES = $HTTP_POST_FILES;
}

if (!isCfgOn('register_globals')) {
	foreach($_GET as $k=>$v){
		if (!get_magic_quotes_gpc()) {
		$$k = addslashes($v);
		} else {
		$$k = $v;
		}
	}
	foreach($_COOKIE as $k=>$v){
		if (!get_magic_quotes_gpc()) {
		$$k = addslashes($v);
		} else {
		$$k = $v;
		}
	}
	foreach($_POST as $k=>$v){
		if (!get_magic_quotes_gpc()) {
		$$k = addslashes($v);
		} else {
		$$k = $v;
		}
	}
}



// SECURITY STUFF
$dir = stripslashes(safePath($dir));

?>