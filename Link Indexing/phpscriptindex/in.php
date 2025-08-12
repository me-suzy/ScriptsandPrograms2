<?
if (!$id){
	Header("Location: index.php");
	exit;
}
include "./config.php";
$sql = "select hitsin,subcat from $tablescripts where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$resrow = mysql_fetch_row($result);
$hitsin = $resrow[0];
$subcat = $resrow[1];
$viewed = $HTTP_COOKIE_VARS["viewed".$id];

if (!$viewed){
	$hitsin++;
	$sql = "update $tablescripts set hitsin='$hitsin' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$hour = 3600;
	setcookie ("viewed".$id, "1", time()+$hour);
}
Header("Location: index.php");
exit;
?>