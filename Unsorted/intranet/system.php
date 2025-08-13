<html>
<head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
$appheaderstring='Network';
include("header.php");
echo "<center><table width='95%' border='0' cellpadding='0' cellspacing='2'><tr><td>";

// SEND THE WINPOPUP MESSAGE TO ALL THE WORKSTATIONS.
if ($message != "") {

	$result = mysql_query("select machinename from userinfo where ipaddress ='$ipaddy'");
	$row = mysql_fetch_row($result);
	$ident = $row[0];
	$result = mysql_query("select machinename, firstname, ipaddress from userinfo order by machinename");
	
	while ($row = mysql_fetch_row($result))
		{
		if ($row[0] != "admin")
			{
			$dude = $row[0];
			if ($user[$dude] == "on")
				{
				$ident = strtoupper($ident);
				$dude = strtoupper($dude);
				exec("echo $message | smbclient -M $dude -U $ident", $crap[], $nothin[$dude]);
				}		
			}
		}
         }

$result = mysql_query("select machinename, firstname, ipaddress from userinfo order by machinename");

echo "<tr><td bgcolor='666666' align='center'><font color='white' size='2'>USER</font></td><td bgcolor='666666' align='center'><font color='white' size='2'>STAT</font></td><td bgcolor='666666' align='left' width='5'><font color='white' size='2'>SEND<br>MSG?</font></td><td bgcolor='666666' align='left'><font color='white' size='2'>LAST<br>MSG?</font></td><td align='left' rowspan='9' valign='top' bgcolor='FFFFFF'><font color='000000'>";
if ($message != "") { echo "&nbsp; &nbsp; The last message you sent was:<p>&nbsp; &nbsp; ", $message; } else { echo "&nbsp; &nbsp; Click the checkbox next to each person that you want to receive your message. Then, type your message in the text box below and click Send Message to send it. To refresh this page, just re-click the Network icon on the menu (don't click Reload or Refresh on your browser)."; }
echo "</font></td></tr>";

echo "<form action='system.php' method='post'>";
while ($row = mysql_fetch_row($result))
	{
	if ($row[0] != "admin")
		{
		echo "<tr><td align='right' valign='center'><b>";

		$bing = $row[0];
		exec("ping -c 1 $bing", $crap[], $ping[$bing]);
		echo $row[1];
		echo "</b></td><td align='center' valign='center'>";
		if ($ping[$bing] == 0) {  echo "<img src='icons/green.gif'>";
			} else {
			echo "<img src='icons/red.gif'>";
				}
		echo "</td><td valign='center'>";
		if ($ping[$bing] == 0) { echo "<input type='checkbox' name='user[", $row[0], "]'>"; }
		echo "</td><td>";
		if ($user[$bing] == "on")
			{
				if ($ping[$bing] == 0) { echo "<img src='icons/green.gif'>"; } else { echo "<img src='icons/yellow.gif'>"; }
			} else { echo "<img src='icons/red.gif'>"; }
		echo "</td></tr>";			
		}
	}
// GET ALL DIAL-UP TARGETS OUT OF THE DATABASE
dbconnect($dbusername,$dbuserpasswd);
$presult=mysql_query("select * from network where type='DialUp'");
while($plist=mysql_fetch_array($presult))
	{
	echo "<tr><td align='right'><b>Dial-Up";
	if($plist[pingwhich] == 'ip') { $target=$plist[ipaddress]; } else { $target=$plist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[dialup]);
	echo "</b></td><td align='center'>";
	if ($ping[dialup] == 0)
		{
		echo "<img src='icons/green.gif'></td><td> </td>";
		} else
			{
			echo "<img src='icons/red.gif'><td> </td>";
			}
	echo "<td><img src='icons/red.gif'></td></tr>";
	}
// MESSAGE SENDING PART.

echo "<tr><td colspan='5' align='right'><input type='text' size='60' name='message'><br><input type='submit' value='Send Message'></td></tr>";

echo "<tr><td>&nbsp;</td><td> </td><td> </td></tr>";
?>


<tr><td bgcolor='666666' align='center'><font color='white' size='2'>SERVER</font></td><td bgcolor='666666' align='center'><font color='white' size='2'>STAT</font></td><td bgcolor='666666' align='center' colspan='3'><font color='white' size='2'>SYSTEM INFORMATION</font></td></tr>
<?php
// LOCAL SERVERS

dbconnect($dbusername,$dbuserpasswd);
$presult=mysql_query("select * from network where type='Gateway'");
while($plist=mysql_fetch_array($presult))
	{
	echo "<tr><td align='right'><b>", $plist[domainname];
	if($plist[pingwhich] == 'ip') { $target=$plist[ipaddress]; } else { $target=$plist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[server]);
	echo "</b></td><td align='center'>";
	if ($ping[server] == 0)
		{
		echo "<img src='icons/green.gif'></td>";
		} else
			{
			echo "<img src='icons/red.gif'>";
			}
	echo "<td colspan='3'>(", $plist[type], ") ";	
	if($plist[message]) { include("$plist[message]"); }
	echo "</td></tr>";
	}
dbconnect($dbusername,$dbuserpasswd);
$qresult=mysql_query("select * from network where type='IntranetServer'");
while($qlist=mysql_fetch_array($qresult))
	{
	echo "<tr><td align='right'><b>", $qlist[domainname];
	if($qlist[pingwhich] == 'ip') { $target=$qlist[ipaddress]; } else { $target=$qlist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[server]);
	echo "</b></td><td align='center'>";
	if ($ping[server] == 0)
		{
		echo "<img src='icons/green.gif'></td>";
		} else
			{
			echo "<img src='icons/red.gif'>";
			}
	echo "<td colspan='3'>(", $qlist[type], ") ";
	if($qlist[message]) { include("$qlist[message]"); }
	echo "</td></tr>";
	}
?>

<tr><td> </td><td bgcolor='666666' align='center'><font color='white' size='2'>STAT</font></td><td bgcolor='666666' align='center' colspan='3'><font color='white' size='2'>INTERNET</font></td></tr>
<?
dbconnect($dbusername,$dbuserpasswd);
$qresult=mysql_query("select * from network where type='ISP'");
while($qlist=mysql_fetch_array($qresult))
	{
	echo "<tr><td align='right'>";
	if($qlist[pingwhich] == 'ip') { $target=$qlist[ipaddress]; } else { $target=$qlist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[server]);
	echo "</td><td align='center'>";
	if ($ping[server] == 0)
		{
		echo "<img src='icons/green.gif'></td>";
		} else
			{
			echo "<img src='icons/red.gif'>";
			}
	echo "<td colspan='3'><b>ISP</b> (", $qlist[domainname], ", ", $qlist[ipaddress], ") ";
	echo "</td></tr>";
	}
dbconnect($dbusername,$dbuserpasswd);
$qresult=mysql_query("select * from network where type='OurWebSite'");
while($qlist=mysql_fetch_array($qresult))
	{
	echo "<tr><td align='right'>";
	if($qlist[pingwhich] == 'ip') { $target=$qlist[ipaddress]; } else { $target=$qlist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[server]);
	echo "</td><td align='center'>";
	if ($ping[server] == 0)
		{
		echo "<img src='icons/green.gif'></td>";
		} else
			{
			echo "<img src='icons/red.gif'>";
			}
	echo "<td colspan='3'><b>", $qlist[domainname], "</b> (", $qlist[ipaddress], ") ";
	echo "</td></tr>";
	}
dbconnect($dbusername,$dbuserpasswd);
$qresult=mysql_query("select * from network where type='MiscWebSite'");
while($qlist=mysql_fetch_array($qresult))
	{
	echo "<tr><td align='right'>";
	if($qlist[pingwhich] == 'ip') { $target=$qlist[ipaddress]; } else { $target=$qlist[domainname]; }
	exec("ping -c 1 $target", $crap[], $ping[server]);
	echo "</td><td align='center'>";
	if ($ping[server] == 0)
		{
		echo "<img src='icons/green.gif'></td>";
		} else
			{
			echo "<img src='icons/red.gif'>";
			}
	echo "<td colspan='3'><b>", $qlist[domainname], "</b> (", $qlist[ipaddress], ") ";
	echo "</td></tr>";
	}

?>

</table>
                                                </center>
<?php } else { echo "Error<br>", $hdigkeng; } ?>
</body></html>
