<?php
  include("config.php");
  $string = stripslashes($_SERVER['PHP_SELF']) . stripslashes($_SERVER['QUERY_STRING']);
  $blf = "$logs/log.html";
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
 ?>