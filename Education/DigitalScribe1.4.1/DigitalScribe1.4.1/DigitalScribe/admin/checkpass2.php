<?
session_start();
IF (@$HTTP_SESSION_VARS['secure_level']!=1) {
$error = "You do not have access to see that page.  Please log in.";
header("location: ../login.php?error=$error");
die();
}
?>