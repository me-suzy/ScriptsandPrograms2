<?
set_time_limit(0);
include "./config.php";

if ((!$ckAdminPass) || ($ckAdminPass!=$adminpass)){
	print "Only the admin can access this file. If you are the admin, you need to read the instructions to gain administrator rights.";
	exit;
}

$sql = "select id,cat from $tablecats";
$result = mysql_query($sql);
$numrows = mysql_num_rows($result);
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$cat = $resrow[1];
	print "<b>$cat</b> - ";
	flush();

	$sql = "select count(*) from scripts where subcat='$id'";
	$reslt2 = mysql_query($sql);
	$rr = mysql_fetch_row($reslt2);
	$ct = $rr[0];
	$sql = "update scriptcats set ct='$ct' where id='$id'";
	$reslt2 = mysql_query($sql);
	print $ct."<br>";
	flush();
}
print "Done!";
?>