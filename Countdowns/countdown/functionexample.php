<?
/*
MVW Counter
===========

By Gary Kertopermono

This credit tag may not be removed.
*/

include "countdown.func.php";
?>
<title>Function Example for the MVW Countdown</title>
<h1>Function Example for the MVW Countdown</h1>
This file will demonstrate the function examples in the <b>countdown.func.php</b> file.
<p>
<i>Text-only mode - This will show the time until next Newyears Eve.</i>
<p>
<b>Code:</b><p>
<code>&lt;?php<br>
$currenttime = countdown_text(0,0,0,1,1,date("Y")+1);<br>
$seconds = $currenttime;<br>
$days = countdown_getdays($seconds);<br>
$seconds-= $days*24*3600;<br>
$hours = countdown_gethours($seconds);<br>
$seconds-= $hours*3600;<br>
$minutes = countdown_getminutes($seconds);<br>
$seconds-= $minutes*60;<br>
echo "Until Newyears ".(date("Y")+1).": $days days, $hours hours, $minutes minutes and $seconds seconds.";<br>
?&gt;</code><p>

<b>Results:</b><p>

<?php
$currenttime = countdown_text(0,0,0,1,1,date("Y")+1);
$seconds = $currenttime;
$days = countdown_getdays($seconds);
$seconds-= $days*24*3600;
$hours = countdown_gethours($seconds);
$seconds-= $hours*3600;
$minutes = countdown_getminutes($seconds);
$seconds-= $minutes*60;
echo "Until Newyears ".(date("Y")+1).": $days days, $hours hours, $minutes minutes and $seconds seconds.";
?>
<p>
<i>Flash mode 1 - This will show the dummy Flash, which counts down to an hour later. It uses the <b>countdown.swf</b> file included.</i>
<p>
<b>Code:</b><p>
<code>&lt;?php<br>
echo countdown_flash("countdown.swf",200,100);<br>
?&gt;</code><p>

<b>Results:</b><p>

<?php
echo countdown_flash("countdown.swf",200,100);
?>

<p>
<i>Flash mode 2 - This will show the time until Newyears Eve. It uses the <b>countdown.swf</b> file included.</i>
<p>
<b>Code:</b><p>
<code>&lt;?php<br>
echo countdown_flash("countdown.swf",200,100,true,"nextnewyears.php");<br>
?&gt;</code><p>

<b>Results:</b><p>

<?php
echo countdown_flash("countdown.swf",200,100,true,"nextnewyears.php");
?>
<br><font size=1>MVW Counter, by Gary Kertopermono</font>