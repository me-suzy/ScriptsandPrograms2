<?php
echo phpinfo();
echo "\n---------------------------------------------\n";

print_r("\n_SERVER:"); 
print_r($_SERVER);
print_r("\nHTTP_SERVER_VARS:");
print_r($HTTP_SERVER_VARS);
print_r("\n_GET");
print_r($_GET);
print_r("\nHTTP_GET_VARS");
print_r($HTTP_GET_VARS);
print_r("\n_POST");
print_r($_POST);
print_r("\nHTTP_POST_VARS");
print_r($HTTP_POST_VARS);
print_r("\n_COOKIE");
print_r($_COOKIE);
print_r("\nHTTP_COOKIE_VARS");
print_r($HTTP_COOKIE_VARS);
print_r("\n_ENV");
print_r($_ENV);
print_r("\nHTTP_ENV_VARS");
print_r($HTTP_ENV_VARS);
print_r("");

?>