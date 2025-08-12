<?php

/****************************************************
 *   file      : hits.php							*
 *   version   : 0.1								*
 *   date      : October 20, 2005					*
 *   copyright : Abbas Alafoo						*
 *   website   : http://www.website-hostings.net	*
 ****************************************************/

$hits = "./hits.txt";

$handle = fopen($hits, "r");
$count = fread($handle, filesize($hits));
$count = $count+1;
fclose($handle);

$handle = fopen($hits, "w");
fwrite($handle,$count);
fclose($handle);

print $count;

?>