<?php
include("filereader.php");
$date_array = explode('-', $date);
$date = mktime(0,0,0, $date_array[0], $date_array[1], $date_array[2]);

if(mktime() < $date){
	$difference = $date - mktime();
} else{
	$difference = 0;
}
$days = (int)($difference/86400);
$temp_date = $difference % 86400;
$hours = (int)($temp_date/3600);
$temp_date = $temp_date % 3600;
$minutes = (int)($temp_date/60);
$seconds = $temp_date % 60;


$date_str = "$days Days, $hours Hours, $minutes Minutes, $seconds Seconds";
echo "<h3>$text</h3>\n";
echo "<b>$date_str</b>";
?>