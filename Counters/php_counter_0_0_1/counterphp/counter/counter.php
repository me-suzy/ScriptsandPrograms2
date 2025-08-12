<?php

/***************************************************************************
 *                            counter.php
 *                            ----------------
 *   version              : version 1.0
 *   begin                : July 29, 2003
 *   copyright            : Jive Networks Resources
 *   website              : http://www.jivenetworks.info
 *
 *
 *
 ***************************************************************************/


$ip = getenv("REMOTE_ADDR");
$name = "logger.txt";

$fo = fopen($name, "r");
$fr = fread($fo, filesize($name));
fclose($fo);

if(strstr($fr, $ip)){

$file = file($name);
$unique = array_unique($file);
$hits = count($unique) - 1;
$counter = "<span class=\"counter\">$hits</span>";
print $counter;

} else {

$fp = fopen($name, "w");
$fadd = $fr."\n".$ip;
$fw = fwrite($fp, $fadd);
fclose($fp);

}

?>