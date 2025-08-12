<?php

// Insert at the very top of your page

$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;

?>




<?php

// Place at the very bottom of your page

$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);
printf ("Page took %f seconds to load.", $totaltime);
// The above line can be changed but remember to keep %f 

?>