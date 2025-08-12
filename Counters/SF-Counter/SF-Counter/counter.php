<?php

/***************************************************************************
SloughFlash Counter
Copyright(C) SloughFlash.com - All Rights Reserved.
****************************************************************************/

$count = file_get_contents("count.txt");
$count = explode("=", $count);
$count[1] = $count[1]+1;
$file = fopen("count.txt", "w+");
fwrite($file, "count=".$count[1]);
fclose($file);
print "count=".$count[1];
?>