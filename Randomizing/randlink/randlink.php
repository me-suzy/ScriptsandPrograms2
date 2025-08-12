<?php 
//Avoid caching results
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//do cURL stuff here
$c   = curl_init('random.yahoo.com/fast/ryl');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($c);
curl_close($c);
if ($result !== false)
{
	echo rtrim(strstr($result,'http')) ;
}
?>