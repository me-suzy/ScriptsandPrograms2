<?php

print "<font face='verdana' size='1'>";

If (file_exists( "counter.txt" )){
	include_once "read.php";

	$count++;

	echo "This page has been visited <b>$count</b> times";

	include_once "write.php";
} else {
	print "<font color='red'><b>Error</b>: Cannot find Counter Data File!</font>";
}

print "</font>";

?>