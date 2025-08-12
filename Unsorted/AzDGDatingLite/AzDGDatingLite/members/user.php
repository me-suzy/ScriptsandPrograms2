<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
$sql = "SELECT id, user, password FROM $mysql_table WHERE user = '$username'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
$i = mysql_fetch_array($result);
if ($i == "") {
include "../templates/header.php";
echo $err_mes_top.$lang[46].$err_mes_bottom;
include "../templates/footer.php";
die;
} elseif ($i != "") {
if ($password != md5(stripslashes($i[password]))) {
include "../templates/header.php";
echo $err_mes_top.$lang[45].$err_mes_bottom;
include "../templates/footer.php";
die;
}
$checkid = $i[id];
}
?>