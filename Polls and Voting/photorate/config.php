<?
####################################
#        PhotoRate v2.0
#      Nuked Web Services
#    http://www.nukedweb.com/
####################################

#These 4 blank variables MUST be filled in with your
#MySQL information before you canstart.


$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$table = "photorate";

#Voting Options - Change these to suit your purpose for PhotoRate
$option_a = "Disgusting!";
$option_b = "Pretty Bad!";
$option_c = "It's OK.";
$option_d = "Pretty Nice!";
$option_e = "Oh YEAH!";

#Color bars - These specify the colors of the vote results bars on each page.
$optcolor_a = "#FF0000";
$optcolor_b = "#0033FF";
$optcolor_c = "#00CC66";
$optcolor_d = "#FFFF33";
$optcolor_e = "#9966FF";

#This specifies the maximum size (in bytes) for uploaded pictures.
$maxsize = "100000";

#This needs not to be edited. It's the code to connect to MySQL. :)
$db = mysql_connect($sqlhost, $sqllogin, $sqlpass);

mysql_select_db($sqldb, $db);

?>