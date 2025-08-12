<?

if (!$id && !$from){

	Header("Location: $bx_url");

	exit;

}

include "./config.php";

$sql = "select siteclicks from $table where id='$from'";

$result = mysql_query($sql) or die("Failed: $sql");

$numrows = mysql_num_rows($result);

if ($numrows==0){

	Header("Location: $bx_url");

	exit;

}



$resrow = mysql_fetch_row($result);

$siteclicks = $resrow[0];

$siteclicks++;

$sql = "update $table set siteclicks='$siteclicks', lastclickin=now() where id='$from'";

$result = mysql_query($sql) or die("Failed: $sql");







$sql = "select url,myclicks from $table where id='$id'";

$result = mysql_query($sql) or die("Failed: $sql");

$numrows = mysql_num_rows($result);

if ($numrows==0){

	Header("Location: $bx_url");

	exit;

}



$resrow = mysql_fetch_row($result);

$url = $resrow[0];

$myclicks = $resrow[1];

$myclicks++;

$sql = "update $table set myclicks='$myclicks' where id='$id'";

$result = mysql_query($sql) or die("Failed: $sql");

Header("Location: $url");

exit;

?>