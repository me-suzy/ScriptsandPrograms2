<?
/***************************************************************************
 *                             jfunction.php
 *                            -------------------
 *   begin                : Saturday, Dec 04, 2004
 *   copyright            : (C) 2004 Network Rebusnet
 *   contact              : http://rockcontact.rebusnet.biz/contact/
 *
 *   $Id$
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
 
require_once("./includes/config.inc.php");
require_once("./includes/template.inc.php");
require_once("./includes/common.inc.php");
require_once("./includes/counter.inc.php");

increment_counter(COUNTER_PAGE_JFUNCTIONS);

if( isset($_GET['lc']) || isset($_POST['lc']) ) {
  $lc = ( isset($_POST['lc']) ) ? $_POST['lc'] : $_GET['lc'];
} else {
  $lc = '';
}

$lc = get_page_language($lc);
require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/jfunctions.js.php"));

$tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/jfunctions.js.tpl"));
$tpl = template_transform($tpl, $msg);

header('Content-Type: text/javascript; charset=utf-8');
echo $tpl;

?>
