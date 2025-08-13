<?php

$helptxt = array("nocookie" => "Redirect all clicks coming from visitors without cookies to default URL<br><br>It is recommended that you set this to Yes to stop all spiders and bots crawling your website from showing up in the stats.",
                 "ratioon" => "<b>Hits In:</b> Returns hits based on Hits In. Example: 100 hits in with a ratio of 130 will result in 130 hits sent back to trade.<br><br><b>Productivity:</b> Return hits based on productivity. Example: If ratio is set to 100, a site with 200 hits in and 300 clicks (150% Prod.) will be sent back 300 hits");


print <<<END
<html>
<head>
<title>Help</title>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<div align="left">
<font face="Verdana,Arial,Helvetica" size="2">
END;

if ( $_GET["t"] ) { showhelp(); }

print "No help topic selected\n</div>\n</body></html>";
exit;

function showhelp() {
global $helptxt;

$helptopic = $_GET["t"];

print $helptxt[$helptopic];

print "</font>\n</div>\n</body>\n</html>\n";
exit;
}

?>