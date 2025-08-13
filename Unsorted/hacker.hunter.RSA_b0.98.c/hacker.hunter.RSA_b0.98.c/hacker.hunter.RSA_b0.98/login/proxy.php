<?
include ("inc.php");
session_start();
/*Parse session and data*/
if (session_is_registered("allow")){
	if ($HTTP_SESSION_VARS["allow"] == $HTTP_SERVER_VARS["REMOTE_ADDR"]){
		if (session_is_registered("show")) {
			if ($denited == "no") {
				$show = 0;
			} elseif ($denited == "yes"){
				$show = 1;
			}
		}else{
			$show= 1;
			session_register ("show");
		}
		if (mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error")){
			mysql_select_db($db_name);
			if (isset($field_proxy)) {
				$sql_clear_proxy = "DELETE FROM $proxy_table where deny=$show";
				mysql_query ($sql_clear_proxy) or die ("Bad query");
				if (strlen($field_proxy)>6) {
					$proxy_array = explode("\n", trim($field_proxy));
					foreach ($proxy_array as $value) {
						$str =trim ($value);
						$sql_add_proxy = "INSERT INTO $proxy_table (proxy_ip,deny) VALUES ('".trim ($value)."',$show)";
						mysql_query ($sql_add_proxy) or die ("Bad query");
					}
				}
			}
			$sql_proxy = "SELECT proxy_ip FROM $proxy_table WHERE deny=$show order by proxy_ip";
			$proxy_list = mysql_query ($sql_proxy) or die ("Bad query");
			while ($row = mysql_fetch_row($proxy_list)) {
				$proxies .= $row[0]."\n";
			}
			mysql_close();?>
			<html><head>
			<title>Hacker Hinter. Proxy admin Panel.</title>
			<link rel="stylesheet" href="admin.css"></head><body>
			<form method="post" action="proxy.php"><center>
			<?if ($show==1) {
				echo "<a href=\"proxy.php?denited=no\">Show allowed proxies</a><p>Denited proxies list:";
			} else {
				echo "<a href=\"proxy.php?denited=yes\">Show denited proxies</a><p>Allowed proxies list:";
			}
			echo "<br><textarea class=\"frm\" name=\"field_proxy\" rows=\"28\" cols=\"20\">$proxies</textarea><p><input type=\"submit\" class=\"fld\" value=\"Update\"></form><b>";
			include ("timer.php");
			echo $s_time."</b></center></body></html>";
		}
	} else {
		session_unregister ("allow");
		header ("Location: index.html");
	}
} else {
	header ("Location: index.html");
}
?>