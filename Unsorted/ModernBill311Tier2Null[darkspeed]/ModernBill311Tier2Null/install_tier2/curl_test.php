<?
include_once("../include/config/config.locale.php");
include_once("../include/config/config.main.php");
?>
<center><b>Your HTTPS/SSL cURL connection:</b></center><br>
<center>cURL is REQUIRED for TIER2 Licenses ONLY if<br>
        using the Authorize.net/PaymentPlanet or ECHO Gateways.</center>
<br><br>
<b>FILE:</b> path_to_modernbill/include/config/<b>config.main.php</b><br><br>
<table>
<tr><td>$path_to_curl  </td><td>= "<b><?=$path_to_curl?></b>"; </td><td># <-- Command Line Path to cURL</td></tr>
</table>
<br>
<b>Results:</b>
<ul>
<?
if(exec("$path_to_curl https://www.modernserver.com",$test,$ret))
{
    echo "<li>--> <font color=blue>HTTPS/SSL cURL connection <b>OK</b>.</font><br><br>";
    if ($test) for ($idx = 0; $idx < count($test) ; ++$idx) { $pos = $idx+1; echo "LINE_".$pos.":  ".htmlentities($test[$idx])."<BR>"; }
}
else
{
    echo "<li>--> <font color=red>ERROR: HTTPS/SSL cURL connection <b>NOT OK</b>.</font><br><br>";
    htmlentities($test);
    if ($test) for ($idx = 0; $idx < count($test) ; ++$idx) { $pos = $idx+1; echo "LINE_".$pos.":  ".htmlentities($test[$idx])."<BR>"; }
    $stop=1;
}
?>
</ul>
<b>
<? if ($stop) { ?>
<font color=red>
There was an error with the cURL connection.<br>
This can mean one of two things:<br><br>
1) cURL is NOT installed on your server or it was not compiled with SSL support.<br><br>
2) If it is installed, you may need to update the full cURL path in the main config after installation.<br><br>
You may continue, but will need to resolve this later in order to use the integrated payment gateways mentioned above.<br><br>

</font>
<? } else { ?>
<center><font color=blue>No errors detected. cURL is ready to go!</font></center>
<? } ?>
</b>
<br>