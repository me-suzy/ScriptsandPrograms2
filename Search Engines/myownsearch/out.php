<?
if (!$id && !$adid) exit;
include "./config.php";
include "./mysql.php";

if ($id){
	$sql = "select url,clicks from $table where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		print "Invalid ID";
		exit;
	}
	$resrow = mysql_fetch_row($result);
	$url = $resrow[0];
	$clicks = $resrow[1];
	$clicks++;
	$sql = "update $table set clicks='$clicks' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: $url");
	exit;
}

if ($adid){
	$sql = "select url,clicks from $adstable where id='$adid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		print "Invalid ID";
		exit;
	}
	$resrow = mysql_fetch_row($result);
	$url = $resrow[0];
	$clicks = $resrow[1];
	$clicks++;
	$sql = "update $adstable set clicks='$clicks' where id='$adid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: $url");
	exit;
}
?>