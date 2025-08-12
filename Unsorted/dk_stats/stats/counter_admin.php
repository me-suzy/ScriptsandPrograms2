<link rel="stylesheet" href="stylesheet.css" type="text/css">
<?
include ("connect.php");
$spacer = "<hr size='1'>";
$time = time();
$today = date("d. F Y",$time);
$date = date("mY",$time);
$date_today = date("dmY",$time);
echo "<span class='headline'>Hits month:</span>";
echo "$spacer";
$result = mysql_query("SELECT * FROM counthits GROUP BY FROM_UNIXTIME(time, '%m%Y')");
while ($row = mysql_fetch_array($result)){
	$month_spec = date("mY",$row[time]);
	$month = date("F, Y",$row[time]);
	$result1 = mysql_query("SELECT SUM(userstoday) as sum FROM counthits WHERE FROM_UNIXTIME(time, '%m%Y') like '$month_spec'  AND start like 'no'");
	$row1 = mysql_fetch_array($result1);
	echo "<span class='time'>$month</span> - $row1[sum]";
	echo "<br>";
}
echo "$spacer";
if (!$limit){
	$limit = "10";
}
echo "<span class='headline'>Hits a day last $limit days:</span>";
echo "$spacer";
$result = mysql_query("SELECT * FROM counthits WHERE start like 'no' ORDER BY time DESC LIMIT 0,$limit");
while ($row = mysql_fetch_array($result)){
	$time2 = date("d. F Y",$row[time]);
	echo "<span class='time'>$time2</span> - $row[userstoday]";
	echo "<br>";
}
echo "$spacer";
echo "<span class='headline'>Days with most hits top $limit:</span>";
echo "$spacer";
$result = mysql_query("SELECT * FROM counthits WHERE start like 'no' ORDER BY userstoday DESC LIMIT 0,$limit");
$i = "1";
while ($row = mysql_fetch_array($result)){
	$time = date("d. F Y",$row[time]);
	echo "$i | <span class='time'>$time</span> - $row[userstoday]";
	echo "<br>";
	$i++;
}
echo "$spacer";
echo "<span class='headline'>Average without todays hits:</span>";
echo "$spacer";
$result = mysql_query("SELECT * FROM counthits WHERE start like 'no' AND FROM_UNIXTIME(time, '%d%m%Y') not like '$date_today'");
$amount = mysql_num_rows($result);
$result = mysql_query("SELECT SUM(userstoday) as sum FROM counthits WHERE start like 'no' AND FROM_UNIXTIME(time, '%d%m%Y') not like '$date_today'");
$row = mysql_fetch_array($result);
$hits = ceil($row[sum]/$amount);
echo "$hits pr dag";
echo "$spacer";
echo "<span class='headline'>Today $today:</span>";
echo "$spacer";
$result = mysql_query("SELECT * FROM counthits WHERE FROM_UNIXTIME(time, '%d%m%Y') like '$date_today'");
$hits = mysql_fetch_array($result);
echo $hits[userstoday];
echo "$spacer";
echo "<span class='headline'>Choose amount of days:</span>";
echo "$spacer";
echo "<a href='?limit=10'>10 days</a>";
echo " | ";
echo "<a href='?limit=20'>20 days</a>";
echo " | ";
echo "<a href='?limit=30'>30 days</a>";
mysql_close();
?>