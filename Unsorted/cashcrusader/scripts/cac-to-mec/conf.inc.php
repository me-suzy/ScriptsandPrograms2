<?
include("../conf.inc.php");
$mysql_username=$mysql_user;
$awardtype = "Dollars";         
$awardstyle = "SetPercent";
$awardpoints = array('0','15','10','5','2','1');
$maxtiers = count($awardpoints)-1;
$userdatadir="/home/$mysql_user/userdata/";
$cac_data_root="/home/$mysql_user/";
