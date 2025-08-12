<?php
// Read the data.dat file for countdown info
include("filereader.php");
include("functions.php");
// Convert Date to timestamp
$date_array = explode('-', $date);
$date = mktime(0,0,0, $date_array[0], $date_array[1], $date_array[2]);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" 
href="default.css" />
<title>Tizag Graphic Countdown</title>
</head>
<body style="text-align: center;">
<p>
<a href="index.php">Create/Edit Countdown</a> | <a href="htmlcode.php">View Current Countdown & HTML Code</a>
</p>
<?php
echo '<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>';

if($mode){
	gfxCountdownHTML();
} else {
	plainCountdownHTML();
}

//Graphical or Plain Text Countdown?
if($mode == 1){
	?>
	<h2>Graphical Countdown</h2>
	<?php
	if($border){
		$color_array = explode('.', $bordercolor);
		echo "<img src='preview.php' style='border: 4px solid rgb($color_array[0], $color_array[1], $color_array[2] );' />";
	} else {
		echo "<img src='preview.php' />";
	}
} else{
	echo "<h2>Plain Text Countdown</h2>";
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
}
?>
</body>
</html>