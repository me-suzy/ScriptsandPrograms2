<?
session_start();
IF (@!$HTTP_SESSION_VARS['secure_id'] && @!$HTTP_SESSION_VARS['secure_ghost']) {
$error = "You are not logged in.  Please log in.";
header("location: ../login.php?error=$error");
die();
}

IF ($HTTP_SESSION_VARS['secure_level']=='2' || $HTTP_SESSION_VARS['secure_level']=='0' || $HTTP_SESSION_VARS['secure_level']=='5' || $HTTP_SESSION_VARS['secure_level']=='6') {
$error = "You are not logged in.  Please log in.";
header("location: ../login.php?error=$error");
die();
}
?>