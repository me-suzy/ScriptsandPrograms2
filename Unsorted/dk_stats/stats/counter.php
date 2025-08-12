<?
include("connect.php");
// timer is the amount of time that must have passed before another count is registered - seconds
$timer = "300";
$time = time();
$date = date("dmY",$time);
$day = date("d",$time);
$ip = $REMOTE_ADDR;
$result = mysql_query("SELECT * FROM counthits ORDER BY time DESC LIMIT 0,1");
$row = mysql_fetch_array($result);
$olddate = date("dmY",$row[time]);
$oldday = date("d",$row[time]);
// start a new day
if($olddate != $date) {
  mysql_query("insert into counthits (time,userstoday) values ('$time', '1')");
}
// Delete recorded users from yesterday (ips)
mysql_query("delete FROM countip WHERE FROM_UNIXTIME(time, '%d%m%Y') != '$date'");
$result = mysql_query("SELECT * FROM counthits ORDER BY time DESC LIMIT 0,1");
$hits = mysql_fetch_array($result);
$id = $hits[id];
$result1 = mysql_query("SELECT * FROM countip WHERE ip = '$ip'");
$row = mysql_fetch_array($result1);
$olddate = $row[time];
$oldtime = $row[time];
if($row[ip] == "") {
// if the user has not been here today - count
	  	mysql_query("insert into countip (ip, time) values ('$ip', '$time')");
	  	$userstoday = $hits[userstoday]+1;
	  	mysql_query("update counthits set userstoday='$userstoday' WHERE id like '$id'");
} else {
// if the user has been here today - check if the time specified has passed - count
	$dif = $time-$oldtime;
	if($dif > $timer) {
	    $userstoday = $hits[userstoday]+1;
	    mysql_query("update counthits set userstoday='$userstoday' WHERE id like '$id'");
	    mysql_query("update countip set time = '$time' WHERE ip like '$ip'");
	} else {
		// update the time so that the user must leave for $timer seconds before a count is registered again
		// leave out this line to count after $timer seconds - without updating the time if the user comes back
		// within $timer seconds
		// mysql_query("update countip set time = '$time' WHERE ip like '$ip'");
	}
}
$result = mysql_query("SELECT * FROM counthits WHERE FROM_UNIXTIME(time, '%d%m%Y') like '$date'");
$hits = mysql_fetch_array($result);
// echo out these variables to show them
$userstoday = $hits[userstoday];
$result1 = mysql_query("SELECT SUM(userstoday) as sum FROM counthits");
$total = mysql_fetch_array($result1);
$userstotal = $total[sum];
//mysql_close();
?>