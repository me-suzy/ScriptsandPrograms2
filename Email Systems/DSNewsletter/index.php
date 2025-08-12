<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<?php 
	include("config.php");
	?>
	<title><? echo "$name"; ?></title>
</head>

<body>
<?
include("header.php");
?>
<? 
echo "<font size=7><b><u>$name</font></b></u><BR>"; 
echo "<font size=4><b><i>$blurb</font></b></i><br><br>";
?>
<u>Subscribe:</u>
<form action="subscribe.php" method="POST"><input name="email" type="text" value=""> <input type="submit" value="Subscribe"></form>
<br>
<u>Unsubscribe:</u>
<form action="unsubs.php" method="POST"><input name="email" type="text" value=""> <input type="submit" value="Unsubscribe"></form>
<br><br>
<?php

include("config.php");
$curr = file_get_contents("issue.html");
echo "<font size=6><u>$name Issue Code $curr</u></font><br><br>";
// $files = array();
$dir = "perm";
$dh = opendir($dir);

while (false !== ($filename = readdir($dh))) {

    if ($filename == '.' || $filename == '..') {
        continue; // skip these
    }

    if (0 === strpos($filename, $curr)) { // if the prefix exists at the very beggining of the filename
        $files = "$dir/$filename";
		$str = file_get_contents("$files");
		echo "$str <br><br>";
    }
}

 echo "";
 ?>
<?
include("footer.php");
?>
</body>
</html>

