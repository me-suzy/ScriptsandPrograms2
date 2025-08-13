<?php
ignore_user_abort(true);

require("dbsettings.php");
require("update.php");

$tidc = time();
$tid = localtime($tidc);
$thour = $tid[2];
$refid = 1;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$result = mysql_query("SELECT lastupdate FROM updateinfo");
$line = mysql_fetch_array($result, MYSQL_NUM);
$lutid = localtime($line[0]);

if( $lutid[2] != $thour ) {
	updatehour($thour,$lutid[2],$lutid[3],$lutid[4],$lutid[5]+1900);
	$result = mysql_query("UPDATE updateinfo SET lastupdate='$tidc'");
	}

$ip = $_SERVER["REMOTE_ADDR"];
$refurl = $_SERVER["HTTP_REFERER"];

$refa = explode("/",$refurl);
preg_match("/(www\.)*(.*)/",$refa[2],$refd);
$refdomain = $refd[2];

if ( $refdomain ) {
	$query = "SELECT siteid FROM sites WHERE sitedomain='$refdomain'";
	$result = mysql_query($query) or die(mysql_error());
	if ( $line = mysql_fetch_array($result, MYSQL_NUM) )
		{
		$refid = $line[0];
		}
	}

$query = "UPDATE sites SET in$thour=in$thour+1 WHERE siteid=$refid"; 
$result = mysql_query($query) or die(mysql_error());

if( $refurl == "" ) { $refurl = "none"; }
$result = mysql_query("INSERT INTO visitlog (siteid, ip, referer, tid) VALUES ($refid, '$ip', '$refurl', NOW())");
mysql_close($link);

print "<script language=\"JavaScript\">\n<!--\ndocument.cookie='infref=$refid; expires=Fri, 01-Jan-2010 12:00:00 GMT; path=/'\n//-->\n</script>";
?>
