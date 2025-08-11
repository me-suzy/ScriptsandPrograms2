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
// Filename: header.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Common header for system pages
// ----------------------------------------------------------------------

ini_set('arg_separator.output', '&;amp;');
ob_start("ob_gzhandler");

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

$meta = file_get_contents("design/meta.html");
if(file_exists("design/marquee_$lang.html")){ 
   $marquee = file_get_contents("design/marquee_$lang.html"); 
}else{
   $marquee = file_get_contents("design/marquee_en.html");
}
$marquee = "<marquee class=marquee direction=" . ALIGN . ">$marquee</marquee>";
$LBL_SEARCH_CMD = LBL_SEARCH_CMD;
$LBL_SEARCH = LBL_SEARCH;
$search_block=<<<END
<FORM action=search.php method=get>$LBL_SEARCH
<INPUT maxLength=255 size=25 name=q>
<INPUT type=submit value="$LBL_SEARCH_CMD" name=submit>
</FORM>
END;

include_once ("template.class.php");

$template = new Template;

if(file_exists("design/template_$lang.html")){
   $template->load("design/template_$lang.html");
}else{
   $template->load("design/template_en.html");
}

$template->replace("SITE_TITLE", SITE_TITLE);
$template->replace("CHARSET", CHARSET);
$template->replace("META", $meta);
$template->replace("DIRECTION", DIRECTION);
$template->replace("SEARCH_BLOCK", $search_block);
$template->replace("MARQUEE", $marquee);

// Start output buffering
ob_start();

?>

