<?PHP
function updateonline($topicid,$viewforum,$posting, $userid, $username, $page){
$select_all_os = mysql_query("SELECT * FROM online");
while ($r = mysql_fetch_assoc($select_all_os)){
	$update = mysql_query("UPDATE online SET viewtopic='0', viewforum='0', posting='0' WHERE isonline='0'");
}

$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time();
$expiration = $timestamp - 900;
$current_day = date('j');
	
	$delete_results = mysql_query("DELETE FROM online WHERE online_date!='$current_day'");
    $delete_res_2 = mysql_query("DELETE FROM online WHERE isonline='0' AND guest='1'");
	if(!isset($userid)){
		$z_result = mysql_query("SELECT * FROM online WHERE ip='$ip' AND guest='1'");
		$count = mysql_num_rows($z_result);

		$update_results = mysql_query("UPDATE online SET isonline='0', page='". $page ."' WHERE ip='$_SERVER[REMOTE_ADDR]' AND guest='0'");

		if($count!=0){
			$x_result = mysql_query("UPDATE online SET timestamp='". $timestamp ."', page='". $page ."', viewtopic='". $topicid ."', viewforum='". $viewforum ."', posting='". $posting ."',  isonline='1' WHERE ip='". $ip ."' AND guest='1'");
		} else {
			$x_result = MYSQL_QUERY("INSERT INTO online (timestamp,guest,ip,viewtopic,viewforum,posting, online_date, page)".
			"VALUES ('". $timestamp ."', '1', '". $ip ."', '". $topicid ."', '". $viewforum ."', '". $posting ."', '". $current_day ."', '". $page ."')"); 
		}
	} else {
		$date = date("Y-m-j");
		$u_result = mysql_query("UPDATE users SET lastlogin='". $date ."' WHERE id='". $userid ."'");

		$z_result = mysql_query("SELECT * FROM online WHERE id='". $userid ."' AND guest='0'");
		$count = mysql_num_rows($z_result);

		$delete_results = mysql_query("DELETE FROM online WHERE ip='$_SERVER[REMOTE_ADDR]' AND guest='1'");

		if($count!=0){
				$x_result = mysql_query("UPDATE online SET timestamp='". $timestamp ."', page='". $page ."', viewtopic='". $topicid ."', viewforum='". $viewforum ."', posting='". $posting ."', isonline='1', ip='". $ip ."' WHERE id='". $userid ."' AND guest='0'");
		} else {
				$x_result = MYSQL_QUERY("INSERT INTO online (id,username,timestamp,guest,ip,viewtopic,viewforum,posting, online_date, page)".
				"VALUES ('". $userid ."', '". $username ."', '". $timestamp ."', '0', '". $ip ."', '". $topicid ."', '". $viewforum ."', '". $posting ."', '". $current_day ."', '". $page ."')"); 
		}
	}
	$update_results = mysql_query("UPDATE online SET isonline='0' WHERE timestamp<'". $expiration ."'");
}
?>