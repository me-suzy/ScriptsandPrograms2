#!/usr/local/bin/php -q
<?PHP
/**
* A script to run operations of 12all via cronjob
*/
	require_once("engine.inc.php");
	$result = mysql_query ("SELECT * FROM Messages
							WHERE completed = '0'
							AND status = '0'
							LIMIT 1
	");
	if ($c1 = mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$d_num = $row["d_check"];
			$d_id = $row["id"];
			$d_nl = $row["nl"];
			if ($d_num > 0){
				$id=$d_id;
				$sendval=resend;
				$nl=$d_nl;
				include("send_app2.php");
			}
			else {
				mysql_query("UPDATE Messages SET d_check = d_check + 1 WHERE id = '$d_id'");		
			}
		} 
	}
	$t_date = date("Y-m-d");
	$t_time = date("H:i:s");
	$result = mysql_query ("SELECT * FROM Messages
							WHERE completed = '0'
							AND status = '4'
							AND s_date <= '$t_date'
							AND s_time <= '$t_time'
							OR  completed = '0'
							AND status = '4'
							AND s_date < '$t_date'
	");
	if ($c1 = mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$d_id = $row["id"];
			$d_nl = $row["nl"];
			$d_time = $row["s_time"];
				$id=$d_id;
				$sendval=resend;
				$nl=$d_nl;
				include("send_app2.php");
		} 
	}
?>