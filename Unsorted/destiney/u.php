<?php
include("./admin/config.php");
include("$include_path/common.php");
if(isset($_GET['k']) && strlen($_GET['k']) == 32){
	$sql = "
		update
			$tb_users
		set
			subscribed = 'no'
		where
			md5key = '$_GET[k]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	echo "Unsubscribed..";
} else {
	echo "Bad key.. check your url for line wrap.";
}
?>