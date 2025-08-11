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
// Filename: pagespdf.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  View one page contents in PDF formate
// ----------------------------------------------------------------------

session_start();

?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	 if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	$key = 1;
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$tkey = "" . $key . "";
$strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if ($rs->RecordCount()	== 0 ) {
    $strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey;
    $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    if ($rs->RecordCount()  == 0 ) {
	    header("Location:pagespdf.php?key=1");
    }
}

// get the field contents
$x_id = @$rs->fields["id"];
$x_title = @$rs->fields["title"];
$x_content = @$rs->fields["content"];
$x_views = @$rs->fields["views"];

// update page views counter
$x_views++;
$strsql = "UPDATE `pages` SET `views`=$x_views WHERE `id`=" . $x_id . " AND `lang`='" . $lang . "'";
$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

mysql_close();

// Remove HTML tags
$x_content = preg_replace('/<(.|\s)*?>/', '', $x_content);
$x_content = preg_replace('/&nbsp;/', '', $x_content);

include ('class.ezpdf.php');
$pdf =& new Cezpdf();
$pdf->ezSetCmMargins(2,2,1.2,1.2);
$pdf->ezStartPageNumbers(300,40,10,'','page {PAGENUM} of {TOTALPAGENUM}');

//$pdf->ezImage("./cmsimages/khaled.jpg",'',50,'none','left');
//$pdf->ezSetDy(50);

$pdf->selectFont('./fonts/Times-Roman.afm');
$pdf->ezText("<b>$x_title</b>",20,array('justification' => 'center'));
$pdf->ezSetDy(-50);

$pdf->ezText("$x_content",12,array('justification' => 'full'));

$pdf->ezStream();
?>
