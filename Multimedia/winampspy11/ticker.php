<?php
// This version only works with Spyamp 1.6.2, http://spyamp.sf.net
/***************************************************************************
 *                       Spyamp Track Display Console V1.1 - WinampSpy
 *                            -------------------
 *   created:                : Friday, Apr. 16th '04.
 *   copyright               : (C) 2004 Blue-Networks / Explodingpanda
 *   email                   : neil@explodingpanda.com
 *   web                     : http://www.explodingpanda.com/
 *
 * The script is well commented, so PLEASE read the comments! Also, the readme
 * will assist with adding the script to your page.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
//===============main config===============//

$address = "home.blue-networks.net";
$port = "5080";
/*
To open my spyamp, I enter http://home.blue-networks.net:5080. The port is 
5080, as entered above, and the address is home.blue-networks.net. Address
can just as well be your home IP address.

Address - Your ip address / other hostname (ie dyndns.org)
Port - The port as specified in Spyamp.
*/
$bg = "raw2.jpg"; //default raw2.jpg
/*
This is the background you are using. It defaults to raw2.jpg as supplied in
the package, but I have also included one more - raw.jpg, for now. You will
probably need to change the font colour to make it look nice.
*/

$fontcolor = "#FFCC33"; //default #FFCC33
$fontsize = "9"; //default 9
$fontfamily = "Verdana, Arial, Helvetica, sans-serif";
/*
This is all just the font colour, size, etc for the display.
Font names are in order of preference, seperated by ", "
*/

//===============end config===============//
$checkonline = @fsockopen($address, $port, $errno, $errstr, 3);
if(!$checkonline){ $stopped = "1";}
else {
$fulladd = "http://".$address.":".$port."/";
$handle = @fopen ($fulladd, "rb");
$trackraw = "";
do {
    $data = @fread($handle, 8192);
    if (strlen($data) == 0) {
        break;
    }
    $trackraw .= $data;
} while(true);
@fclose ($handle);

$first = split ("<tr><th>Status", $trackraw);
$second = split ("<tr><th>Quality", $first[0]);
$third = split ("<tr><th>Position", $second[0]);
$fourth = split ("Title<td>", $third[0]);

$firstime = split ("Position<td>", $trackraw);
$secondtime = split ("<tr><th>Quality", $firstime[1]);
$finaltime = $secondtime[0];
if (!$finaltime){ $finaltime = "Stopped"; };

$track = $fourth[1];

}

if (strlen($track) > 50) {
$finale = substr_replace($track, '...', '-10', 50);
}
else { $finale = $track;}
if ($stopped == "1"){$finale = "                                Winamp is currently closed";}
else {
$finale = $finale . "  (" . $finaltime . ")";
}

echo('
<style type="text/css">
<!--
.style4 {
	font-size: '.$fontsize.';
	color: '.$fontcolor.';
	font-family: '.$fontface.';
	font-weight: bold;
}
-->
</style>
<table width="544" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" align="center" valign="middle" background="http://'.$_SERVER["HTTP_HOST"].'/ticker/raw2.jpg" class="style4">'.$finale.'</td>
  </tr>
</table>
');
?>