<TITLE>Countdown Eventchoser</TITLE>
<center>
<?
/*
MVW Counter
===========

By Gary Kertopermono

This credit tag may not be removed.
*/

include "countdown.inc.php";

include "eventlist.php";

echo "\n\n<p>";
if($dummy)
{
?>
</center>
<p>
<a href="chooseevent.php">Dummy Event</a><br>
<?
}
for($i=0;$i<sizeof($eventname);$i++)
{
	echo "<a href=\"?event=".$eventname[$i]."\">".$eventdesc[$i]."</a><br>";
}
?>
<br><font size=1>MVW Counter, by Gary Kertopermono</font>