<html><head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select default_bgcolor, default_fontsize, default_fontface, default_fontcolor,
				heading_bgcolor, heading_fontsize, heading_fontface, heading_fontcolor, firstname, login
				from userinfo where ipaddress ='$ipaddy'");
	$setting = mysql_fetch_row($result);

echo "<body bgcolor='#FFFFFF' text='#000000' link='#000000' vlink='#000000'>";
 echo "<table border='0' cellpadding='0' cellspacing='0'>";
	$result = mysql_query( "select id, task, priority, who_owns, tstamp, who_wrote from tasklist where who_owns ='$setting[9]' order by priority, tstamp");
	$number = mysql_num_rows($result);
	while ($taskdata = mysql_fetch_row($result))
		{
		echo "<tr><td align='right'>", $taskdata[2], ". &nbsp;</td>";
		echo "<td align='left'>", $taskdata[1], "</td></tr>";
                    }
	if ($number < 1)  { echo "<tr><td>You don't have any tasks on your task list.</td></tr>"; }
?>
</table></body></html>
<?php } ?>
