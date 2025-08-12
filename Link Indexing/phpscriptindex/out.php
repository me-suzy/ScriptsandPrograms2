<?
if (!$id){
	Header("Location: index.php");
	exit;
}
include "./config.php";

if ($dl=="1"){
	$sql = "select hitsout,dlurl from $tablescripts where id='$id'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		Header("Location: index.php");
		exit;
	}
	$hitsout = $resrow[0];
	$url = $resrow[1];
	$hitsout++;
	$sql = "update $tablescripts set hitsout='$hitsout' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: $url");
	exit;
}

if ($demo=="1"){
	$sql = "select hitsout,demourl from $tablescripts where id='$id'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		Header("Location: index.php");
		exit;
	}
	$hitsout = $resrow[0];
	$url = $resrow[1];
	$hitsout++;
	$sql = "update $tablescripts set hitsout='$hitsout' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: $url");
	exit;
}

if ($home=="1"){
	$sql = "select hitsout,homeurl from $tablescripts where id='$id'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		Header("Location: index.php");
		exit;
	}
	$hitsout = $resrow[0];
	$url = $resrow[1];
	$hitsout++;
	$sql = "update $tablescripts set hitsout='$hitsout' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: $url");
	exit;
}
?>