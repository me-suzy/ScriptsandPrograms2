<?
GLOBAL $script_url,$locale_db_host,$locale_db_login,$locale_db_pass,$locale_db_name,$https;

###############################################
### CHANGE ModernBill Database Settings #######
###############################################

$locale_db_host  = "localhost"; # <-- Your DB HOST
$locale_db_login = "username";  # <-- Your DB LOGIN NAME
$locale_db_pass  = "password";  # <-- Your DB LOGIN PASSWORD
$locale_db_name  = "modernbill";# <-- Your DB NAME

##
## This is your STANDARD URL to ModernBill: SEE EXAMPLE FOR SYNTAX
##
## Our demo is located here: http://www.modernbill.com/demo/
##   ...so our setting looks like this below:
##
##   $standard_url = "www.modernbill.com/demo/";
##
##   NOTICE: NO "http://" and WITH ending "/"
##

$standard_url = "www.my_domain.com/modernbill/"; # <-- ENTER YOURS HERE!

##
## This is your SECURE URL to ModernBill: SEE EXAMPLE FOR SYNTAX
##
## Our secure demo is located here: https://ns1.modernserver.com/modernbill/demo/
##   ...so our setting looks like this below:
##
##   $standard_url = "ns1.modernserver.com/modernbill/demo/";
##
##   NOTICE: NO "https://" and WITH ending "/"
##
##

$secure_url = "www.my_ssl_domain.com/modernbill/"; # <-- ENTER YOURS HERE!

##
## Enter "http" if you are NOT going to use the secure_url
## Enter "https" if you ARE going to use the secure_url
##

$https = "http"; # <-- ENTER YOURS HERE!

###############################################
###############################################
?>