<?php
/***************************************************************************
 * (c)2003, 2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
echo "PHP version: ";
echo phpversion();
echo "<br>";
echo "$new_global_handling is set to: ";
if($new_global_handling)
	echo "true";
else
	echo "false";
echo "<br>";
echo "$has_file_errors is set to: ";
if($has_file_errors)
	echo "true";
else
	echo "false";
echo "<br>";
echo "$upload_avail is set to: ";
if($upload_avail)
	echo "true";
else
	echo "false";
echo "<br>";
echo "$insafemode is set to: ";
if($insafemode)
	echo "true";
else
	echo "false";
echo "<br>";
echo "$gdavail is set to: "
if($gdavail)
	echo "true";
else
	echo "false";
echo "<br>";
echo "Detected cookiedomain is: ";
echo $cookiedomain;
?>