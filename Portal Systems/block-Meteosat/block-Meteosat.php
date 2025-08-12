<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Meteosat.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}



$today = getdate();
$year = $today[year];
$content = "<table align=center>";
$content .= "<tr><td>";
$content .= "<center><br><A HREF=\"http://www.goes.noaa.gov/browse.html\" target=\"_blank\"><IMG SRC=\"images/ECIR.JPG\" width=\"128\" border=\"0\" height=\"128\" title=\"Click for larger image\"></A></center>";
$content .= "</td></tr>";
$content .= "<tr><td>";
$content .= "<center>NorthAmerica &copy;</center>";
$content .= "<center><a href=\"http://www.goes.noaa.gov/\" target=\"_blank\">NOAA</a></center>";
$content .= "</td></tr>";
$content .= "<tr><td>";
$content .= "<center><A HREF=\"http://oiswww.eumetsat.org/IDDS-cgi/listImages\" target=\"_blank\"><IMG SRC=\"images/M7_thumb_sectors.jpg\"  width=\"128\" border=\"0\" height=\"128\" title=\"Click for larger image\"></A></center>";
$content .= "</td></tr>";
$content .= "<tr><td>";
$content .= "<center>Europe &copy;</center>";
$content .= "<center><a href=\"http://www.eumetsat.de/\" target=\"_blank\">copyright $year EUMETSAT</a></center>";
$content .= "</td></tr>";

$content .= "<tr><td>";
$content .= "<center><A HREF=\"http://maps.google.com/maps?ll=40.689851,-74.044998&spn=0.005450,0.007811&t=k&hl=en\" target=\"_blank\"><IMG SRC=\"images/ls130.jpg\"  width=\"128\" border=\"0\" height=\"128\" title=\"Click for larger image\"></A></center>";
$content .= "</td></tr>";
$content .= "<tr><td>";
$content .= "<center>U.S.A. &copy;</center>";
$content .= "<center><a href=\"http://maps.google.com/maps\" target=\"_blank\">copyright $year Google maps</a></center>";
$content .= "</td></tr>";

$content .= "<tr><td>";
$content .= "<center>made for PHP-Nuke</center>";
$content .= "<center><a href=\"http://www.hecargo.net/\">by Claus Bamberg</a></center>";
$content .= "</td></tr>";
$content .= "</table><br>";

?>