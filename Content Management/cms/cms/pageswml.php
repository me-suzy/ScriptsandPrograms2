<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: pageswml.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Call some mobile phone simulaters for WAP browsing
// ----------------------------------------------------------------------
?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<?php
$path_arr = explode("/", $_SERVER['SCRIPT_NAME']);
$temp = array_pop($path_arr);
$path = implode("/", $path_arr);
$path = $_SERVER['HTTP_HOST'] . $path . "/wml.php";
?>
<TABLE width="90%" border=0 align="center" cellPadding=5 cellSpacing=3>
  <TBODY>
    <TR align="center"> 
      <TD vAlign=bottom><FONT face=Arial size=1><A 
      href="http://gelon.net/cgi-bin/wapalizeericssonr320.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Ericsson R320!';return true;"
      onmouseout="window.status='';return true;"><IMG height=63
      alt="" src="cmsimages/ericssonr320mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://gelon.net/cgi-bin/wapalizeericssonr320.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Ericsson R320!';return true;"
      onmouseout="window.status='';return true;"><B>Ericsson
        R320</B></A> </FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A 
      href="http://www.gelon.net/cgi-bin/wapalize.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Nokia 7110!';return true;"
      onmouseout="window.status='';return true;"><IMG height=65 alt=""
      src="cmsimages/nokia7110mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://www.gelon.net/cgi-bin/wapalize.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Nokia 7110!';return true;"
      onmouseout="window.status='';return true;"><B>Nokia
        7110</B></A></FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://www.gelon.net/cgi-bin/wapalizenokia6210.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Nokia 6210!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=55 alt="" src="cmsimages/nokia6210mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://www.gelon.net/cgi-bin/wapalizenokia6210.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Nokia 6210!';return true;"
      onmouseout="window.status='';return true;"><B>Nokia 
        6210</B></A></FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://www.gelon.net/cgi-bin/wapc35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens C35!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=56 alt="" src="cmsimages/c35mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://www.gelon.net/cgi-bin/wapc35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens C35!';return true;"
      onmouseout="window.status='';return true;"><B>Siemens 
        C35</B></A></FONT></TD>
</tr><tr align="center">
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://www.gelon.net/cgi-bin/wapm35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens M35!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=50 alt="" src="cmsimages/m35mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://www.gelon.net/cgi-bin/wapm35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens M35!';return true;"
      onmouseout="window.status='';return true;"><B>Siemens 
        M35</B></A></FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://www.gelon.net/cgi-bin/waps35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens s35!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=50 alt="" src="cmsimages/s35mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://www.gelon.net/cgi-bin/waps35.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View the new Siemens s35!';return true;"
      onmouseout="window.status='';return true;"><B>Siemens 
        s35</B></A></FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://gelon.net/cgi-bin/motorola_a6188.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View wapsites using Motorola a6188  (Accompli)!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=39 alt="" src="cmsimages/a6188mini.gif" width=20 align=MIDDLE
      border=0></A><BR>
        <A 
      href="http://gelon.net/cgi-bin/motorola_a6188.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View wapsites using Motorola a6188 (Accompli)!';return true;"
      onmouseout="window.status='';return true;"><B>Motorola 
        A6188</B></A></FONT></TD>
      <TD vAlign=bottom><FONT face=Arial size=1><A href="http://gelon.net/cgi-bin/wapmotorolap7389.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View wapsites using Motorola p7389!';return true;"
      onmouseout="window.status='';return true;"><IMG 
      height=66 alt="" src="cmsimages/motorolap7389mini.gif" width=20
      align=MIDDLE border=0></A><BR>
        <A 
      href="http://gelon.net/cgi-bin/wapmotorolap7389.cgi?url=http://<?php echo $path; ?>" target="_blank" class=vl
      onmouseover="window.status='View wapsites using Motorola p7389!';return true;"
      onmouseout="window.status='';return true;"><B>Motorola 
        P7389</B></A></FONT></TD>
    </TR>
  </TBODY>
</TABLE>
<?php include_once ("footer.php") ?>
