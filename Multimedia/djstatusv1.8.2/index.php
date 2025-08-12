<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("config.php");

if ($scsuccs!=1) {
if($streamstatus == "1"){
if (isset($dj)) {
echo "<b>Listeners:</b> $currentlisteners&nbsp;&nbsp;&nbsp;<a href=\"http://$scip:$scport/listen.pls\">Click to listen!</a><br>
<b>Current Title</b>: $servertitle<br>
<b>Current Song:</b> $song[0]<br>
<b>Current DJ ID</b>: $dj<br>
<b>Current DJ</b>: $name<br>
";

if ((empty($aimdb)) && (isset($aim) && $aim) && ($aim != "N/A")) {
$aimdb = $aim;
}

if ((empty($icqdb)) && (isset($icq) && $icq)) {
$icqdb = $icq;
}

if (isset($aimdb) && $aimdb) {
echo "<b>AIM</b>: $aimdb<br>";
}

if (isset($msn) && $msn) {
echo "<b>MSN</b>: $msn<br>";
}

if (isset($yim) && $yim) {
echo "<b>YIM</b>: $yim<br>";
}

if (isset($icqdb) && $icqdb) {
echo "<b>ICQ</b>: $icqdb<br>";
}

if (isset($address) && $address) {
echo "<b>Song request page</b>: <a href=\"$address\">Link</a>";
}

if ($showsetby == 1) {
echo "<br><br><b>This DJ was found by checking:</b> $setby";
}

} else {
echo "<b>A DJ is not currently signed on to the system. Please check again later.</b>";
}
} else {
echo "<b>A DJ is not currently connected to the radio. Please check again later.</b>";
}
} else {
echo "<b>The radio is currently down. Please check again later.</b>";
}
echo "<br><br><font size=\"-1\"><strong>Powered by DJ Status v$version - &copy;2005 Nathan Bolender - <a href=\"http://www.nathanbolender.com\" target=\"_blank\">www.nathanbolender.com</a></strong></font>";
?>