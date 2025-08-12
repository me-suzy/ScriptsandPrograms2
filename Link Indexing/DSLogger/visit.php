<?php
include("config.php");
$string = stripslashes($_SERVER['QUERY_STRING']);
// SINGLE LOG
$logfile = "$logs/" . $string . "";
if(file_exists($logfile)) {
$file = "$logs/" . $string . "";
$oldlog = file_get_contents("$file");
$newlog = $oldlog + 1;
$fp = fopen($file, 'w');
fwrite($fp, $newlog);
fclose($fp);
} else {
$filename = "$logs/" . $string . "";
$log = "1";
$fp = fopen($filename, 'w');
fwrite($fp, $log);
fclose($fp);
}

// BIG LOG
$blf = "$logs/log.html";
//$g = date(g);
//$i = date(i);
//$A = date(A);
//$d = date(d);
//$m = date(m);
//$y = date(y);
//$td = "$g:$i $A  $d-$m-$y";
$time = time()- ($sof*3600);
$td = date('g:i A  d-m-y', $time);
if ($_SERVER['HTTP_REFERER'] != "") {
$biglog = "[" . $td . "] - [" . $_SERVER['REMOTE_ADDR'] . "] : " . $string . " : [" . $_SERVER['HTTP_REFERER'] . "]<br>";
} else {
$biglog = "[" . $td . "] - [" . $_SERVER['REMOTE_ADDR'] . "] : " . $string . " : [Ref N/A]<br>";
}
$file = fopen($blf, 'a+');
fwrite($file, $biglog);
fclose($file);  

if (substr($string,0,7) == "http://") { 
$nloc = str_replace("\\", "/", $string);
header("Location: " . $nloc . "");
} else { 
$nloc = str_replace("\\", "/", $string);
header("Location: http://" . $nloc . ""); 
}
?>