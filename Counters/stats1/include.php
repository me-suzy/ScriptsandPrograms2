<?

include "config.php";

if(session_id() == "") {
session_start();
}

if(STATS_CONNECTION == 0) {
	mysql_connect(STATS_MYSQL_HOSTNAME, STATS_MYSQL_USERNAME, STATS_MYSQL_PASSWORD) or die ("1. Counter script encountered an error:<br>".mysql_error());
	mysql_select_db(STATS_MYSQL_DATABASE) or die ("2. Counter script encountered an error:<br>".mysql_error()); 
}

if($_SESSION['Uniques'] == "") {
	$_SESSION['Uniques'] = "1";
	
	$day_query = mysql_query("SELECT * FROM days WHERE Day = '".date("j", time())."' AND Site = '".STATS_SITE_URL."' LIMIT 1") or die ("3. Counter script encountered an error:<br>".mysql_error());
	
	if(mysql_num_rows($day_query) == 0) {
	
		mysql_query("INSERT INTO days (ID, Site, Day, Uniques, Total) VALUES ('', '".STATS_SITE_URL."', '".date("j", time())."', '1', '1')") or die ("4. Counter script encountered an error:<br>".mysql_error());
	
	} else {
	
		mysql_query("UPDATE days SET Uniques = Uniques + 1, Total = Total + 1 WHERE Site = '".STATS_SITE_URL."' AND Day = '".date("j", time())."' LIMIT 1") or die ("5. Counter script encountered an error:<br>".mysql_error());
	
	}
	
	$month_query = mysql_query("SELECT * FROM months WHERE Month = '".date("n", time())."' AND Site = '".STATS_SITE_URL."' LIMIT 1") or die ("6. Counter script encountered an error:<br>".mysql_error());
		
	if(mysql_num_rows($month_query) == 0) {
		
		mysql_query("INSERT INTO months (ID, Site, Month, Year, Uniques, Total) VALUES ('', '".STATS_SITE_URL."', '".date("n", time())."', '".date("Y", time())."', '1', '1')") or die ("7. Counter script encountered an error:<br>".mysql_error());
		
		mysql_query("DELETE FROM days WHERE Site = '".STATS_SITE_URL."'") or die ("7_2. Counter script encountered an error:<br>".mysql_error());
	
	} else {
		
		mysql_query("UPDATE months SET Uniques = Uniques + 1, Total = Total + 1 WHERE Site = '".STATS_SITE_URL."' AND Month = '".date("n", time())."' AND Year = '".date("Y", time())."' LIMIT 1") or die ("8. Counter script encountered an error:<br>".mysql_error());
		
	}
	
} else {

	$day_query = mysql_query("SELECT * FROM days WHERE Day = '".date("j", time())."' AND Site = '".STATS_SITE_URL."' LIMIT 1") or die ("9. Counter script encountered an error:<br>".mysql_error());
	
	if(mysql_num_rows($day_query) == 0) {
	
		mysql_query("INSERT INTO days (ID, Site, Day, Uniques, Total) VALUES ('', '".STATS_SITE_URL."', '".date("j", time())."', '1', '1')") or die ("10. Counter script encountered an error:<br>".mysql_error());
	
	} else {
	
		mysql_query("UPDATE days SET Total = Total + 1 WHERE Site = '".STATS_SITE_URL."' AND Day = '".date("j", time())."' LIMIT 1") or die ("11. Counter script encountered an error:<br>".mysql_error());
	
	}
	
	$month_query = mysql_query("SELECT * FROM months WHERE Month = '".date("n", time())."' AND Site = '".STATS_SITE_URL."' LIMIT 1") or die ("12. Counter script encountered an error:<br>".mysql_error());
		
	if(mysql_num_rows($month_query) == 0) {
		
		mysql_query("INSERT INTO months (ID, Site, Month, Year, Uniques, Total) VALUES ('', '".STATS_SITE_URL."', '".date("n", time())."', '".date("Y", time())."', '1', '1')") or die ("13. Counter script encountered an error:<br>".mysql_error());
		
		mysql_query("DELETE FROM days WHERE Site = '".STATS_SITE_URL."'") or die ("13_2. Counter script encountered an error:<br>".mysql_error());
	
	} else {
		
		mysql_query("UPDATE months SET Total = Total + 1 WHERE Site = '".STATS_SITE_URL."' AND Month = '".date("n", time())."' AND Year = '".date("Y", time())."' LIMIT 1") or die ("14. Counter script encountered an error:<br>".mysql_error());
		
	}

}



$refferal = gethostbyname($HTTP_REFERER);
$refferal = str_replace("http://", "", $refferal);
$refferal = str_replace("www.", "", $refferal);
$parts = explode("/", $refferal);
$refferal = $parts[0];

if(($refferal != STATS_SITE_URL) && ($refferal != "")) {

	$refferal_query = mysql_query("SELECT * FROM refferals WHERE Site = '".STATS_SITE_URL."' AND Refferer = '".$refferal."' LIMIT 1") or die ("15. Counter script encountered an error:<br>".mysql_error());

	if(mysql_num_rows($refferal_query) == 1) {
		
		mysql_query("UPDATE refferals SET Total = Total + 1 WHERE Site = '".STATS_SITE_URL."' AND Refferer = '".$refferal."' LIMIT 1") or die ("16. Counter script encountered an error:<br>".mysql_error());
		
	} else {
	
		mysql_query("INSERT INTO refferals (ID, Site, Refferer, Total) VALUES ('', '".STATS_SITE_URL."', '".$refferal."', '1')") or die ("17. Counter script encountered an error:<br>".mysql_error());
	
	}

}

mysql_query("OPTIMIZE TABLE days, months, referrals");

if(STATS_CONNECTION == 0) {
	mysql_close();
}
?>