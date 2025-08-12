<?
include("header.php");
?>
<?php

include("config.php");
$curr = "05_";
echo "<font size=6><u>$name Isssue Code $curr</u></font><br><br>";
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
