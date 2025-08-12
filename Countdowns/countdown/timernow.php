<?php
/*
MVW Counter
===========

By Gary Kertopermono

This credit tag may not be removed.
*/
$cursec = intval(date("s"));
$curmin = intval(date("i"));
$curhour = intval(date("G"));
$curday = intval(date("j"));
$curmonth = intval(date("n"));
$curyear = intval(date("Y"));

echo "cursec=$cursec&curmin=$curmin&curhour=$curhour&curday=$curday&curmonth=$curmonth&curyear=$curyear&vloaded=1";

?>